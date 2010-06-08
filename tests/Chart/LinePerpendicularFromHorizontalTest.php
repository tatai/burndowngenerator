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

class LinePerpendicularFromHorizontalTest extends PHPUnit_Framework_TestCase {
	private $_from = null;
	private $_to = null;
	private $_at = null;
	
	/**
	 * 
	 * @var LineOperations
	 */
	private $_operations = null;

	public function setUp() {
		$this->_from = new Point(1, 2);
		$this->_to = new Point(4, 2);
		$this->_at = new Point(3, 2);
		
		$this->_operations = new LineOperations();
	}

	/**
	 * @test
	 */
	public function createPerpendicularLineInPointFromHorizontal0Degrees() {
		$length = 2;
		
		$perpendicularFrom = new Point(3, 1);
		$perpendicularTo = new Point(3, 3);
		
		$line = new Line($this->_from, $this->_to);
		
		$perpendicular = $this->_operations->perpendicular($line, $this->_at, $length);
		$this->assertEquals($perpendicularFrom, $perpendicular->from());
		$this->assertEquals($perpendicularTo, $perpendicular->to());
	}
	
	/**
	 * @test
	 */
	public function createPerpendicularLineInPointFromHorizontal180Degrees() {
		$length = 2;
		
		$perpendicularFrom = new Point(3, 3);
		$perpendicularTo = new Point(3, 1);
		
		$line = new Line($this->_to, $this->_from);
		
		$perpendicular = $this->_operations->perpendicular($line, $this->_at, $length);
		$this->assertEquals($perpendicularFrom, $perpendicular->from());
		$this->assertEquals($perpendicularTo, $perpendicular->to());
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');