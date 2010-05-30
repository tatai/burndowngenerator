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
require_once(dirname(__FILE__) . '/../test_startup.php');

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

require_once(dirname(__FILE__) . '/../test_shutdown.php');