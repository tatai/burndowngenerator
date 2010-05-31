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
class Hexadecimal implements INumeralSystem {
	private $_decimal = null;

	public function __construct($value) {
		$cleanValue = $this->_sanitize($value);
		$this->_decimal = $this->_convertHexToDecimal($cleanValue);
	}
	
	public function hexadecimal() {
		if(!is_null($this->_decimal)) {
			return dechex($this->_decimal);
		}
		
		return $this->_decimal;
	}
	
	public function decimal() {
		return $this->_decimal;
	}
	
	private function _sanitize($value) {
		$check = strtolower($value);
		if(preg_match('/^-?[0-9a-f]+$/', $check)) {
			return $check;
		}
		
		return null;
	}
	
	private function _convertHexToDecimal($value) {
		if(is_null($value)) {
			return null;
		}
		
		if($value[0] == '-') {
			$converted = -1 * hexdec(substr($value, 1));
		}
		else {
			$converted = hexdec($value);
		}
		
		return $converted;
	}
}