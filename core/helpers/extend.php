<?php
/*
	Class: Extend
    Functions for working with extensions
    About:
    - file		extend.php
	- version	1.0
	- date		10/3/2011
	- author	Jack Lightbody <jack.lightbody@gmail.com>
	- project	Html Writr
	- type		Helper
*/
	
class Extend {
	public static $installed = array();
	/*
	 Function: Install
	
	Installs an extensions
	
	Parameters:
	
	   extension - The name of the extension that you are installing
	
	*/
    public static function install$extension){
    	//add the event to the array
    	self::$installed[$extension] = 'installed';
    	include 'text.php';
    	$class=Text::camelcase($extension);
    	$class::install();
    }
    /*
	 Function: Uninstall
	
	Uninstalls an extensions
	
	Parameters:
	
	   extension - The name of the extension that you are uninstalling
	
	*/
    public static function uninstall$extension){
    	//add the event to the array
    	unset(self::$installed[$extension]);
    	include 'text.php';
    	$class=Text::camelcase($extension);
    	$class::uninstall();
    	//its your job to remove all your hooks
    }
    /*
	 Function: getInstalled
	
	gets the list of installed extensions
	
	Returns:
		installed- an array of extension folder names
	
	*/
    public static function getInstalled(){
    	$installed=array();
    	foreach(self::$installed as $key=>$install){
    		array_push($installed, $key);
    		//add the extensions
    	}
    	return $installed;
    }
   	/*
	 Function: getInfo
	
	Ges the Extensions info
	
	Parameters:
	
	   extension - The name of the extension that you are installing
	Returns:
		info- an array of the information
	
	*/
    public static function getInfo($extension){
    	include 'text.php';
    	include '../../extend/'.$extension;
		$class=Text::camelcase($extension);
		$info=$class::getInfo();
		return $info;
    }
}