<?php
include_once(dirname(__FILE__) . '/includes/classes/MainPage.class.php');

$action = 'main';
if(isset($_GET['action'])) {
	switch($_GET['action']) {
		case 'comment':
		case 'burndown':
			$action = $_GET['action'];
			break;
	}
}

$main = new MainPage();

if($action == 'main') {
}
else if($action == 'comment') {
	include_once(dirname(__FILE__) . '/includes/classes/Comments.class.php');
	$comments = new Comments();
	if($comments->check()) {
		$comments->send();
	}

	print $comments->response();

	exit();
}
else if($action == 'burndown') {
}

$main->render();
?>
