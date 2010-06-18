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
include(dirname(__FILE__) . '/../pdf/class.ezpdf.php');

class MetricsPdf extends Cezpdf implements IPdf {
	protected $_ratio = null;

	public function __construct($paper, $orientation) {
		parent::__construct($paper, $orientation);

		$this->_ratio = 595.28 / 210;
	}

	protected function _convert($value) {
		return $value * $this->_ratio;
	}

	public function line($x1, $y1, $x2, $y2) {
		return parent::line($this->_convert($x1), $this->_convert($y1), $this->_convert($x2), $this->_convert($y2));
	}

	public function addTextWrap($x, $y, $width, $size, $text, $just = 'left', $angle = 0) {
		// $width is multiplied by 1.01 (1%) trying to avoid line jumping (second line will not appear)
		return parent::addTextWrap($this->_convert($x), $this->_convert($y), $this->_convert($width) * 1.01, $this->_convert($size), $text, $just, $angle);
	}

	public function getPageWidth() {
		return round($this->ez['pageWidth'] / 72 * 2.54 * 10);
	}

	public function getPageHeight() {
		return round($this->ez['pageHeight'] / 72 * 2.54 * 10);
	}

	public function rectangle($x1, $y1, $x2, $y2) {
		$this->line($x1, $y1, $x2, $y1);
		$this->line($x2, $y1, $x2, $y2);
		$this->line($x2, $y2, $x1, $y2);
		$this->line($x1, $y2, $x1, $y1);
	}
}
