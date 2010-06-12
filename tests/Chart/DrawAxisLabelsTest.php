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

class DrawAxisLabelsTest extends PHPUnit_Framework_TestCase {
	/**
	 * 
	 * @var DrawText
	 */
	private $_text = null;
	
	/**
	 * 
	 * @var DrawAxisLabels
	 */
	private $_labels = null;
	
	/**
	 * 
	 * @var Point
	 */
	private $_point = null;
	
	private $_start = null;
	private $_increment = null;

	public function setUp() {
		$pdf = $this->getMock('MetricsPdf', array(), array(
			'a4', 
			'landscape'));
		$this->_text = $this->getMock('DrawText', array(), array($pdf));
		$this->_start = 3;
		$this->_increment = 2;

		$this->_point = $this->getMock('Point', array(), array(
			1, 
			2));
		
		$this->_labels = new DrawAxisLabels($this->_text, $this->_start, $this->_increment);
	}

	/**
	 * @test
	 */
	public function eachTimeNextMethodIsCalledTextIsDrawn() {
		$calls = rand(1, 10);
		
		$this->_text->expects($this->exactly($calls))->method('horizontal');
		for($i = 0; $i < $calls; $i++) {
			$this->_labels->next($this->_point);
		}
	}

	/**
	 * @test
	 */
	public function startValueAndIncrementIsUsedAsText() {
		$calls = 4;
		
		for($i = 0; $i < $calls; $i++) {
			$this->_labels->next($this->_point);
			$this->assertEquals($this->_start + $this->_increment * $i, $this->_labels->last());
		}
	}

	/**
	 * @test
	 */
	public function textIsDrawnInGivenPoint() {
		$calls = rand(1, 10);
		
		$this->_text->expects($this->exactly($calls))->method('horizontal')->with($this->anything(), $this->anything(), $this->_point);
		for($i = 0; $i < $calls; $i++) {
			$this->_labels->next($this->_point);
		}
	}
	
	/**
	 * @test
	 */
	public function textCorrectlyAligned() {
		$calls = rand(1, 10);
		
		$align = 'right';
		
		$this->_text->expects($this->exactly($calls))->method('horizontal')->with($this->anything(), $this->anything(), $this->anything(), $align);
		for($i = 0; $i < $calls; $i++) {
			$this->_labels->next($this->_point, $align);
		}
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');