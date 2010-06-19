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
class LineStroke {
	private $_width = null;
	private $_valid = null;
	private $_style = null;

	/**
	 * 
	 * @param $width int stroke width
	 * @param $style ILineStroke line style
	 */
	public function __construct($width, ILineStroke $style) {
		$this->_valid = $this->_checkIsNotNegative($width);

		if($this->isValid()) {
			$this->_width = $width;
			$this->_style = $style;
		}
	}
	
	public function getWidth() {
		return $this->_width;
	}
	
	public function getCap() {
		return $this->_style->getCap();
	}
	
	public function getDash() {
		return $this->_style->getDash();
	}
	
	private function _checkIsNotNegative($value) {
		return (is_numeric($value) && $value >= 0);
	}
	
	public function isValid() {
		return $this->_valid;
	}
	
	public function isEqual(LineStroke $stroke) {
		return ($this->getWidth() == $stroke->getWidth()) && ($this->getCap() == $stroke->getCap()) && ($this->getDash()== $stroke->getDash());
	}
}