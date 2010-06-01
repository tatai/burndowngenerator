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

class PointTest extends PHPUnit_Framework_TestCase {
	
	private $_point = null;
	private $_x = null;
	private $_y = null;

	public function setUp() {
		$this->_x = 2;
		$this->_y = 3;
		$this->_point = new Point($this->_x, $this->_y);
	
	}

	/**
	 * @test
	 */
	public function xValueCanBeReturned() {
		$this->assertEquals($this->_x, $this->_point->x());
	}

	/**
	 * @test
	 */
	public function yValueCanBeReturned() {
		$this->assertEquals($this->_y, $this->_point->y());
	}

	/**
	 * @test
	 */
	public function comparingPointWithHimselfReturnsTrue() {
		$this->assertTrue($this->_point->isEqual($this->_point));
	}

	/**
	 * @test
	 */
	public function whenComparingWithPointWithDifferentXValueReturnsFalse() {
		$point = new Point(1, 3);
		$this->assertFalse($this->_point->isEqual($point));
	}

	/**
	 * @test
	 */
	public function whenComparingWithPointWithDifferentYValueReturnsFalse() {
		$point = new Point(2, 5);
		$this->assertFalse($this->_point->isEqual($point));
	}
}

require_once(dirname(__FILE__) . '/../test_shutdown.php');