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
class Color {
	private $_red = null;
	private $_green = null;
	private $_blue = null;
	private $_isValid = null;

	/**
	 * Define line color
	 * 
	 * @param $red INumeralSystem value for red
	 * @param $green INumeralSystem value for green
	 * @param $blue INumeralSystem value for blue
	 */
	public function __construct(INumeralSystem $red, INumeralSystem $green, INumeralSystem $blue) {
		$this->_isValid = $this->_checkIsValid($red) && $this->_checkIsValid($green) && $this->_checkIsValid($blue);
		
		if($this->isValid()) {
			$this->_red = $red;
			$this->_green = $green;
			$this->_blue = $blue;
		}
	}

	public function isValid() {
		return $this->_isValid;
	}

	public function red() {
		return $this->_red->decimal() / 255;
	}

	public function green() {
		return $this->_green->decimal() / 255;
	}

	public function blue() {
		return $this->_blue->decimal() / 255;
	}

	public function isEqual(Color $color) {
		return ($this->red() == $color->red()) && ($this->green() == $color->green()) && ($this->blue() == $color->blue());
	}

	private function _checkIsValid(INumeralSystem $number) {
		$value = $number->decimal();
		if(is_numeric($value) && $value <= 255 && $value >= 0) {
			return true;
		}
		
		return false;
	}
}