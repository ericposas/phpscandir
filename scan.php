<?php

# need to write a token-swap function in here or in a new file.. 
# write code that scans a directory, returns an array, re-formats that array for use in BannerKit 

# all other globally-accessible variables set within the functions 
$elements; #file resource 

# scan the provided directory 
if(!isset($argv[1])){
  echo "Please provide a directory to scan for images.\n";
}else{
  createElementsArray();
}

function createElementsArray(){
  global $argv;
  global $imgarr; #creates $imgarr variable and sets it to the global space 
  $dir = $argv[1];
  $imgarr = scandir($dir);
  cleanImagesArray();
  processElems();
  arrangeFormattedArray();
  openArrayElementsFile();
}

function cleanImagesArray(){
  global $imgarr;
  foreach($imgarr as $key=>$val){
    #remove directory '. .' and '.DS_Store' from array, if found -- "clean" the array 
    if($val == "." || $val == ".." || $val == ".DS_Store"){
      unset($imgarr[$key]);
    }
    #remove excluded filenames 
    if($val === "back.jpg" || $val === "loading-circle.png" || $val === "clicktag.png" || $val === "reload.png" || $val == "button.png"){
      unset($imgarr[$key]);
    }
  }
}

function processElems(){
  global $imgarr,$exts,$filenames,$formattedarr;
  $exts=[];
  $filenames=[];
  $matches=[];
  $rgx = '/(.+)(\.\w{3})/';
  foreach($imgarr as $key=>$val){
    if(preg_match($rgx, $val, $matches)){
      #handle .jpg & .png separately
      $matches[2] === ".jpg" ? ($filenames[$key] = "['".$matches[1]."']") : ($filenames[$key] = "'".$matches[1]."'");
    }
  }
}

function arrangeFormattedArray(){
  global $formattedarr, $filenames;
  $formattedarr = "var e = [" . implode(", ", $filenames) . "];";
  #echo $formattedarr."\n";
}

function openArrayElementsFile(){
  global $formattedarr, $elements;
  $name = "elements.js";
  #open new $elements file handle 
  if(!$elements = fopen($name, "w")){
    echo "Error opening new 'elements.js' file for writing.";
  }else{
    writeElementsFile();
  }
}

function writeElementsFile(){
  global $elements, $formattedarr;
  if(!fwrite($elements, $formattedarr)){
    echo "Failed to write to 'elements.js' file";
  }else{
    fclose($elements);
  }
}

