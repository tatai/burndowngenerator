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

class BurndownSpeedTest extends PHPUnit_Framework_TestCase {
	/**
	 * 
	 * @var BurndownSpeed
	 */
	private $_speed = null;
	
	/**
	 * @var IPdf
	 */
	private $_pdf = null;
	
	/**
	 * 
	 * @var LineStyleChanger
	 */
	private $_lineStyleChanger = null;
	
	/**
	 * 
	 * @var DrawText
	 */
	private $_text = null;
	
	/**
	 * @var array
	 */
	private $_margins = null;
	
	/**
	 * 
	 * @var int
	 */
	private $_points = null;

	public function setUp() {
		$this->_pdf = $this->getMock('IPdf');
		$this->_lineStyleChanger = $this->getMock('LineStyleChanger');
		$this->_text = $this->getMock('DrawText', array(), array($this->_pdf));
		$this->_speed = new BurndownSpeed($this->_pdf, $this->_lineStyleChanger, $this->_text);
		
		$this->_margins = new BurndownMargins(0, 0, 0, 0);
		$this->_points = 100;
	}

	/**
	 * @test
	 */
	public function lineStyleIsSet() {
		$this->_lineStyleChanger->expects($this->once())->method('change');
		
		$this->_speed->draw($this->_points, $this->_margins);
	}

	/**
	 * @test
	 */
	public function drawsSpeedText() {
		$this->_text->expects($this->once())->method('horizontal');
		
		$this->_speed->draw($this->_points, $this->_margins);
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');