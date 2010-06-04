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
class BurndownTitle {
	/**
	 * @var MetricsPdf
	 */
	private $_pdf = null;
	
	/**
	 * 
	 * @var DrawText
	 */
	private $_text = null;
	
	/**
	 * 
	 * @var BurndownMargins
	 */
	private $_margins = null;

	/**
	 * 
	 * @param MetricsPdf $pdf
	 * @param DrawText $text
	 * @param BurndownMargins $margins
	 */
	public function __construct(MetricsPdf $pdf, DrawText $text, BurndownMargins $margins) {
		$this->_pdf = $pdf;
		$this->_text = $text;
		$this->_margins = $margins;
	}

	public function draw($title = null) {
		$size = 10;
		$text = $this->_validate($title);
		
		$position = new Point($this->_pdf->getPageWidth() / 2, $this->_pdf->getPageHeight() - $this->_margins->top() + $size / 2);
		$this->_text->horizontal($this->_pdf, $text, $size, $position, 'center');
	}

	private function _validate($title) {
		$text = 'Burndown online generator';
		$validator = new ValidatorNonEmptyString();
		
		if($validator->check($title)) {
			$text = $title;
		}
	
		return $text;
	}
}