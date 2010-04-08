<?php
abstract class ValidatorBase {
	protected $_error = null;

	public function __construct() {
		return $this;
	}

	abstract public function check($value);

	public function getError($name) {
		if($this->_error) {
			return preg_replace('/%%name%%/', $name, $this->_error);
		}

		return null;
	}
}
