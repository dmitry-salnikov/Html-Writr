<?php
/*
	Class: Visitor
    Gives you information about your visitors
    - file		visitor.php
	- version	1.0
	- date		10/1//2011
	- author	Jack Lightbody <jack.lightbody@gmail.com>
	- project	Html Writr
	- type		Helper
*/
	
class Visitor {
    /*
	 Function: getIP
	Gets the visitors ip address
	
	Returns:
	
	  ip- the ip address
	*/
	public function getIP() {			
	    if ( array_key_exists ('HTTP_CLIENT_IP', $_SERVER ) && $_SERVER['HTTP_CLIENT_IP']){
	    	return $_SERVER['HTTP_CLIENT_IP'];
	    }
	    else if ( array_key_exists ('HTTP_X_FORWARDED_FOR', $_SERVER ) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
	    	return $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }
	    else{
	    	return $_SERVER['REMOTE_ADDR'];
	    }
	}
}