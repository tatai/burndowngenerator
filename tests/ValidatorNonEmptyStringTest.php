<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once dirname(__FILE__) . '/../includes/classes/validators/ValidatorNonEmptyString.class.php';
require_once dirname(__FILE__) . '/ValidatorTestBase.php';

class ValidatorNonEmptyStringTest extends ValidatorTestBase {
	public function setup() {
		parent::setup();
		$this->_validator = new ValidatorNonEmptyString();
	}

	protected function setErrorValue() {
		$this->_errorValue = '';
	}

	protected function setOkValue() {
		$this->_okValue = 'text';
	}

	public function testWhenNullReturnsFalse() {
		$this->assertFalse($this->_validator->check(null));
	}

	public function testWhenEmptyReturnsFalse() {
		$this->assertFalse($this->_validator->check(''));
	}

	public function testWhenTextReturnsTrue() {
		$this->assertTrue($this->_validator->check('text'));
	}

	public function testWhenSpaceReturnsFalse() {
		$this->assertFalse($this->_validator->check(' '));
	}
}
