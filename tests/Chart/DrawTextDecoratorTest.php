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

class DrawTextDecoratorTest extends PHPUnit_Framework_TestCase {
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
	 * @var string
	 */
	private $_align = null;
	
	/**
	 * 
	 * @var ITextDecorator
	 */
	private $_decorator = null;
	
	public function setUp() {
		$this->_pdf = $this->getMock('MetricsPdf', array(), array('a4', 'landscape'));
		$this->_text = new DrawText($this->_pdf);
		$this->_string = 'aaaa';
		$this->_size = 10;
		$this->_position = new Point(10, 25);
		$this->_align = 'left';
		$this->_decorator = $this->getMock('ITextDecorator');
	}
	
	/**
	 * @test
	 */
	public function whenDecoratorPassedToHorizontalTextThenDrawMethodIsCalledInDecorator() {
		$this->_decorator->expects($this->once())
			->method('draw');

		$this->_text->horizontal($this->_string, $this->_size, $this->_position, $this->_align, $this->_decorator);
	}
	
	/**
	 * @test
	 */
	public function whenDecoratorPassedToVerticalTextThenDrawMethodIsCalledInDecorator() {
		$this->_decorator->expects($this->once())
			->method('draw');

		$this->_text->vertical($this->_string, $this->_size, $this->_position, $this->_align, $this->_decorator);
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');	