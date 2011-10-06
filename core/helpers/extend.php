<?php
/*
	Class: Extend
    Functions for working with extensions
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
	 Function: getInstalled
	
	gets all the installed extensions
	
	Returns:
	
		installed- an array of all installed extensions	
		
	See Also:
	
	   <Install>
	   <Uninstall>
	*/
	public function getEvents(){
		return self::$installed;
	}
	/*
	 Function: Install
	
	Installs an extensions
	
	Parameters:
	
	   extension - The name of the extension that you are installing
	   
	See Also:
	
	   <Uninstall>
	   <getInfo>
	
	*/
    public static function install($extension){
    	//add the event to the array
    	self::$installed[$extension] = 'installed';
    	include_once 'text.php';
    	include_once 'extend/'.$extension;
    	$extension=str_replace('.php', '', $extension);
    	$class=Text::camelcase($extension);
    	$ext=new $class();
    	$ext->install();
    }
    /*
	 Function: Uninstall
	
	Uninstalls an extensions
	
	Parameters:
	
	   extension - The name of the extension that you are uninstalling
	   
	See Also:
	
	   <Install>
	   <getInstalled>
	   <getInfo>
	
	*/
    public static function uninstall($extension){
    	//add the event to the array
    	unset(self::$installed[$extension]);
    	include_once 'text.php';
    	include_once 'extend/'.$extension;
    	$extension=str_replace('.php', '', $extension);
    	$class=Text::camelcase($extension);
    	$ext=new $class();
    	$ext->uninstall();
    	//its your job to remove all your hooks
    }
    /*
	 Function: getInstalled
	
	gets the list of installed extensions
	
	Returns:
		installed- an array of extension folder names
		
	See Also:
	
	   <Install>
	   <Uninstall>
	   <getInfo>
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
	
	   file - The folder name of the extension that you are installing
	Returns:
		info- an array of the information
		
	See Also:
	
	   <Install>
	   <Uninstall>
	
	*/
    public static function getInfo($file){
    	include_once 'text.php';
    	include_once 'extend/'.$file;
    	$extension=str_replace('.php', '', $file);
		$class=Text::camelcase($extension);
		$ext=new $class();
    	$info=$ext->getInfo();
		return $info;
    }
}