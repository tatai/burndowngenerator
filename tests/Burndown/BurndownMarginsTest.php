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

class BurndownMarginsTest extends PHPUnit_Framework_TestCase {
	/**
	 * 
	 * @var BurndownMargins
	 */
	private $_margins = null;
	
	private $_left = null;
	private $_right = null;
	private $_top = null;
	private $_bottom = null;

	public function setUp() {
		$this->_left = rand(1, 100);
		$this->_right = rand(1, 100);
		$this->_top = rand(1, 100);
		$this->_bottom = rand(1, 100);

		$this->_margins = new BurndownMargins($this->_left, $this->_top, $this->_right, $this->_bottom);
	}

	/**
	 * @test
	 */
	public function leftMarginCanBeReturned() {
		$this->assertEquals($this->_left, $this->_margins->left());
	}
	
	/**
	 * @test
	 */
	public function topMarginCanBeReturned() {
		$this->assertEquals($this->_top, $this->_margins->top());
	}

	/**
	 * @test
	 */
	public function rightMarginCanBeReturned() {
		$this->assertEquals($this->_right, $this->_margins->right());
	}

	/**
	 * @test
	 */
	public function bottomMarginCanBeReturned() {
		$this->assertEquals($this->_bottom, $this->_margins->bottom());
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');