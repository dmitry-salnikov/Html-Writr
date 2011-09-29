<?php
/*
	Class: fileHelper
    Functions useful for working with files
    About:
    - file		file.php
	- version	1.0
	- date		9/29/2011
	- author	Jack Lightbody <jack.lightbody@gmail.com>
	- project	Html Writr
	- type		Helper
*/
	
class fileHelper {
    /*
	 Function: rrmdir
	Deletes its directory and everything in it from http://www.php.net/manual/en/function.rmdir.php#98622
	Parameters:
	  dir- the directory you want to delete
	*/
	public function rrmdir($dir) { 
 		if (is_dir($dir)) { 
 			$objects = scandir($dir); 
 			foreach ($objects as $object) { 
 			  	if ($object != "." && $object != "..") { 
 			    	if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object); 
 				} 
 			} 
 			reset($objects); 
 			rmdir($dir); 
 		} 
 	} 	
}