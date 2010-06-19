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
class AxisSplitter {
	private $_splits = null;
	private $_xSplit = null;
	private $_ySplit = null;
	private $_current = null;
	
	/**
	 * 
	 * @var Line
	 */
	private $_line = null;

	public function __construct($distance, Line $line) {
		$this->_line = $line;

		$separation = $line->size() / $distance;
		$this->_splits = (int)floor($separation) + 1;
		//print $line->size() . ' ' . $distance . ' ' . floor(round($separation)) . ' ' . $this->_splits . '<br />';
		
		$this->_xSplit = ($line->to()->x() - $line->from()->x()) / $separation;
		$this->_ySplit = ($line->to()->y() - $line->from()->y()) / $separation;
		
		$this->rewind();
	}
	
	public function splits() {
		return $this->_splits;
	}
	
	/**
	 *
	 * @return Line
	 */
	public function line() {
		return $this->_line;
	}

	/**
	 *
	 * @return Point|NULL
	 */
	public function next() {
		if($this->_current < $this->splits()) {
			$at = new Point(
				$this->_line->from()->x() + $this->_xSplit * $this->_current,
				$this->_line->from()->y() + $this->_ySplit * $this->_current
			);
			
			$this->_current++;
			
			return $at;
		}
		
		return null;
	}
	
	public function rewind() {
		$this->_current = 0;
	}
}