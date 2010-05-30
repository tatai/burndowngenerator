<?php
include_once (dirname(__FILE__) . '/config.php');

include_once(dirname(__FILE__) . '/includes/classes/ClassLoader.class.php');
$GLOBALS['ClassLoader'] = new ClassLoader();
$GLOBALS['ClassLoader']->addPath(dirname(__FILE__) . '/includes/classes');

function __autoload($classname) {
	$GLOBALS['ClassLoader']->includeClass($classname . '.class.php');
}
?>