<?php

# write code that scans a directory, returns an array, re-formats that array for use in BannerKit 

# all globally-accessible variables set within the functions 

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
}

# remove directory '. .' and '.DS_Store' from array, if found -- "clean" the array 
function cleanImagesArray(){
  global $imgarr;
  foreach($imgarr as $key=>$val){
    if($val == "." || $val == ".." || $val == ".DS_Store"){
      unset($imgarr[$key]);
    }
  }
}

function processElems(){
  global $imgarr,$exts,$filenames,$formattedarr;
  $exts=[];
  $filenames=[];
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
  $formattedarr = "var e = [" . implode(", ", $filenames) . "]";
  echo $formattedarr."\n";
}

