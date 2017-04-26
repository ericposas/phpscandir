<?php

# Rewrite for use in swapping any sort of token, not just for placing 'frames.js' into 'main.js' 
# Find the %F% token in main.js and replace it with our frame animation set of functions 

$tokenswap = new TokenSwap($argv);  #argument list order: file-to-copy, file-with-contents-to-replace-token, token-value 
#now we can specify custom tokens to swap.. niiiice. 

Class TokenSwap {
  #checkArgs($argv);  --# changed to the "__construct" function 
  private $values = [];

  # check for parameters passed to the script 
  public function __construct($argv){
    # Process and set 'ogfilepath' from pasted path 
    #/Users/eposas/Desktop/300x250/js/main.js --create regular expression to extract path 
    $matches = [];
    preg_match('/(.+\/)(\w+.js)/', $argv[1], $matches);  # match all before "filename.js" 
    $this->ogfilepath = $matches[1];
    $this->file = $matches[2];
    $this->newfile = "new.js";
    
    # Pass in the 'main.js' file location 
    if(isset($argv[1])){
      $file = $argv[1];
    }else{
      echo "Please pass in the '.js' file to copy.\n";
      exit();
    }
    
    if(isset($argv[2])){
      $tokenfile = $argv[2];
    }else{
      echo "Please pass in the '.js' file that will replace the token.\n";
      exit();
    }
    
    if(isset($argv[3])){
      $this->token = $argv[3];
    }else{
      echo "Please specify a token to replace.";
      exit();
    }
    
    $this->values['tokenfile'] = $this->getTokenFile($tokenfile);
    $this->getContentsOfFile($file);
  }
  
  #file that will replace token demarked by //%F% or something similar 
  private function getTokenFile($tokenf){
    if(!$tokenfile = file_get_contents($tokenf)){
      echo "Error getting the contents of the supplied token file.";
    }else{
      return $tokenfile;
    }
  }
  
  private function getContentsOfFile($file){
    if(!$originalcontents = file_get_contents($file)){
      echo "Can't copy original file's contents\n";
    }else{
      $this->createNewFile($originalcontents);
    }
  }
  
  private function createNewFile($og){
    if(!$newhandle = fopen($this->newfile, "w")){
      echo "Error creating new js file for writing to.\n";
    }else{
      $this->writeIntoNewFile($newhandle, $og);
    }
  }
  
  private function writeIntoNewFile($handle, $ogcontent){
    # Let's alter the $ogcontent string now 
    $token = "//%F%";
    if($alteredcontent = str_replace($token, $this->tokenfile, $ogcontent, $count)){
      if($count < 1){
        echo "Could not find/replace the supplied token.";
      }
    }
    
    if(!fwrite($handle, $alteredcontent)){
      echo "Error writing $this->file content into new file.\n";
    }else{
      $this->moveNewFile();
    }
  }
  
  private function moveNewFile(){
    if(rename($this->newfile, $this->ogfilepath.$this->file)){
      echo "Success.\n";
    }
  }
  
  public function __get($name){
    if(isset($this->values[$name])){
      return $this->values[$name];
    }
  }

  public function __set($name, $val){
    $this->values[$name] = $val;
  }

}
