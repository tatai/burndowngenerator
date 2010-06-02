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
class DrawText {

	public function __construct() {
	
	}

	public function horizontal(MetricsPdf &$pdf, $text, $size, Point $position, $align = 'left', $decorator = null) {
		$width = $pdf->getTextWidth($size, $text);
		
		$x = $this->_calculateTextPosition($position, 'x', $align, $width);
		$y = $position->y();
		
		$pdf->addTextWrap(
			$x,
			$y,
			$width,
			$size, 
			$text
		);
		
		if($decorator instanceof ITextDecorator) {
			$upperLeft = new Point($x, $y);
			$lowerRight = new Point($x + $width, $y + $size);
			$this->_callDecorator($pdf, $decorator, $upperLeft, $lowerRight);
		}
	}
	
	public function vertical(MetricsPdf &$pdf, $text, $size, Point $position, $align = 'left', $decorator = null) {
		$width = $pdf->getTextWidth($size, $text);
		
		$x = $position->x();
		$y = $this->_calculateTextPosition($position, 'y', $align, $width);
		
		$pdf->addTextWrap(
			$x,
			$y,
			$width,
			$size, 
			$text,
			'left',
			270
		);

		if($decorator instanceof ITextDecorator) {
			$upperLeft = new Point($x, $y);
			$lowerRight = new Point($x + $width, $y + $size);
			$this->_callDecorator($pdf, $decorator, $upperLeft, $lowerRight);
		}
	}
	
	private function _calculateTextPosition(Point $position, $axis, $align, $width) {
		if($align == 'center') {
			return $position->$axis() - ($width / 2);
		}
		else if($align == 'right') {
			return $position->$axis() - $width;
		}
		
		return $position->$axis();
	}

	private function _callDecorator(MetricsPdf $pdf, ITextDecorator $decorator, Point $upperLeft, Point $lowerRight) {
		$decorator->draw($pdf, $upperLeft, $lowerRight);
	}
}