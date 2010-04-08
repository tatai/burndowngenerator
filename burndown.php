<?php
include(dirname(__FILE__) . '/includes/classes/MetricsPdf.class.php');
include(dirname(__FILE__) . '/includes/classes/Burndown.class.php');

header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' ); 

$days = 7;
$points = 127;

$pdf = new MetricsPdf('a4', 'landscape');

$burndown = new Burndown($pdf, $points, $days);
$burndown->output();
?>
