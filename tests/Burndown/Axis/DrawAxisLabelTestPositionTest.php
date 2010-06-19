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

class DrawAxisLabelTestPositionTest extends PHPUnit_Framework_TestCase {
	/**
	 * 
	 * @var DrawText
	 */
	private $_draw_text = null;
	
	/**
	 * 
	 * @var DrawAxisLabel
	 */
	private $_axis_label = null;
	
	/**
	 * 
	 * @var AxisElements
	 */
	private $_axis_elements = null;

	public function setUp() {
		$pdf = $this->getMock('IPdf');
		
		$this->_draw_text = $this->getMock('DrawText', array(), array(
			$pdf));
		
		$this->_axis_label = new DrawAxisLabel($this->_draw_text);
		
		$this->_axis_elements = $this->getMock('IAxisElements');
		;
	}

	/**
	 * @test
	 */
	public function drawTextInPositionGiven() {
		$point = new Point(rand(1, 20), rand(1, 20));
		$this->_axis_elements->expects($this->once())->method('labelPosition')->will($this->returnValue($point));
		$this->_axis_elements->expects($this->once())->method('labelDirection')->will($this->returnValue('horizontal'));
		
		
		$this->_draw_text->expects($this->once())->method('horizontal')->with($this->anything(), $this->anything(), $point, $this->anything());
		
		$this->_axis_label->draw($this->_axis_elements, 'aaaa');
	}
}

require_once (dirname(__FILE__) . '/../../test_shutdown.php');