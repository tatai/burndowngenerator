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

class AxisSplitterTest extends PHPUnit_Framework_TestCase {
	/**
	 * 
	 * @var AxisSplitter
	 */
	private $_axisSplitter = null;
	
	/**
	 * 
	 * @var Line
	 */
	private $_line = null;
	
	private $_splits = null;

	public function setUp() {
		$this->_line = new Line(new Point(1, 1), new Point(6.5, 1));
		$this->_axisSplitter = new AxisSplitter(2, $this->_line);
		$this->_splits = 3;
	}

	/**
	 * @test
	 */
	public function whenGivingCorrectDataCorrectNumberOfSplitsCanBeQueried() {
		$this->assertEquals($this->_splits, $this->_axisSplitter->splits());
	}
	
	/**
	 * @test
	 */
	public function returnsAsManyPointsAsSplits() {
		for($i = 0; $i < $this->_splits; $i++) {
			$this->assertTrue($this->_axisSplitter->next() instanceof Point);
		}
		
		$this->assertNull($this->_axisSplitter->next());
	}

	/**
	 * @test
	 */
	public function pointsAreCalculatedCorrectly() {
		$this->assertEquals(new Point(1, 1), $this->_axisSplitter->next());
		$this->assertEquals(new Point(3, 1), $this->_axisSplitter->next());
		$this->assertEquals(new Point(5, 1), $this->_axisSplitter->next());
		$this->assertNull($this->_axisSplitter->next());
	}
	
	/**
	 * @test
	 */
	public function pointsCanBeReturnedAgain() {
		$this->pointsAreCalculatedCorrectly();
		$this->_axisSplitter->rewind();
		$this->pointsAreCalculatedCorrectly();
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');