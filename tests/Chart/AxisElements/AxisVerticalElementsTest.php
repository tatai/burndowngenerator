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

class AxisVerticalElementsTest extends PHPUnit_Framework_TestCase {
	/**
	 * 
	 * @var AxisVerticalElements
	 */
	private $_elements = null;

	public function setUp() {
		$this->_elements = new AxisVerticalElements(7, 2, 6, new Point(2, 7));
	}

	/**
	 * @test
	 */
	public function tickIsCorrectlyDrawn() {
		$size = 4;
		
		$point = new Point(3, 4);
		$result = new Line(new Point(1, 4), new Point(5, 4));
		
		$this->assertEquals($result, $this->_elements->tick($point, $size));
	}

	/**
	 * @test
	 */
	public function gridIsCorrectlyDrawn() {
		$size = 16;
		
		$point = new Point(3, 4);
		$result = new Line(new Point(3, 4), new Point(19, 4));
		
		$this->assertEquals($result, $this->_elements->grid($point, $size));
	}

	/**
	 * @test
	 */
	public function valueIsCorrectlyDrawn() {
		$text = 'aaaa';
		$size = 12;
		$pdf = $this->getMock('IPdf');

		$drawText = $this->getMock('DrawText', array(), array($pdf));
		$drawText->expects($this->once())->method('horizontal')->with($text, $size, $this->anything(), 'right');
		
		$this->_elements->value($drawText, $text, new Point(rand(1, 20), rand(1, 20)), $size);
	}

	/**
	 * @test
	 */
	public function returnsLabelDirection() {
		$this->assertEquals('vertical', $this->_elements->labelDirection());
	}
}

require_once (dirname(__FILE__) . '/../../test_shutdown.php');