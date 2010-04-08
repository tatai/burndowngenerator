<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once dirname(__FILE__) . '/../includes/classes/validators/ValidatorNonEmptyString.class.php';

abstract class ValidatorTestBase extends PHPUnit_Framework_TestCase {
	protected 
		$_validator = null,
		$_errorValue = null,
		$_okValue = null
		;
	
	abstract protected function setErrorValue();
	abstract protected function setOkValue();

	public function setup() {
		$this->setErrorValue();
		$this->setOkValue();
	}

	public function testIfErrorMessageAvailable() {
		$this->_validator->check($this->_errorValue);
		$this->assertTrue(strlen((string)$this->_validator->getError('name')) > 0);
	}

	public function testIfOkNoMessage() {
		$this->_validator->check($this->_okValue);
		$this->assertTrue($this->_validator->getError('name') == null);
	}
}
