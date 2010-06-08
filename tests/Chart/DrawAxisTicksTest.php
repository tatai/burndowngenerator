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
	 * @var int
	 */
	private $_tick_size = null;
	
	/**
	 * 
	 * @var DrawAxisTicks
	 */
	private $_draw_axis_ticks = null;
	
	/**
	 * 
	 * @var Line
	 */
	private $_line = null;
	
	/**
	 * 
	 * @var float
	 */
	private $_step = null;

	public function setUp() {
		$pdf = $this->getMock('MetricsPdf', array(), array(
			'a4', 
			'landscape'));
		$styleChanger = new LineStyleChanger();
		
		$this->_draw_line = $this->getMock('DrawLine', array(), array(
			$pdf, 
			$styleChanger));
		
		$this->_tick_size = 3;
		$this->_step = 2;
		
		$this->_line = new Line(new Point(2, 4), new Point(8.5, 4));
		
		$this->_draw_axis_ticks = new DrawAxisTicks($this->_draw_line);
	}
	
	/**
	 * @test
	 */
	public function createsTicksWithinLine() {
		$distanceBetweenTicks = 3;
		$lineSize = $this->_line->to()->x() - $this->_line->from()->x();
		
		$ticks = (int)floor($lineSize / $distanceBetweenTicks);

		$this->_draw_line->expects($this->exactly($ticks))->method('draw');
		
		$this->_draw_axis_ticks->draw($distanceBetweenTicks, $this->_tick_size, $this->_line);
	}

	/**
	 * @TODO: impossible to make this work with PHPUnit 3.4.1
	 */
	public function twoTicksMeansOneTickAtStartPointAndOneAtTheEnd() {
		$ticks = 2;
		$startTick = new Line(
			new Point($this->_start_point->x(), $this->_start_point->y() - $this->_tick_size / 2),
			new Point($this->_start_point->x(), $this->_start_point->y() + $this->_tick_size / 2)
		);
		
		$endTick = new Line(
			new Point($this->_end_point->x(), $this->_end_point->y() - $this->_tick_size / 2),
			new Point($this->_end_point->x(), $this->_end_point->y() + $this->_tick_size / 2)
		);
		
		$this->_draw_line->expects($this->once())->method('draw')->with($startTick);
		$this->_draw_line->expects($this->once())->method('draw')->with($endTick);
		
		$this->_draw_axis_ticks->draw($ticks, $this->_tick_size, $this->_line);
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');