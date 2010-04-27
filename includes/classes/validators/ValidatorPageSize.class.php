<?php
include_once(dirname(__FILE__) . '/ValidatorFixedValues.class.php');
class ValidatorPageSize extends ValidatorFixedValues {
	protected function _defineValues() {
		$this->_values = array(
			'a0' => true,
			'a1' => true,
			'a2' => true,
			'a3' => true,
			'a4' => true,
			'a5' => true,
			'a6' => true,
			'letter' => true,
			'legal' => true,
			'executive' => true,
			'folio' => true
		);
	}
}
