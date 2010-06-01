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
class Text {

	public function __construct() {
	
	}

	public function horizontal(MetricsPdf &$pdf, $text, $size, Point $position, $align = 'left') {
		$size = 10;
		$width = $pdf->getTextWidth($size, $text);
		
		$x = $this->_calculateTextPosition($position, $align, $width);
		
		$pdf->addTextWrap(
			$x,
			$position->y(),
			$width,
			$size, 
			$text
		);
	}
	
	private function _calculateTextPosition(Point $position, $align, $width) {
		if($align == 'center') {
			return $position->x() - ($width / 2);
		}
		else if($align == 'right') {
			return $position->x() - $width;
		}
		
		return $position->x();
	} 
}