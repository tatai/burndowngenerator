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

class BurndownLineTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var MetricsPdf
	 */
	private $_pdf = null;
	
	/**
	 * 
	 * @var BurndownLine
	 */
	private $_line = null;
	
	/**
	 * 
	 * @var LineStyleChanger
	 */
	private $_styleChanger = null;
	
	/**
	 * 
	 * @var IBurndownLineType
	 */
	private $_burndownLineType = null;

	public function setUp() {
		$this->_pdf = $this->getMock('MetricsPdf', array(), array(
			'a4', 
			'landscape'));
		$this->_styleChanger = $this->getMock('LineStyleChanger');
		$margins = array();
		$this->_line = new BurndownLine($this->_pdf, $this->_styleChanger, $margins);
		$this->_burndownLineType = $this->getMock('IBurndownLineType', array(), array(new Point(1, 2), new Point(3, 4)));
	}
	
	/**
	 * @test
	 */
	public function changesLineStyleToGivenColor() {
		$color = new Color(new Decimal(42), new Decimal(92), new Decimal(78));
		
		$this->_styleChanger->expects($this->once())->method('change');
		
		$this->_line->draw($color, $this->_burndownLineType);
	}
		
	/**
	 * @test
	 */
	public function drawMethodOfCollaboratorObjectIsCalled() {
		$color = new Color(new Decimal(42), new Decimal(92), new Decimal(78));
	
		$this->_burndownLineType->expects($this->once())->method('draw');
		
		$this->_line->draw($color, $this->_burndownLineType);
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');