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
class Burndown {
	/**
	 * PDF Class
	 * 
	 * @var MetricsPdf
	 * @private
	 */
	private $_pdf = null;
	
	/**
	 * Class to handle text insertion into pdf
	 * 
	 * @var DrawText
	 */
	private $_text = null;
	
	/**
	 * Delegación del cambio de estilos de línea
	 * 
	 * @var LineStyleChanger
	 */
	private $_styleChanger = null;
	
	/**
	 * Delegación del dibujo de líneas
	 * 
	 * @var DrawLine
	 */
	private $_drawLine = null;
	
	/**
	 * Margins in pdf drawing
	 * 
	 * @var BurndownMargins
	 */
	private $_margins = null;
	
	private
		$_points = null,
		$_days = null,
		$_tick_size = null,
		$_title = null,
		$_hide_speed = null,
		$_hide_grid = null,
		$_burndown_color = null,
		$_xlabel = null,
		$_ylabel = null,
		$_chart_type = null;

	public function __construct($pdf, $points, $days) {
		$this->_pdf = $pdf;
		$this->_points = $points;
		$this->_days = $days;
		$this->_text = new DrawText($this->_pdf);
		$this->_styleChanger = new LineStyleChanger();
		$this->_drawLine = new DrawLine($this->_pdf, $this->_styleChanger);
		
		$this->_margins = new BurndownMargins(20, 25, 20, 20);
		
		$this->_tick_size = 4;
		$this->_hide_speed = false;
		$this->_hide_grid = false;
		$this->_burndown_color = '#000000';
		$this->_chart_type = 'burndown';
	}

	public function setOptions($options) {
		foreach($options as $k => $v) {
			$k = '_' . $k;
			$this->$k = $v;
		}
	}

	public function output() {
		$this->_log();
		
		$this->_drawTitle();
		
		$this->_drawXAxis();
		$this->_drawYAxis();
		
		$this->_drawBurndownLine();
		
		if(!$this->_hide_speed) {
			$this->_drawSpeed();
		}
		
		$this->_drawAds();
		
		$this->_pdf->ezStream();
	}

	private function _drawTitle() {
		$title = new BurndownTitle($this->_pdf, $this->_text, $this->_margins);
		$title->draw($this->_title);
	}

	private function _drawXAxis() {
		$this->_drawXAxisLine();
		
		$xAxisSplit = $this->_calculateXAxisSplit();
		
		$start = new Point($this->_margins->left(), $this->_margins->bottom());
		$end = new Point($this->_pdf->getPageWidth() - $this->_margins->left() , $this->_margins->bottom());
		$line = new Line($start, $end);
		$splitter = new AxisSplitter($xAxisSplit, $line);
		
		$this->_drawXAxisTicks($splitter);
		$this->_drawXAxisValues($splitter);
		
		if(!$this->_hide_grid) {
			$this->_drawXAxisGrid($splitter);
		}
		
		$this->_drawXAxisLabel();
	}

	private function _drawXAxisLine() {
		// Horizontal line
		$from = new Point($this->_margins->left(), $this->_margins->bottom());
		$to = new Point($this->_pdf->getPageWidth() - $this->_margins->right(), $this->_margins->bottom());
		
		$line = new Line($from, $to);
		$this->_drawLine->draw($line, LineStyleFactory::thinContinuous());
	}

	private function _calculateXAxisSplit() {
		return ($this->_pdf->getPageWidth() - $this->_margins->right() - $this->_margins->left()) / ($this->_days - 1);
	}

	private function _drawXAxisTicks(AxisSplitter $splitter) {
		$this->_styleChanger->change($this->_pdf, LineStyleFactory::thinContinuous());
		
		$axisTicks = new DrawAxisTicks($this->_drawLine);
		$axisTicks->draw($splitter, new AxisHorizontalElements(), $this->_tick_size);
	}

	private function _drawXAxisGrid(AxisSplitter $splitter) {
		$this->_styleChanger->change($this->_pdf, LineStyleFactory::thinDashed());

		$gridSize = $this->_pdf->getPageHeight() - $this->_margins->top() - $this->_margins->bottom() - ($this->_tick_size / 2);
		$axisGrid = new DrawAxisGrid($this->_drawLine);
		$axisGrid->draw($splitter, new AxisHorizontalElements(), $gridSize);
	}

	private function _drawXAxisValues(AxisSplitter $splitter) {
		$axisLabels = new DrawAxisLabels($this->_text);
		$axisLabels->draw($splitter, new AxisHorizontalElements(), 4, 0, 1);
	}

