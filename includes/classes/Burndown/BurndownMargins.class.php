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
class BurndownMargins {
	/**
	 * 
	 * @var int
	 */
	private $_left = null;
	
	/**
	 * 
	 * @var int
	 */
	private $_right = null;
	
	/**
	 * 
	 * @var int
	 */
	private $_top = null;
	
	/**
	 * 
	 * @var int
	 */
	private $_bottom = null;

	public function __construct($left, $top, $right, $bottom) {
		$this->_left = $this->_validate($left);
		$this->_top = $this->_validate($top);
		$this->_right = $this->_validate($right);
		$this->_bottom = $this->_validate($bottom);
	}
	
	public function left() {
		return $this->_left;
	}
	
	public function top() {
		return $this->_top;
	}
	
	public function right() {
		return $this->_right;
	}
	
	public function bottom() {
		return $this->_bottom;
	}
	
	private function _validate($value) {
		return is_numeric($value) ? $value : 0;
	}
}