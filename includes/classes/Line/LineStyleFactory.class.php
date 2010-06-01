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
class LineStyleFactory {

	private function __construct() {
	
	}

	static public function thinContinuous() {
		return array(
			new Color(new Decimal(0), new Decimal(0), new Decimal(0)), 
			new LineStroke(1, new LineStyleContinuous()));
	}

	static public function thickContinuous(Color $color) {
		return array(
			$color, 
			new LineStroke(1, new LineStyleContinuous()));
	}

	static public function thinDashed(Color $color) {
		return array(
			new Color(new Decimal(200), new Decimal(200), new Decimal(200)), 
			new LineStroke(1, new LineStyleDashed(5)));
	}
}