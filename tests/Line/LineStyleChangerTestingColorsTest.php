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

class LineStyleChangerTestingColorsTest extends PHPUnit_Framework_TestCase {
	private $_changer = null;
	private $_stroke = null;

	public function setUp() {
		$this->_changer = new LineStyleChanger();
		$this->_pdf = $this->getMock('MetricsPdf', array(), array('a4', 'landscape'));

		$width = 1;
		$line = $this->getMock('ILineStyle');
		$this->_stroke = $this->getMock('LineStroke', array(), array(1, $line));
	}

	/**
	 * @test
	 */
	public function changesColorWithDecimalValues() {
		$red = new Decimal(30);
		$green = new Decimal(20);
		$blue = new Decimal(100);
		
		$lineColor = new Color($red, $green, $blue);

		$this->_pdf->expects($this->once())
			->method('setStrokeColor')
			->with($red->decimal() / 255, $green->decimal() / 255, $blue->decimal() / 255);

		$this->_changer->change($this->_pdf, $lineColor, $this->_stroke);
	}

	/**
	 * @test
	 */
	public function changesColorWithHexadecimalValues() {
		$red = new Hexadecimal('1a');
		$green = new Hexadecimal('33');
		$blue = new Hexadecimal('ff');
		
		$lineColor = new Color($red, $green, $blue);

		$this->_pdf->expects($this->once())
			->method('setStrokeColor')
			->with($red->decimal() / 255, $green->decimal() / 255, $blue->decimal() / 255);

		$this->_changer->change($this->_pdf, $lineColor, $this->_stroke);
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');