<?php
include_once(dirname(__FILE__) . '/ValidatorFixedValues.class.php');
class ValidatorChartType extends ValidatorFixedValues {
	protected function _defineValues() {
		$this->_values = array(
			'burndown' => true,
			'burnup' => true
		);
	}
}
