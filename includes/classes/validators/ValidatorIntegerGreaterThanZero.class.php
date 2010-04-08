<?php
include_once(dirname(__FILE__) . '/ValidatorBase.class.php');
class ValidatorIntegerGreaterThanZero extends ValidatorBase {
	public function check($value) {
		$this->_error = null;

		if((int)$value > 0) {
			return true;
		}

		$this->_error = '%%name%% must be greater than zero';
		return false;
	}
}
