<?php
include_once(dirname(__FILE__) . '/ValidatorBase.class.php');
class ValidatorRGBString extends ValidatorBase {
	public function check($value) {
		$this->_error = null;

		$check = $this->_clean($value);
		if(!preg_match('/#[0-9a-f]{6}$/', $check)) {
			$this->_error = '%%name%% must be in #rrggbb hex format';
			return false;
		}

		return true;
	}

	private function _clean($value) {
		$clean = trim($value);

		return $clean;
	}
}
