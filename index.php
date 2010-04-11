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

$errors = array();
$data = array();

// First lets check out if data to draw burndown is available
if($action == 'burndown') {
	include(dirname(__FILE__) . '/includes/classes/EntryData.class.php');
	$entry = new EntryData();
	$errors = $entry->check();

	if(count($errors)) {
		$action = 'main_with_input';
		$data = array_merge(
			array('points' => $entry->getPoints()),
			array('days' => $entry->getDays()),
			$entry->getOptions()
		);
	}
	else {
		$action = 'render_burndown';
	}
}

if($action == 'main' || $action == 'main_with_input') {
	$main = new MainPage();

	if($action == 'main_with_input') {
		$main->setErrors($errors);
		$main->setData($data);
	}

	$main->render();
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
else if($action == 'render_burndown') {
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', false);
	header('Pragma: no-cache');

	include(dirname(__FILE__) . '/includes/classes/MetricsPdf.class.php');
	include(dirname(__FILE__) . '/includes/classes/Burndown.class.php');

	$pdf = new MetricsPdf('a4', 'landscape');

	$burndown = new Burndown($pdf, $entry->getPoints(), $entry->getDays());
	$burndown->setOptions($entry->getOptions());
	$burndown->output();
}
?>
