<?php defined('CMS_LOADED') or die("Access Denied.");
/*
	Class: Load
    Loads bundles of commonly used information and includes files. This should be used whenever possible because t always loads the right file.
    - file		load.php
	- version	1.0
	- date		9/29/2011
	- author	Jack Lightbody <jack.lightbody@gmail.com>
	- project	Html Writr
	- type		Helper
*/
	
class Load {
	 /*
	 	Function: helper
	 	
	 	loads a helper
	
		Parameters:
		
		name- the name of the helper you want to use without the php extension
		extension-loads a helper from an extension
	
	  	dir- the directory you want to delete
	*/
     public function helper($name,$extension=null){
     	if(file_exists('helpers/'.$name.'.php')){
     		require_once('helpers//'.$name.'.php');
     		return;
     	}elseif($extension&&file_exists('extensions/'.$extension.'/helpers/'.$name.'.php')){
     		require_once('extensions/'.$extension.'/helpers/'.$name.'.php');
     		return;
     	}elseif(file_exists('helpers/'.$name.'.php')){
     		require_once('helpers/'.$name.'.php');
     		return;
     	}
     }
}