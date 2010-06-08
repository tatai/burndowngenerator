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
class LineOperations {

	/**
	 * 
	 * @param Line $line
	 * @param Point $at
	 * @param float $length
	 * @return Line
	 */
	public function perpendicular(Line $line, Point $at, $length) {
		if($line->from()->x() == $line->to()->x()) {
			return $this->_perpendicularFromVerticalLine($line, $at, $length);
		}
		else if($line->from()->y() == $line->to()->y()) {
			return $this->_perpendicularFromHorizontalLine($line, $at, $length);
		}
	}

	/**
	 *
	 * @param Line $line
	 * @param Point $at
	 * @param float $length
	 * @return Line
	 */
	private function _perpendicularFromHorizontalLine(Line $line, Point $at, $length) {
		$medTickSize = $length / 2;
		
		$medTickSize *= $this->_chooseSign($line, 'x');
		
		$from = new Point($at->x(), $at->y() - $medTickSize);
		$to = new Point($at->x(), $at->y() + $medTickSize);
		
		return new Line($from, $to);
	}

	/**
	 *
	 * @param Line $line
	 * @param Point $at
	 * @param float $length
	 * @return Line
	 */
	private function _perpendicularFromVerticalLine(Line $line, Point $at, $length) {
		$medTickSize = $length / 2;
		
		$medTickSize *= $this->_chooseSign($line, 'y');
		
		$from = new Point($at->x() - $medTickSize, $at->y());
		$to = new Point($at->x() + $medTickSize, $at->y());
		
		return new Line($from, $to);
	}

	private function _chooseSign(Line $line, $dimension) {
		if($line->to()->$dimension() - $line->from()->$dimension() < 0) {
			return -1;
		}
		
		return 1;
	}
}