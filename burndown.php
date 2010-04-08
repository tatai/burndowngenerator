<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

include(dirname(__FILE__) . '/includes/classes/MetricsPdf.class.php');
include(dirname(__FILE__) . '/includes/classes/Burndown.class.php');
include(dirname(__FILE__) . '/includes/classes/EntryData.class.php');

$entry = new EntryData();
$errors = $entry->check();

// Load XTemplate

if(count($errors)) {
	print 'Errors found';
	print '<pre>';
	print_r($errors);
	print '</pre>';
	exit();
}

$pdf = new MetricsPdf('a4', 'landscape');

$burndown = new Burndown($pdf, $entry->getPoints(), $entry->getDays());
$burndown->setOptions($entry->getOptions());
$burndown->output();
?>
