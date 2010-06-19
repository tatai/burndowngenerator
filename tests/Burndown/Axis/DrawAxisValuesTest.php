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
require_once (dirname(__FILE__) . '/../../test_startup.php');

class DrawAxisValuesTest extends PHPUnit_Framework_TestCase {
	/**
	 * 
	 * @var DrawText
	 */
	private $_draw_text = null;

	/**
	 * 
	 * @var DrawAxisValues
	 */
	private $_draw_axis_values = null;
	
	/**
	 * 
	 * @var IAxisElements
	 */
	private $_axisElements = null;
	
	/**
	 * 
	 * @var AxisSplitter
	 */
	private $_splitter = null;

	public function setUp() {
		$pdf = $this->getMock('IPdf');
		$styleChanger = new LineStyleChanger();
		
		$this->_draw_line = $this->getMock('DrawText', array(), array($pdf));
		
		$this->_draw_axis_values = new DrawAxisValues($this->_draw_line);
		
		$this->_axisElements = $this->getMock('IAxisElements');
		$this->_splitter = $this->getMock('AxisSplitter', array(), array(1, new Line(new Point(2, 4), new Point(8.5, 4))));
	}

	/**
	 * @test
	 */
	public function createAsManyLabelsAsTicks() {
		$ticks = 4;

		$this->_splitter->expects($this->any())->method('splits')->will($this->returnValue($ticks));
		$this->_splitter->expects($this->exactly($ticks))->method('next');
		
		$this->_draw_axis_values->draw($this->_splitter, $this->_axisElements);
	}

	/**
	 * @test
	 */
	public function createValueInEachTick() {
		$ticks = 4;

		$this->_splitter->expects($this->any())->method('splits')->will($this->returnValue($ticks));
		$this->_splitter->expects($this->exactly($ticks))->method('next');
		$this->_axisElements->expects($this->exactly($ticks))->method('value');
		
		$this->_draw_axis_values->draw($this->_splitter, $this->_axisElements);
	}

	/**
	 * @test
	 */
	public function whenDrawMethodIsCalledRewindMade() {
		$this->_splitter->expects($this->once())->method('splits')->will($this->returnValue(0));
		$this->_splitter->expects($this->once())->method('rewind');

		$this->_draw_axis_values->draw($this->_splitter, $this->_axisElements);
	}
}

require_once (dirname(__FILE__) . '/../../test_shutdown.php');