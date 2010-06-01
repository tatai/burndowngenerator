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
require_once (dirname(__FILE__) . '/test_startup.php');

class ScaleBeautifierTest extends PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function scaleToFitAtLeastSize() {
		$axisSize = 100;
		$points = 200;
		$minSizeBetweenSteps = 5;
		
		$scale = new ScaleBeautifier($axisSize, $points, $minSizeBetweenSteps, Scale::$BASIC);
		
		$this->assertEquals(10, $scale->pointsBetweenTicks());
		$this->assertEquals(21, $scale->numberTicks());
		$this->assertEquals(5, $scale->distanceBetweenTicks());
	}

	/**
	 * @test
	 */
	public function anotherTest() {
		$axisSize = 165;
		$points = 345;
		$minSizeBetweenSteps = 5;
		
		$scale = new ScaleBeautifier($axisSize, $points, $minSizeBetweenSteps, Scale::$BASIC);
		
		$ticks = 24;
		
		$this->assertEquals(15, $scale->pointsBetweenTicks());
		$this->assertEquals($ticks, $scale->numberTicks());
		$this->assertEquals(165 / ($ticks - 1), $scale->distanceBetweenTicks());
	}
}

require_once (dirname(__FILE__) . '/test_shutdown.php');