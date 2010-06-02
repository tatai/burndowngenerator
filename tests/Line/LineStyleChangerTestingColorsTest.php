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
	private $_lineStyle = null;

	public function setUp() {
		$this->_changer = new LineStyleChanger();
		$this->_pdf = $this->getMock('MetricsPdf', array(), array('a4', 'landscape'));

		$width = 1;
		$color = new Color(new Decimal(0), new Decimal(0), new Decimal(0));
		$this->_lineStyle = new LineStyle(
			$color,
			$this->getMock('LineStroke', array(), array(1, $this->getMock('ILineStroke')))
		);
	}

	/**
	 * @test
	 */
	public function strokeIsChangedInPdf() {
		$this->_pdf->expects($this->once())
			->method('setStrokeColor');

		$this->_changer->change($this->_pdf, $this->_lineStyle);
	}

	/**
	 * @test
	 */
	public function lineStyleIsChangedInPdf() {
		$this->_pdf->expects($this->once())
			->method('setLineStyle');

		$this->_changer->change($this->_pdf, $this->_lineStyle);
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');