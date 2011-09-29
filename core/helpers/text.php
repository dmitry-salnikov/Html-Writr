<?php defined('CMS_LOADED') or die("Access Denied.");
/*
	Class: Text
    Functions useful for working with text
    About:
    - file		text.php
	- version	1.0
	- date		9/29/2011
	- author	Jack Lightbody <jack.lightbody@gmail.com>
	- project	Html Writr
	- type		Helper
*/
	
class Text {
	/*
	  Function: sanitize
		Sanitizes a string
	  Parameters:
	  	text- the string you want sanitized
	  	allowed- the allowed tags in the string
	  Returns:
	  	the cleaned string
	  	
	*/
     public function sanitize($text, $allowed="") {
     	if ($text == null) {
			return "";
		}
     	$text=strip_tags($text, $allowed);
     	return $text;
     }
}