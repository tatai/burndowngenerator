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
class AxisHorizontalElements extends AxisElementsAbstract {
	/**
	 * 
	 * @param int $textSize
	 * @param float $tickStart
	 * @param float $tickIncrement
	 * @param Point $labelPosition
	 */
	public function __construct($textSize, $tickStart, $tickIncrement, Point $labelPosition) {
		parent::__construct($textSize, $tickStart, $tickIncrement, $labelPosition, 'horizontal');
	}
	
	public function tick(Point $at, $size) {
		$midSize = $size / 2;
		return new Line(new Point($at->x(), $at->y() - $midSize), new Point($at->x(), $at->y() + $midSize));
	}

	public function grid(Point $at, $size) {
		return new Line(new Point($at->x(), $at->y()), new Point($at->x(), $at->y() + $size));
	}

	public function value(DrawText $drawText, $text, $at, $size) {
		$position = new Point($at->x(), $at->y() - $size - 2);

		$drawText->horizontal($text, $size, $position, 'center');
	}
}