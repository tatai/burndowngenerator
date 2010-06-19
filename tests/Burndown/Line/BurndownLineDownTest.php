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

class BurndownLineDownTest extends PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function lineIsDrawnFromUpperLeftToLowerRight() {
		$pdf = $this->getMock('IPdf');
		
		$top = 45;
		$left = 28;
		$bottom = 104;
		$right = 143;
	
		$pdf->expects($this->once())->method('line')->with($left, $top, $right, $bottom);

		$line = new BurndownLineDown(new Point($left, $top), new Point($right, $bottom));
		$line->draw($pdf);
	}
}

require_once (dirname(__FILE__) . '/../../test_shutdown.php');