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

class DrawAxisLabelTestTextTest extends PHPUnit_Framework_TestCase {
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
		$this->_axis_elements->expects($this->any())->method('labelPosition')->will($this->returnValue(new Point(2, 3)));
		$this->_axis_elements->expects($this->once())->method('labelDirection')->will($this->returnValue('horizontal'));
	}

	/**
	 * @test
	 */
	public function drawTextLabelGiven() {
		$label = 'foo text';
		
		$this->_draw_text->expects($this->once())->method('horizontal')->with($label, $this->anything(), $this->anything(), $this->anything());
		
		$this->_axis_label->draw($this->_axis_elements, $label);
	}

	/**
	 * @test
	 */
	public function textIsTrimmed() {
		$label = '  foo text  ';
		
		$this->_draw_text->expects($this->once())->method('horizontal')->with(trim($label), $this->anything(), $this->anything(), $this->anything());
		
		$this->_axis_label->draw($this->_axis_elements, $label);
	}

	/**
	 * @test
	 */
	public function ifNoTextGivenNoCallsToDrawText() {
		$label = '';
		
		$this->_draw_text->expects($this->never())->method('horizontal');
		
		$this->_axis_label->draw($this->_axis_elements, $label);
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');