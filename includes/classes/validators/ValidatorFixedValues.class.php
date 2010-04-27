<?php
include_once(dirname(__FILE__) . '/ValidatorBase.class.php');
abstract class ValidatorFixedValues extends ValidatorBase {
	protected $_values = null;

	public function __construct() {
		parent::__construct();

		$this->_defineValues();

		return $this;
	}

	abstract protected function _defineValues();

	public function check($value) {
		$this->_error = null;

		if(isset($this->_values[$value])) {
			return true;
		}

		$this->_error = '%%name%% is not valid';
		return false;
	}
}
