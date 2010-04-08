<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once dirname(__FILE__) . '/../includes/classes/validators/ValidatorIntegerGreaterThanZero.class.php';
require_once dirname(__FILE__) . '/ValidatorTestBase.php';

class ValidatorIntegerGreaterThanZeroTest extends ValidatorTestBase {
	public function setup() {
		parent::setup();
		$this->_validator = new ValidatorIntegerGreaterThanZero();
	}

	protected function setErrorValue() {
		$this->_errorValue = 0;
	}

	protected function setOkValue() {
		$this->_okValue = 1;
	}

	public function testWhenNullReturnsFalse() {
		$this->assertFalse($this->_validator->check(null));
	}

	public function testWhenEmptyReturnsFalse() {
		$this->assertFalse($this->_validator->check(''));
	}

	public function testWhenZeroOrNegativeReturnsFalse() {
		$this->assertFalse($this->_validator->check(0));
		$this->assertFalse($this->_validator->check(-1));
	}

	public function testWhenPositiveReturnsTrue() {
		$this->assertTrue($this->_validator->check(1));
	}

	public function testWhenSpaceReturnsFalse() {
		$this->assertFalse($this->_validator->check(' '));
	}
}
