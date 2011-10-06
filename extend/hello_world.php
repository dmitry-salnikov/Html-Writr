<?php
Class HelloWorld {
	public function getInfo(){
		return array("name"=>"Hello World","description"=>"simple example extension");
	}
	public function install(){
		include_once 'core/helpers/events.php';
		Events::registerEvent('page_edit','test','test','hello_world',0);
	}
	public function uninstall(){
		include_once 'core/helpers/events.php';
		Events::uninstallExtEvent('page_edit','hello_world');
	}
}
