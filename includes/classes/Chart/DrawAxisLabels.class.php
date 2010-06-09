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
class DrawAxisLabels {
	/**
	 * 
	 * @var DrawText
	 */
	private $_drawText = null;
	
	/**
	 * 
	 * @var MetricsPdf
	 */
	private $_pdf = null;
	
	/**
	 * 
	 * @var int
	 */
	private $_start = null;
	
	/**
	 * 
	 * @var int
	 */
	private $_increment = null;
	
	/**
	 * 
	 * @var int
	 */
	private $_current = null;

	/**
	 * 
	 * @param MetricsPdf $pdf
	 * @param DrawText $drawText
	 * @param int $start
	 * @param int $end
	 */
	public function __construct(MetricsPdf $pdf, DrawText $drawText, $start, $increment) {
		$this->_pdf = $pdf;
		$this->_drawText = $drawText;
		$this->_start = $start;
		$this->_increment = $increment;
	}
	
	public function next(Point $at, $align = 'center') {
		$this->_calculateNextCurrentValue();

		$this->_drawText->horizontal($this->_pdf, $this->last(), 4, $at, $align);
	}
	
	public function last() {
		return $this->_current;
	}
	
	private function _calculateNextCurrentValue() {
		$this->_current = is_null($this->_current) ? $this->_start : $this->_current + $this->_increment;
	}
}