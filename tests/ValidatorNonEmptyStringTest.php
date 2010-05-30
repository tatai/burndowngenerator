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
require_once(dirname(__FILE__) . '/test_startup.php');
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

require_once(dirname(__FILE__) . '/test_shutdown.php');