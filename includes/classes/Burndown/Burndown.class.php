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
	 * @var Text
	 */
	private $_text = null;
	
	/**
	 * Delegación del cambio de estilos de línea
	 * 
	 * @var LineStyleChanger
	 */
	private $_styleChanger = null;
	
	private
		$_points = null,
		$_days = null,
		$_margins = null,
		$_tick_size = null,
		$_title = null,
		$_hide_speed = null,
		$_hide_grid = null,
		$_burndown_color = null,
		$_xlabel = null,
		$_ylabel = null,
		$_chart_type = null
		;

	public function __construct($pdf, $points, $days) {
		$this->_pdf = $pdf;
		$this->_points = $points;
		$this->_days = $days;
		$this->_text = new Text();
		$this->_styleChanger = new LineStyleChanger();

		$this->_margins = array(
			'top' => 25,
			'bottom' => 20,
			'left' => 20,
			'right' => 20
		);

		$this->_tick_size = 4;
		$this->_hide_speed = false;
		$this->_hide_grid = false;
		$this->_burndown_color = '#000000';
		$this->_chart_type = 'burndown';
	}

	public function setOptions($options) {
		foreach($options AS $k => $v) {
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
		$this->_drawXAxisTicks($xAxisSplit);
		$this->_drawXAxisValues($xAxisSplit);

		if(!$this->_hide_grid) {
			$this->_drawXAxisGrid($xAxisSplit);
		}

		$this->_drawXAxisLabel();
	}

	private function _drawXAxisLine() {
		// Horizontal line
		$this->_styleChanger->change($this->_pdf, LineStyleFactory::thinContinuous());
		$this->_pdf->line(
			$this->_margins['left'],
			$this->_margins['bottom'],
			$this->_pdf->getPageWidth() - $this->_margins['right'],
			$this->_margins['bottom']
		);
	}

	private function _calculateXAxisSplit() {
		return ($this->_pdf->getPageWidth() - $this->_margins['right'] - $this->_margins['left']) / ($this->_days - 1);
	}

	private function _drawXAxisTicks($split) {
		$this->_styleChanger->change($this->_pdf, LineStyleFactory::thinContinuous());
		for($i = 0; $i < $this->_days; $i++) {
				$this->_pdf->line(
				$this->_margins['left'] + $split * $i,
				$this->_margins['bottom'] - ($this->_tick_size / 2),
				$this->_margins['left'] + $split * $i,
				$this->_margins['bottom'] + ($this->_tick_size / 2)
			);
		}
	}
	
	private function _drawXAxisGrid($split) {
		$this->_styleChanger->change($this->_pdf, LineStyleFactory::thinDashed());
		for($i = 1; $i < $this->_days; $i++) {
			$this->_pdf->line(
				$this->_margins['left'] + $split * $i,
				$this->_margins['bottom'] + ($this->_tick_size / 2),
				$this->_margins['left'] + $split * $i,
				$this->_pdf->getPageHeight() - $this->_margins['top']
			);
		}
	}
	
	private function _drawXAxisValues($split) {
		for($i = 0; $i < $this->_days; $i++) {
			$size = 4;
			$position = new Point(
				$this->_margins['left'] + $split * $i,
				$this->_margins['bottom'] - $this->_tick_size / 2 - $size
			);
			$this->_text->horizontal($this->_pdf, $i, $size, $position, 'center');
		}
	}

	private function _drawYAxis() {
		$this->_drawYAxisLine();

		$scale = $this->_calculateYAxisProperties();
		
		$yAxisSplit = $scale->distanceBetweenTicks();
		$yPoints = $scale->numberTicks();
		$factor = $scale->pointsBetweenTicks();
		
		$this->_drawYAxisTicks($yAxisSplit, $yPoints);
		$this->_drawYAxisValues($yAxisSplit, $yPoints, $factor);

		if(!$this->_hide_grid) {
			$this->_drawYAxisGrid($yAxisSplit, $yPoints);
		}
		
		$this->_drawYAxisLabel();
	}

	private function _drawYAxisLine() {
		// Vertical line
		$this->_styleChanger->change($this->_pdf, LineStyleFactory::thinContinuous());
		$this->_pdf->line(
			$this->_margins['left'],
			$this->_margins['bottom'],
			$this->_margins['left'],
			$this->_pdf->getPageHeight() - $this->_margins['top']
		);
	}

	/**
	 *
	 * @return ScaleBeautifier
	 */
	private function _calculateYAxisProperties() {
		$axisSize = $this->_pdf->getPageHeight() - $this->_margins['top'] - $this->_margins['bottom'];
		$minSeparation = 5; // millimeters
		
		return new ScaleBeautifier($axisSize, $this->_points, $minSeparation, Scale::$BASIC);
	}

	private function _drawYAxisTicks($split, $points) {
		$this->_styleChanger->change($this->_pdf, LineStyleFactory::thinContinuous());
		for($i = 0; $i < $points; $i++) {
			$this->_pdf->line(
				$this->_margins['left'] - ($this->_tick_size / 2),
				$this->_margins['bottom'] + $split * $i,
				$this->_margins['left'] + ($this->_tick_size / 2),
				$this->_margins['bottom'] + $split * $i
			);
		}
	}

	private function _drawYAxisGrid($split, $points) {
		$this->_styleChanger->change($this->_pdf, LineStyleFactory::thinDashed());
		for($i = 1; $i < $points; $i++) {
			$this->_pdf->line(
				$this->_margins['left'] + ($this->_tick_size / 2),
				$this->_margins['bottom'] + $split * $i,
				$this->_pdf->getPageWidth() - $this->_margins['right'],
				$this->_margins['bottom'] + $split * $i
			);
		}
	}

	private function _drawYAxisValues($split, $points, $factor) {
		for($i = 0; $i < $points; $i++) {
			$size = 4;
			$text = $factor * $i;
			$position = new Point(
				$this->_margins['left'] - ($this->_tick_size / 2) - 2,
				$this->_margins['bottom'] + ($split * $i) - 1
			);
			$this->_text->horizontal($this->_pdf, $text, $size, $position, 'right');
		}
	}
	
	private function _drawYAxisLabel() {
		$text = trim($this->_ylabel);
		if(strlen($text) > 0) {
			$size = 5;
			$position = new Point(
				$this->_margins['left'] - 7 - $size,
				$this->_pdf->getPageHeight() / 2
			);
			$this->_text->vertical($this->_pdf, $text, $size, $position, 'center');
		}
	}

	private function _drawXAxisLabel() {
		$text = trim($this->_xlabel);
		if(strlen($text) > 0) {
			$size = 5;
			$position = new Point(
				$this->_pdf->getPageWidth() / 2,
				$this->_margins['bottom'] - 10 - $size
			);
			$this->_text->horizontal($this->_pdf, $text, $size, $position, 'center');
		}
	}

	private function _drawSpeed() {
		$speed = new BurndownSpeed($this->_pdf, $this->_styleChanger, $this->_text);
		$speed->draw($this->_points, $this->_margins);
	}

	private function _drawBurndownLine() {
		$color = $this->_convertRGBToColorObject($this->_burndown_color);
		$this->_styleChanger->change($this->_pdf, LineStyleFactory::thickContinuous($color));
		if($this->_chart_type == 'burndown') {
			$this->_pdf->line(
				$this->_margins['left'],
				$this->_pdf->getPageHeight() - $this->_margins['top'],
				$this->_pdf->getPageWidth() - $this->_margins['right'],
				$this->_margins['bottom']
			);
		}
		else if($this->_chart_type == 'burnup') {
			$this->_pdf->line(
				$this->_margins['left'],
				$this->_margins['bottom'],
				$this->_pdf->getPageWidth() - $this->_margins['right'],
				$this->_pdf->getPageHeight() - $this->_margins['top']
			);
		}
	}

	private function _convertRGBToColorObject($color) {
		$converter = new ColorConverter();
		return $converter->fromRGBToColor($color);
	}

	private function _drawAds() {
		$size = 3;
		$text = 'http://www.burndowngenerator.com';
		$position = new Point(
			$this->_pdf->getPageWidth() - $this->_margins['right'],
			10
		);
		$this->_text->horizontal($this->_pdf, $text, $size, $position, 'right');
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
			$this->_chart_type
		);

		@fwrite($fd, implode("\t", $data) . "\n");

		fclose($fd);
	}
}
