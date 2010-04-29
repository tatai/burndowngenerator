<?php
/* 
 * Yet another burndown online generator, http://www.burndowngenerator.com
 * Copyright (C) 2010 Francisco José Naranjo <fran.naranjo@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
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
