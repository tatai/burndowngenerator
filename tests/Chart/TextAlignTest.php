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

class TextAlignTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var $_pdf MetricsPdf
	 */
	private $_pdf = null;
	
	/**
	 * 
	 * @var Text
	 */
	private $_text = null;
	
	/**
	 * 
	 * @var string
	 */
	private $_string = null;
	
	/**
	 * 
	 * @var float
	 */
	private $_size = null;
	
	/**
	 * 
	 * @var Point
	 */
	private $_position = null;
	
	/**
	 * 
	 * @var float
	 */
	private $_virtualWidth = null;
	
	public function setUp() {
		$this->_pdf = $this->getMock('MetricsPdf', array(), array('a4', 'landscape'));
		$this->_text = new Text();
		$this->_string = 'aaaa';
		$this->_size = 10;
		$this->_position = new Point(10, 25);

		$this->_virtualWidth = 3;

		$this->_pdf->expects($this->once())
			->method('getTextWidth')
			->will($this->returnValue($this->_virtualWidth));
	}
	
	private function _mockPdfToXPosition($xPos) {
		$this->_pdf->expects($this->once())
			->method('addTextWrap')
			->with($xPos, $this->_position->y(), $this->_virtualWidth, $this->_size, $this->_string);
	}

	/**
	 * @test
	 */
	public function alignsHorizontalTextCentered() {
		$checkXPosition = $this->_position->x() - ($this->_virtualWidth / 2);
		$this->_mockPdfToXPosition($checkXPosition);

		$this->_text->horizontal($this->_pdf, $this->_string, $this->_size, $this->_position, 'center');
	}

	/**
	 * @test
	 */
	public function alignsHorizontalTextLefted() {
		$this->_mockPdfToXPosition($this->_position->x());
		
		$this->_text->horizontal($this->_pdf, $this->_string, $this->_size, $this->_position, 'left');
	}

	/**
	 * @test
	 */
	public function alignsHorizontalTextRighted() {
		$checkXPosition = $this->_position->x() - $this->_virtualWidth;
		$this->_mockPdfToXPosition($checkXPosition);
		
		$this->_text->horizontal($this->_pdf, $this->_string, $this->_size, $this->_position, 'right');
	}

	private function _mockPdfToYPosition($yPos) {
		$this->_pdf->expects($this->once())
			->method('addTextWrap')
			->with($this->_position->x(), $yPos, $this->_virtualWidth, $this->_size, $this->_string, 'left', 270);
	}

	/**
	 * @test
	 */
	public function alignsVerticalTextCentered() {
		$checkYPosition = $this->_position->y() - ($this->_virtualWidth / 2);
		$this->_mockPdfToYPosition($checkYPosition);

		$this->_text->vertical($this->_pdf, $this->_string, $this->_size, $this->_position, 'center');
	}

	/**
	 * @test
	 */
	public function alignsVerticalTextLefted() {
		$this->_mockPdfToYPosition($this->_position->y());
		
		$this->_text->vertical($this->_pdf, $this->_string, $this->_size, $this->_position, 'left');
	}

	/**
	 * @test
	 */
	public function alignsVerticalTextRighted() {
		$checkYPosition = $this->_position->y() - $this->_virtualWidth;
		$this->_mockPdfToYPosition($checkYPosition);
		
		$this->_text->vertical($this->_pdf, $this->_string, $this->_size, $this->_position, 'right');
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');