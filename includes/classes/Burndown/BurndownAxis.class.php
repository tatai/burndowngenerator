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
class BurndownAxis {
	/**
	 * 
	 * @var MetricsPdf
	 */
	private $_pdf = null;
	
	/**
	 * 
	 * @var BurndownMargins
	 */
	private $_margins = null;
	
	/**
	 * 
	 * @var DrawLine
	 */
	private $_drawLine = null;
	
	/**
	 * 
	 * @var DrawText
	 */
	private $_drawText = null;
	
	/**
	 * 
	 * @var LineStyleChanger
	 */
	private $_styleChanger = null;

	/**
	 * 
	 * @param $pdf MetricsPdf
	 * @param $margins BurndownMargins
	 * @param $drawLine DrawLine
	 * @param $drawText DrawText
	 */
	public function __construct(MetricsPdf $pdf, BurndownMargins $margins, DrawLine $drawLine, DrawText $drawText, LineStyleChanger $styleChanger) {
		$this->_pdf = $pdf;
		$this->_margins = $margins;
		$this->_drawLine = $drawLine;
		$this->_drawText = $drawText;
		$this->_styleChanger = $styleChanger;
	}

	public function draw(AxisSplitter $splitter, IAxisElements $axisElements, $label, $tickSize, $showGrid = true, $gridSize = 0) {
		$this->_drawAxisLine($splitter->line());
		
		$this->_drawAxisTicks($splitter, $axisElements, $tickSize);
		$this->_drawAxisValues($splitter, $axisElements);
		
		if($showGrid) {
			$this->_drawAxisGrid($splitter, $axisElements, $gridSize);
		}
		
		$this->_drawAxisLabel($axisElements, $label);
	}

	private function _drawAxisLine(Line $line) {
		$this->_drawLine->draw($line, LineStyleFactory::thinContinuous());
	}

	private function _drawAxisTicks(AxisSplitter $splitter, IAxisElements $axisElements, $tickSize) {
		$this->_styleChanger->change($this->_pdf, LineStyleFactory::thinContinuous());
		
		$axisTicks = new DrawAxisTicks($this->_drawLine);
		$axisTicks->draw($splitter, $axisElements, $tickSize);
	}

	private function _drawAxisGrid(AxisSplitter $splitter, IAxisElements $axisElements, $gridSize) {
		$this->_styleChanger->change($this->_pdf, LineStyleFactory::thinDashed());
		
		$axisGrid = new DrawAxisGrid($this->_drawLine);
		$axisGrid->draw($splitter, $axisElements, $gridSize);
	}

	private function _drawAxisValues(AxisSplitter $splitter, IAxisElements $axisElements) {
		$axisValues = new DrawAxisValues($this->_drawText);
		$axisValues->draw($splitter, $axisElements);
	}

	private function _drawAxisLabel(IAxisElements $axisElements, $label) {
		$axisValues = new DrawAxisLabel($this->_drawText);
		$axisValues->draw($axisElements, $label);
	}
}