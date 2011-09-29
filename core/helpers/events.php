<?php
/*
	Class: Events
    Allows registering and executing events for use by third party extensions
    About:
    - file		events.php
	- version	1.0
	- date		9/29/2011
	- author	Jack Lightbody <jack.lightbody@gmail.com>
	- project	Html Writr
	- type		Helper
*/
	
class Events {
	public static $events = array();
	/*
	 Function: registerEvent
	
	Adds an event to the system
	
	Parameters:
	
	   event - The name of the event you want to extend
	   file - file that you want to load on calling the event
	   function - the function that you want to call in the file
	   type - the type of the function that you want to call. 0 if you just want to get the data, 1 if you want to edit it.
	
	See Also:
	
	   <fire>
	*/
    public static function registerEvent($event,$file,$function,$type=0){
    	//add the event to the array
    	self::$events[$event][]['function'] = $function;
    	self::$events[$event][]['location'] = $file;
    	self::$events[$event][]['type'] = $file;
    }
    /** 
    * Does both methods, in the correct order
    */
   	/*
	 Function: fire
	
	Adds an event to the system. Pass any content that you want modified as the first value in args
	
	Parameters:
	
	   event - The name of the event you want to extend
	   args - an array of the data you want to pass on to extensions
	
	See Also:
	   <fireEvent>
	   <fireEventEdit>
	*/
    public function fire($event, $args=array()){
    	if($content!=""){
    		$data=self::fireEventEdit($event,$args);
    	}
    	self::fireEvent($event, $args);
    	return $data;	
    }
   	/*
	 Function: fireEvent
	
	fires an event when you want events to use the data but not modify it
	
	Parameters:
	
	   event - The name of the event you want to extend
	   args - an array of the data you want to pass on to extensions
	
	See Also:
	   <fire>
	   <fireEventEdit>
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
    /*
	 Function: fireEventEdit
	
	Fires the event and recieves the modified information. Put the content you want modified as the first value in args
	
	Parameters:
	
	   event - The name of the event you want to extend
	   args - an array of the data you want to pass on to extensions
	
	See Also:
	   <fire>
	   <fireEventEdit>
	*/
    public function fireEventEdit($event, $args = array()){
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
                	$i++;
                }
            }
        }
        if($eventReturn&&$eventReturn!=""){
        	return $eventReturn;
        }else{
        	return $content;
        }
        // make sure theres some sutff we're returning
    }
}