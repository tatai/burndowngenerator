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
abstract class AxisElementsAbstract implements IAxisElements {
	private $_text_size = null;
	private $_tick_start = null;
	private $_tick_increment = null;
	
	public function __construct($textSize, $tickStart, $tickIncrement) {
		$this->_text_size = $textSize;
		$this->_tick_start = $tickStart;
		$this->_tick_increment = $tickIncrement;
	}

	public function textSize() {
		return $this->_text_size;
	}

	public function tickStart() {
		return $this->_tick_start;
	}

	public function tickIncrement() {
		return $this->_tick_increment;
	}
}