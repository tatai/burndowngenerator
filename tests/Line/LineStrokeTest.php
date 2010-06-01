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

class LineStrokeTest extends PHPUnit_Framework_TestCase {
	private $_style = null;
	
	public function setUp() {
		$this->_style = $this->getMock('ILineStyle');
	}

	/**
	 * @test
	 */
	public function widthCannotBeNegative() {
		$width = -1;
		
		$stroke = new LineStroke($width, $this->_style);
		
		$this->assertFalse($stroke->isValid());
	}
	
	/**
	 * @test
	 */
	public function returnsStrokeWidth() {
		$width = 5;
		
		$stroke = new LineStroke($width, $this->_style);
		
		$this->assertEquals($width, $stroke->getWidth());
	}

	/**
	 * @test
	 */
	public function returnsCapShape() {
		$width = 2;
		
		$this->_style
			->expects($this->once())
			->method('getCap');
		
		$stroke = new LineStroke($width, $this->_style);
		$stroke->getCap();
	}

	/**
	 * @test
	 */
	public function returnsDashStyle() {
		$width = 2;
		
		$this->_style
			->expects($this->once())
			->method('getDash');
		
		$stroke = new LineStroke($width, $this->_style);
		$stroke->getDash();
	}

	/**
	 * @test
	 */
	public function whenComparingAStrokeWithItselfItReturnsTrue() {
		$stroke = new LineStroke(1, $this->_style);
		
		$this->assertTrue($stroke->isEqual($stroke));
	}

	/**
	 * @test
	 */
	public function whenChangingWidthComparisionReturnsFalse() {
		$stroke = new LineStroke(1, $this->_style);
		$widerStroke = new LineStroke(2, $this->_style);
		
		$this->assertFalse($stroke->isEqual($widerStroke));
	}

	/**
	 * @test
	 */
	public function whenChangingLineStyleComparisionReturnsFalse() {
		$stroke = new LineStroke(1, new LineStyleContinuous());
		$widerStroke = new LineStroke(1, new LineStyleDashed());
		
		$this->assertFalse($stroke->isEqual($widerStroke));
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');