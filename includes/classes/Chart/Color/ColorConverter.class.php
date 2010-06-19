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
class ColorConverter {

	public function __construct() {
	}

	/**
	 * Converts from RGB string value to Color object
	 *
	 * @param $colorStr string
	 * @return Color
	 */
	public function fromRGBToColor($colorStr) {
		$value = strtolower($colorStr);
		if(preg_match('/^#?(?P<red>[0-9a-f]{2})(?P<green>[0-9a-f]{2})(?P<blue>[0-9a-f]{2})$/', $value, $c)) {
			return new Color(
				new Hexadecimal($c['red']),
				new Hexadecimal($c['green']),
				new Hexadecimal($c['blue'])
			);
		}
		else if(preg_match('/^#?(?P<red>[0-9a-f])(?P<green>[0-9a-f])(?P<blue>[0-9a-f])$/', $value, $c)) {
			return new Color(
				new Hexadecimal($c['red'] . $c['red']),
				new Hexadecimal($c['green'] . $c['green']),
				new Hexadecimal($c['blue'] . $c['blue'])
			);
		}
	}
}
