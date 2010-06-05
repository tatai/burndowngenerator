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

class VectorTest extends PHPUnit_Framework_TestCase {
	/**
	 * 
	 * @var Vector
	 */
	private $_vector = null;
	
	private $_slope = null;
	private $_independent = null;

	public function setUp() {
		$this->_slope = 4;
		$this->_independent = 2;
		
		$this->_vector = new Vector($this->_slope, $this->_independent);
	}

	/**
	 * @test
	 */
	public function slopeCanBeReturned() {
		$this->assertEquals($this->_slope, $this->_vector->slope());
	}

	/**
	 * @test
	 */
	public function independentTermCanBeReturned() {
		$this->assertEquals($this->_independent, $this->_vector->independent());
	}

	/**
	 * @test
	 */
	public function yValueIsCalculedFromX() {
		$this->assertEquals(10, $this->_vector->calculateY(2));
	}

	/**
	 * @test
	 */
	public function xValueIsCalculedFromY() {
		$this->assertEquals(0.25, $this->_vector->calculateX(3));
	}

	/**
	 * @test
	 */
	public function vectorCompareWithItselfIsEqual() {
		$this->assertTrue($this->_vector->isEqual($this->_vector));
	}

	/**
	 * @test
	 */
	public function differentSlopeMeansDifferentVector() {
		$vector = new Vector($this->_slope + 1, $this->_independent);
		$this->assertFalse($this->_vector->isEqual($vector));
	}
	
	/**
	 * @test
	 */
	public function differentIndependentMeansDifferentVector() {
		$vector = new Vector($this->_slope, $this->_independent + 1);
		$this->assertFalse($this->_vector->isEqual($vector));
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');