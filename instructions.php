<?php
include_once(dirname(__FILE__) . '/includes/classes/XTemplate.class.php');
$xtpl = new XTemplate('instructions.xtpl', dirname(__FILE__) . '/xtpl');

$xtpl->parse('main');
$xtpl->out('main');
?>
