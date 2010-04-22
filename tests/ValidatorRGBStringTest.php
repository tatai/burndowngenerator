<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once dirname(__FILE__) . '/../includes/classes/validators/ValidatorRGBString.class.php';
require_once dirname(__FILE__) . '/ValidatorTestBase.php';

class ValidatorRGBStringTest extends ValidatorTestBase {
	public function setup() {
		parent::setup();
		$this->_validator = new ValidatorRGBString();
	}

	protected function setErrorValue() {
		$this->_errorValue = '';
	}

	protected function setOkValue() {
		$this->_okValue = '#000000';
	}

	public function testErrorWhenNoHashAtStarts() {
		$this->assertFalse($this->_validator->check('000000'));
	}

	public function testErrorWhenShortRGBFormat() {
		$this->assertFalse($this->_validator->check('#000'));
	}

	public function testDoesNotAcceptInvalidCharsInAnyPosition() {
		$this->assertFalse($this->_validator->check('#h00000'));
		$this->assertFalse($this->_validator->check('#0h0000'));
		$this->assertFalse($this->_validator->check('#00h000'));
		$this->assertFalse($this->_validator->check('#000h00'));
		$this->assertFalse($this->_validator->check('#0000h0'));
		$this->assertFalse($this->_validator->check('#00000h'));
	}

	public function testErrorWhenLargerString() {
		$this->assertFalse($this->_validator->check('#0000000'));
	}
}
