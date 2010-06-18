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

class DrawAxisTicksTest extends PHPUnit_Framework_TestCase {
	/**
	 * 
	 * @var DrawLine
	 */
	private $_draw_line = null;

	/**
	 * 
	 * @var DrawAxisTicks
	 */
	private $_draw_axis_ticks = null;

	public function setUp() {
		$pdf = $this->getMock('IPdf');
		$styleChanger = new LineStyleChanger();
		
		$this->_draw_line = $this->getMock('DrawLine', array(), array(
			$pdf, 
			$styleChanger));
		
		$this->_draw_axis_ticks = new DrawAxisTicks($this->_draw_line);
	}
	
	/**
	 * @test
	 */
	public function createsTicksWithinLine() {
		$ticks = 4;
		$tickSize = 4;

		$splits = $this->getMock('AxisSplitter', array(), array(1, new Line(new Point(2, 4), new Point(8.5, 4))));
		$axisElements = $this->getMock('IAxisElements');
		$axisElements->expects($this->exactly($ticks))->method('tick')->will($this->returnValue(new Line(new Point(1, 2), new Point(6, 7))));

		$splits->expects($this->any())->method('splits')->will($this->returnValue($ticks));
		$splits->expects($this->any())->method('next')->will($this->returnValue(new Point(1, 2)));

		$this->_draw_line->expects($this->exactly($ticks))->method('draw');

		$this->_draw_axis_ticks->draw($splits, $axisElements, $tickSize);
	}

	/**
	 * @test
	 */
	public function whenDrawMethodIsCalledRewindMade() {
		$axisElements = $this->getMock('IAxisElements');

		$splitter = $this->getMock('AxisSplitter', array(), array(1, new Line(new Point(2, 4), new Point(8.5, 4))));
		$splitter->expects($this->once())->method('splits')->will($this->returnValue(0));
		$splitter->expects($this->once())->method('rewind');

		$this->_draw_axis_ticks->draw($splitter, $axisElements, 10);
	}

	/**
	 * @TODO: impossible to make this work with PHPUnit 3.4.1
	 */
	public function twoTicksMeansOneTickAtStartPointAndOneAtTheEnd() {
		$ticks = 2;
		$tickSize = 3;

		$startTick = new Line(
			new Point($this->_start_point->x(), $this->_start_point->y() - $tickSize / 2),
			new Point($this->_start_point->x(), $this->_start_point->y() + $tickSize / 2)
		);
		
		$endTick = new Line(
			new Point($this->_end_point->x(), $this->_end_point->y() - $tickSize / 2),
			new Point($this->_end_point->x(), $this->_end_point->y() + $tickSize / 2)
		);
		
		$this->_draw_line->expects($this->once())->method('draw')->with($startTick);
		$this->_draw_line->expects($this->once())->method('draw')->with($endTick);
		
		$this->_draw_axis_ticks->draw($ticks, $tickSize, $this->_line);
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');