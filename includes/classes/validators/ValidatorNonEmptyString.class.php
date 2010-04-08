<?php
include_once(dirname(__FILE__) . '/ValidatorBase.class.php');
class ValidatorNonEmptyString extends ValidatorBase {
	public function check($value) {
		$this->_error = null;

		$check = $this->_clean($value);
		if(strlen($check) < 1) {
			$this->_error = '%%name%% is empty';
			return false;
		}

		return true;
	}

	private function _clean($value) {
		$clean = trim($value);

		return $clean;
	}
}
