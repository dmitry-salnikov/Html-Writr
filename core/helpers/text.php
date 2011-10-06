<?php
/*
	Class: Text
    Functions useful for working with text
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
    /*
	  Function: camelcase
	  
		Converts a string like test_string to TestString
		
	  Parameters:
	  
	  	string- the string you want camelcased
	  	
	  Returns:
	  
	  	the camelcased string
	  	
	  See Also:
	  
	  	<uncamelcase>
	*/
     public function camelcase($string) {
		$string = ucwords(str_replace(array('_', '-', '/'), ' ', $string));
		$string = str_replace(' ', '', $string);
		return $string;		
	}
	   /*
	  Function: uncamelcase
	  
		Converts a string like TestString to test_string 
		
	  Parameters:
	  
	  	string- the string you want uncamelcased
	  	
	  Returns:
	  
	  	the uncamelcased string
	  	
	  See Also:
	  
	  	<camelcase>
	  	
	*/
     public function uncamelcase($string) {
		$name = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $class_name));	
	}
}