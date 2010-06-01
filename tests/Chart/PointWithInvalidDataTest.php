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

class PointWithInvalidDataTest extends PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function whenXIsNonNumericReturnsNotValid() {
		$x = 'a';
		$y = 3;
		$point = new Point($x, $y);
		
		$this->assertFalse($point->isValid());
	}

	/**
	 * @test
	 */
	public function whenXIsNonNumericValueIsNull() {
		$x = 'a';
		$y = 3;
		$point = new Point($x, $y);
		
		$this->assertNull($point->x());
	}

	/**
	 * @test
	 */
	public function whenYIsNonNumericReturnsNotValid() {
		$x = 2;
		$y = 'a';
		$point = new Point($x, $y);
		
		$this->assertFalse($point->isValid());
	}

	/**
	 * @test
	 */
	public function whenYIsNonNumericValueIsNull() {
		$x = 2;
		$y = 'a';
		$point = new Point($x, $y);
		
		$this->assertNull($point->y());
	}

	/**
	 * @test
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function constructFailsWhenReceivingNoArgument() {
		$point = new Point();
	}

	/**
	 * @test
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function constructFailsWhenReceivingOneArgument() {
		$point = new Point(3);
	}
}

require_once(dirname(__FILE__) . '/../test_shutdown.php');