<?php
require_once('simpletest/autorun.php');
require_once('../core/helpers/events.php');

class TestOfEvents extends UnitTestCase {
    function testInstallEvents() {
        Events::registerEvent('test_event','test_file','test_function','simpletests');
        $events=Events::getEvents();
        $this->assertTrue(is_array($events['test_event']['simpletests']));
    }
    function testUninstallEvents(){
    	Events::uninstallExtEvent('test_event','simpletests');
        $events=Events::getEvents();
        $this->assertFalse(isset($events['test_event']['simpletests']));
    }
}
?>