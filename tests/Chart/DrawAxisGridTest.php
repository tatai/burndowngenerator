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

class DrawAxisGridTest extends PHPUnit_Framework_TestCase {
	/**
	 * 
	 * @var DrawLine
	 */
	private $_draw_line = null;

	/**
	 * 
	 * @var DrawAxisGrid
	 */
	private $_draw_axis_grid = null;

	public function setUp() {
		$pdf = $this->getMock('IPdf');
		$styleChanger = new LineStyleChanger();
		
		$this->_draw_line = $this->getMock('DrawLine', array(), array(
			$pdf, 
			$styleChanger));
		
		$this->_draw_axis_grid = new DrawAxisGrid($this->_draw_line);
	}
	
	/**
	 * @test
	 */
	public function createGridInEachTickExceptForZero() {
		$ticks = 4;
		$gridSize = 4;

		$splitter = $this->getMock('AxisSplitter', array(), array(1, new Line(new Point(2, 4), new Point(8.5, 4))));
		$axisElements = $this->getMock('IAxisElements');
		$axisElements->expects($this->exactly($ticks - 1))->method('grid')->will($this->returnValue(new Line(new Point(1, 2), new Point(6, 7))));

		$splitter->expects($this->any())->method('splits')->will($this->returnValue($ticks));
		$splitter->expects($this->any())->method('next')->will($this->returnValue(new Point(1, 2)));

		$this->_draw_line->expects($this->exactly($ticks - 1))->method('draw');

		$this->_draw_axis_grid->draw($splitter, $axisElements, $gridSize);
	}

	/**
	 * @test
	 */
	public function whenDrawMethodIsCalledRewindMade() {
		$axisElements = $this->getMock('IAxisElements');

		$splitter = $this->getMock('AxisSplitter', array(), array(1, new Line(new Point(2, 4), new Point(8.5, 4))));
		$splitter->expects($this->once())->method('splits')->will($this->returnValue(0));
		$splitter->expects($this->once())->method('rewind');

		$this->_draw_axis_grid->draw($splitter, $axisElements, 10);
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');