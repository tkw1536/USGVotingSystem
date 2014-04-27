<?php
	function read_cfgFile($file){
		//read a cfg file and ignore lines with #s at the start

		$path = dirname(__FILE__) . "/../config/" . $file; 

		$lines = array();

		if(is_file($path)){
			foreach(file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line){
			   if(substr($line, 0, 1) === '#'){} else {
			   		if(!empty($line)){
			   			array_push($lines, $line);
			   		}
			   }
			}
		}

		return $lines; 
	}

	function cfg_contains($file, $line){
		//check if a line is contained in a cfg file as above
		return in_array($line, read_cfgFile($file)); 
	}

	function make_directory($dir){
		if(!is_dir($dir)){
			mkdir($dir, 0777, true);
		}
	}

	function list_files($dir){
		//list files in a directory, create it if it doesnt exist
		make_directory($dir); 

		$files = array(); 
		foreach(scandir($dir) as $file)
		{
		    if(is_file($dir.$file)){
				array_push($files, $file); 
			}
		}

		return $files; 
	}

	function touchdir($dir){
		foreach(list_files($dir) as $file){
			touch($dir . $file); 
		}
	}
?>