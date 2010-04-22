<?php
include_once(dirname(__FILE__) . '/config.php');

include_once(dirname(__FILE__) . '/includes/classes/Dispatcher.class.php');
$d = new Dispatcher();
$redirect = $d->dispatch();
if(!is_null($redirect)) {
	include_once(dirname(__FILE__) . '/includes/classes/pages/' . $redirect['program'] . '.class.php');
	$page = new $redirect['program']($redirect['params']);
	$page->execute();
}
else {
	header('HTTP/1.0 404 Not Found');
}
?>
