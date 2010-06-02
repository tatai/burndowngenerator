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

class BurndownTitleTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var MetricsPdf
	 */
	private $_pdf = null;
	
	/**
	 * 
	 * @var Text
	 */
	private $_text = null;
	
	/**
	 * 
	 * @var BurndownTitle
	 */
	private $_title = null;

	public function setUp() {
		$this->_pdf = $this->getMock('MetricsPdf', array(), array(
			'a4', 
			'landscape'));
		$this->_text = $this->getMock('Text');
		$margins = array();
		$this->_title = new BurndownTitle($this->_pdf, $this->_text, $margins);
	}

	/**
	 * @test
	 */
	public function drawGivenText() {
		$text = 'Foo text';
		$this->_text->expects($this->once())
			->method('horizontal')
			->with($this->_pdf, $text, $this->anything(), $this->anything(), $this->anything());

		$this->_title->draw($text);
	}

	/**
	 * @test
	 */
	public function whenTitleIsNotGivenDefaultTextIsDrawn() {
		$title = 'Burndown online generator';
		$this->_text->expects($this->once())
			->method('horizontal')
			->with($this->_pdf, $title, $this->anything(), $this->anything(), $this->anything());

		$this->_title->draw();
	}
	
	/**
	 * @test
	 */
	public function whenTitleTextIsEmptyStringDefaultTextIsDrawn() {
		$title = 'Burndown online generator';
		$this->_text->expects($this->once())
			->method('horizontal')
			->with($this->_pdf, $title, $this->anything(), $this->anything(), $this->anything());

		$this->_title->draw('');
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');