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
class Vector {
	/**
	 * 
	 * @var float
	 */
	private $_slope = null;
	
	/**
	 * 
	 * @var float
	 */
	private $_independent = null;

	public function __construct($slope, $independent) {
		$this->_slope = $slope;
		$this->_independent = $independent;
	}

	public function slope() {
		return $this->_slope;
	}

	public function independent() {
		return $this->_independent;
	}

	public function calculateY($x) {
		return $this->slope() * $x + $this->independent();
	}

	public function calculateX($y) {
		return ($y - $this->independent()) / $this->slope();
	}
	
	public function isEqual(Vector $test) {
		return (($test->slope() == $this->slope()) && ($test->independent() == $this->independent()));
	}
}