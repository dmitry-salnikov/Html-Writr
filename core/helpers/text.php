<?php defined('CMS_LOADED') or die("Access Denied.");
/**
	@file		text.php
	@version	1.0
	@date		9/15/2011
	@author		Jack Lightbody <jack.lightbody@gmail.com>
	@project	Html Writr
	@type		helper
	@info		
		-- Functions to deal with text
*/
	
class Text {
     public function sanitize($text, $allowed="") {
     	if ($text == null) {
			return "";
		}
     	$text=strip_tags($text, $allowed);
     	return $text;
     }
}