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
include_once(dirname(__FILE__) . '/ValidatorBase.class.php');
class ValidatorRGBString extends ValidatorBase {
	public function check($value) {
		$this->_error = null;

		$check = $this->_clean($value);
		if(!preg_match('/#[0-9a-f]{6}$/', $check)) {
			$this->_error = '%%name%% must be in #rrggbb hex format';
			return false;
		}

		return true;
	}

	private function _clean($value) {
		$clean = trim($value);

		return $clean;
	}
}