	private function _drawYAxis() {
		$this->_drawYAxisLine();
		
		$scale = $this->_calculateYAxisProperties();
		
		$yAxisSplit = $scale->distanceBetweenTicks();
		$yPoints = $scale->numberTicks();
		$factor = $scale->pointsBetweenTicks();

		
		$start = new Point($this->_margins->left(), $this->_margins->bottom());
		$end = new Point($this->_margins->left(), $this->_pdf->getPageHeight() - $this->_margins->top());
		$line = new Line($start, $end);
		$splitter = new AxisSplitter($yAxisSplit, $line);
		
		
		$this->_drawYAxisTicks($splitter);
		$this->_drawYAxisValues($splitter, $factor);
		
		if(!$this->_hide_grid) {
			$this->_drawYAxisGrid($splitter, $yPoints);
		}
		
		$this->_drawYAxisLabel();
	}

	private function _drawYAxisLine() {
		// Vertical line
		$from = new Point($this->_margins->left(), $this->_margins->bottom());
		$to = new Point($this->_margins->left(), $this->_pdf->getPageHeight() - $this->_margins->top());
		
		$line = new Line($from, $to);
		$this->_drawLine->draw($line, LineStyleFactory::thinContinuous());
	}

	/**
	 *
	 * @return ScaleBeautifier
	 */
	private function _calculateYAxisProperties() {
		$axisSize = $this->_pdf->getPageHeight() - $this->_margins->top() - $this->_margins->bottom();
		$minSeparation = 5; // millimeters
		

		return new ScaleBeautifier($axisSize, $this->_points, $minSeparation, Scale::$BASIC);
	}

	private function _drawYAxisTicks(AxisSplitter $splitter) {
		$this->_styleChanger->change($this->_pdf, LineStyleFactory::thinContinuous());
		
		$axisTicks = new DrawAxisTicks($this->_drawLine);
		$axisTicks->draw($splitter, new AxisVerticalElements(), $this->_tick_size);
	}
	
	private function _drawYAxisGrid(AxisSplitter $splitter) {
		$this->_styleChanger->change($this->_pdf, LineStyleFactory::thinDashed());
		
		$gridSize = $this->_pdf->getPageWidth() - $this->_margins->right() - $this->_margins->left() - ($this->_tick_size / 2);
		$axisGrid = new DrawAxisGrid($this->_drawLine);
		$axisGrid->draw($splitter, new AxisVerticalElements(), $gridSize);
	}

	private function _drawYAxisValues(AxisSplitter $splitter, $factor) {
		$axisLabels = new DrawAxisLabels($this->_text);
		$axisLabels->draw($splitter, new AxisVerticalElements(), 4, 0, $factor);
	}

	private function _drawYAxisLabel() {
		$text = trim($this->_ylabel);
		if(strlen($text) > 0) {
			$size = 5;
			$position = new Point($this->_margins->left() - 7 - $size, $this->_pdf->getPageHeight() / 2);
			$this->_text->vertical($text, $size, $position, 'center');
		}
	}

	private function _drawXAxisLabel() {
		$text = trim($this->_xlabel);
		if(strlen($text) > 0) {
			$size = 5;
			$position = new Point($this->_pdf->getPageWidth() / 2, $this->_margins->bottom() - 10 - $size);
			$this->_text->horizontal($text, $size, $position, 'center');
		}
	}

	private function _drawSpeed() {
		$speed = new BurndownSpeed($this->_pdf, $this->_styleChanger, $this->_text);
		$speed->draw($this->_points, $this->_margins);
	}

	private function _drawBurndownLine() {
		$color = $this->_convertRGBToColorObject($this->_burndown_color);
		
		$upperLeft = new Point($this->_margins->left(), $this->_pdf->getPageHeight() - $this->_margins->top());
		$lowerRight = new Point($this->_pdf->getPageWidth() - $this->_margins->right(), $this->_margins->bottom());
		
		$line = new BurndownLine($this->_pdf, $this->_styleChanger);
		if($this->_chart_type == 'burnup') {
			$line->draw($color, new BurndownLineUp($upperLeft, $lowerRight));
		}
		else {
			$line->draw($color, new BurndownLineDown($upperLeft, $lowerRight));
		}
	}

	private function _convertRGBToColorObject($color) {
		$converter = new ColorConverter();
		return $converter->fromRGBToColor($color);
	}

	private function _drawAds() {
		$size = 3;
		$text = 'http://www.burndowngenerator.com';
		$position = new Point($this->_pdf->getPageWidth() - $this->_margins->right(), 10);
		$this->_text->horizontal($text, $size, $position, 'right');
	}

	private function _log() {
		$fd = fopen(dirname(__FILE__) . '/../../log.burndown.txt', 'a');
		$data = array(
			$_SERVER['REMOTE_ADDR'], 
			date('Y-m-d H:i:s'), 
			$this->_points, 
			$this->_days, 
			$this->_title, 
			($this->_hide_grid) ? 'N' : 'Y', 
			($this->_hide_speed) ? 'N' : 'Y', 
			$_SERVER['HTTP_USER_AGENT'], 
			$this->_xlabel, 
			$this->_ylabel, 
			$this->_burndown_color, 
			$this->_chart_type);
		
		@fwrite($fd, implode("\t", $data) . "\n");
		
		fclose($fd);
	}
}