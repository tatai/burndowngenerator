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
require_once (dirname(__FILE__) . '/../test_startup.php');

class BurndownMarginsWithInvalidDataTest extends PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function whenLeftIsNotNumericItReturnsZeroAsValue() {
		$margins = new BurndownMargins('a', rand(1, 100), rand(1, 100), rand(1, 100));
		
		$this->assertEquals(0, $margins->left());
	}

	/**
	 * @test
	 */
	public function whenTopIsNotNumericItReturnsZeroAsValue() {
		$margins = new BurndownMargins(rand(1, 100), 'a', rand(1, 100), rand(1, 100));
		
		$this->assertEquals(0, $margins->top());
	}

	/**
	 * @test
	 */
	public function whenRightIsNotNumericItReturnsZeroAsValue() {
		$margins = new BurndownMargins(rand(1, 100), rand(1, 100), 'a', rand(1, 100));
		
		$this->assertEquals(0, $margins->right());
	}

	/**
	 * @test
	 */
	public function whenBottomIsNotNumericItReturnsZeroAsValue() {
		$margins = new BurndownMargins(rand(1, 100), rand(1, 100), rand(1, 100), 'a');
		
		$this->assertEquals(0, $margins->bottom());
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');