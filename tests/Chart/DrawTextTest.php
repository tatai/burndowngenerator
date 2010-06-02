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

class DrawTextTest extends PHPUnit_Framework_TestCase {
	private $_pdf = null;
	private $_text = null;
	private $_string = null;
	private $_size = null;
	
	public function setUp() {
		$this->_pdf = $this->getMock('MetricsPdf', array(), array('a4', 'landscape'));
		$this->_text = new DrawText();
		$this->_string = 'aaaa';
		$this->_size = 10;
	}

	/**
	 * @test
	 */
	public function usesPointCoordinatesWhenCreatingHorizontalText() {
		$position = $this->getMock('Point', array(), array(1, 2));
		
		$position->expects($this->once())
			->method('x');

		$position->expects($this->once())
			->method('y');
			
		$this->_text->horizontal($this->_pdf, $this->_string, $this->_size, $position);
	}
	
	/**
	 * @test
	 */
	public function usesPointCoordinatesWhenCreatingVerticalText() {
		$position = $this->getMock('Point', array(), array(1, 2));
		
		$position->expects($this->once())
			->method('x');

		$position->expects($this->once())
			->method('y');
			
		$this->_text->vertical($this->_pdf, $this->_string, $this->_size, $position);
	}
	
	/**
	 * @test
	 */
	public function calculatesWidthWithSize() {
		$position = new Point(10, 25);

		$this->_pdf->expects($this->once())
			->method('getTextWidth');
		
		$this->_text->vertical($this->_pdf, $this->_string, $this->_size, $position);
	}

	/**
	 * @test
	 */
	public function classCallsPdfTextMethod() {
		$position = new Point(10, 25);
		
		$this->_pdf->expects($this->once())
			->method('addTextWrap');

		$this->_text->vertical($this->_pdf, $this->_string, $this->_size, $position);
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');