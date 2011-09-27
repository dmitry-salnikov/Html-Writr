<?php defined('CMS_LOADED') or die("Access Denied.");
/**
	@file		events.php
	@version	1.0
	@date		9/15/2011
	@author		Jack Lightbody <jack.lightbody@gmail.com>
	@project	Html Writr
	@type		helper
	@info		
		-- Allows registering and executing events
	@globals
		* $event- param string (name of the vent you're firing)
*/
	
class Events {
	public static $events = array();
	/**
	* Adds an event to the system
	*/
    public static function registerEvent($event,$function,$file,$type=0){
    	//add the event to the array
    	self::$events[$event][]['function'] = $function;
    	self::$events[$event][]['location'] = $file;
    	self::$events[$event][]['type'] = $file;
    }
    /** 
    * Does both methods, in the correct order
    */
    public function fire($event, $args=array(), $content=""){
    	$data=self::fireEventEdit($event, $content,$args);
    	self::fireEvent($event, $args);
    	return $data;	
    }
    /**
    * This function fires an event when you want to use the data but not modify it
    */
    public function fireEvent($event, $args = array()){
        if(isset(self::$events[$event])){
            foreach(self::$events[$event] as $ev){
            	if($ev['type']==0){
            		//only do the ones that don't return data
            		include $ev['location'];
            		$function=$ev['function'];
                	call_user_func_array($function, $args);
                }
            }
        }
    }
    /**
    * This function fires the event and recieves the modified information
    */
    public function fireEventEdit($event,$content, $args = array()){
    	$eventReturn=false;
    	$i=0;
        if(isset(self::$events[$event])){
            foreach(self::$events[$event] as $ev){
            	if($ev['type']==1){
            	//only do the ones that return data
            		include $ev['location'];
            		$function=$ev['function'];
            		//if theres a return on this we'll capture that data.
            		if($i==0){
                		$eventReturn=call_user_func_array($function,$content, $args);
                	}else{
                		$eventReturn=call_user_func_array($function, $eventReturn,$args);
                		//if we've run this before use the data from the last one, otherwise use the data given to us.
                	}
                	$i++
                }
            }
        }
        return $eventReturn;
    }
}