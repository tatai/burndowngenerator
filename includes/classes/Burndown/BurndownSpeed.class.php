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
class BurndownSpeed {
	/**
	 * @var MetricsPdf
	 */
	private $_pdf = null;
	
	/**
	 * 
	 * @var LineStyleChanger
	 */
	private $_lineStyleChanger = null;
	
	/**
	 * 
	 * @var Text
	 */
	private $_text = null;
	
	public function __construct(MetricsPdf &$pdf, LineStyleChanger &$changer, Text &$text) {
		$this->_pdf = $pdf;
		$this->_lineStyleChanger = $changer;
		$this->_text = $text;
	}
	
	public function draw($text, array $margins) {
		$this->_setLineStyle();
		$this->_drawSpeedText($text, $margins);
	}
	
	private function _setLineStyle() {
		$color = new Color(new Decimal(0), new Decimal(0), new Decimal(0));
		$stroke = new LineStroke(1, new LineStyleContinuous());

		$this->_lineStyleChanger->change($this->_pdf, $color, $stroke);
	}
	
	private function _drawSpeedText($text, array $margins) {
		$border = new TextBorder(1);
		
		$size = 7;
		$position = new Point(
			$margins['left'],
			$this->_pdf->getPageHeight() - $margins['top'] + 5
		);

		$this->_text->horizontal($this->_pdf, $text, $size, $position, 'right', $border);
	}
}