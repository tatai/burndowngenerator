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

class LineStyleTest extends PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function returnSameColorAsGiven() {
		$color = new Color(new Decimal(0), new Decimal(0), new Decimal(0));
		$stroke = new LineStroke(1, new LineStrokeContinuous());
		
		$lineStyle = new LineStyle($color, $stroke);
		
		$this->assertTrue($color->isEqual($lineStyle->getColor()));
	}

	/**
	 * @test
	 */
	public function returnSameStrokeAsGiven() {
		$color = new Color(new Decimal(0), new Decimal(0), new Decimal(0));
		$stroke = new LineStroke(1, new LineStrokeContinuous());
		
		$lineStyle = new LineStyle($color, $stroke);
		
		$this->assertTrue($stroke->isEqual($lineStyle->getStroke()));
	}
}

require_once (dirname(__FILE__) . '/../../test_shutdown.php');