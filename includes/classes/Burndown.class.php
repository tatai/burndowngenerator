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
	
	private
		$_points = null,
		$_days = null,
		$_margins = null,
		$_tick_size = null,
		$_tick_steps = null,
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

		$this->_margins = array(
			'top' => 25,
			'bottom' => 20,
			'left' => 20,
			'right' => 20
		);

		$this->_tick_steps = array(
			0,  // Mandatory to get a correct do-while code
			1,
			2,
			3,
			5,
			10,
			15,
			20,
			25,
			50,
			75,
			100,
			125,
			150,
			200,
			250,
			300,
			400,
			500,
			1000,
			1500,
			2000,
			2500,
			5000,
			10000,
			15000,
			20000,
			25000,
			50000,
			100000
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
		$text = 'Burndown online generator';
		if($this->_title) {
			$text = $this->_title;
		}

		$size = 10;
		$width = $this->_pdf->getTextWidth($size, $text);

		$this->_pdf->addTextWrap(
			$this->_pdf->getPageWidth() / 2 - $width / 2,
			$this->_pdf->getPageHeight() - $this->_margins['top'] + $size / 2,
			$width,
			$size,
			$text
		);
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
		$this->_setLineThinContinuous();
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
		for($i = 0; $i < $this->_days; $i++) {
			$this->_setLineThinContinuous();
			$this->_pdf->line(
				$this->_margins['left'] + $split * $i,
				$this->_margins['bottom'] - ($this->_tick_size / 2),
				$this->_margins['left'] + $split * $i,
				$this->_margins['bottom'] + ($this->_tick_size / 2)
			);
		}
	}
	
	private function _drawXAxisGrid($split) {
		for($i = 1; $i < $this->_days; $i++) {
			$this->_setLineThinDashed();
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
			$width = $this->_pdf->getTextWidth(4, $i);

			$this->_pdf->addTextWrap(
				$this->_margins['left'] + $split * $i - ($width / 2),
				$this->_margins['bottom'] - $this->_tick_size / 2 - 4,
				$width,
				4,
				$i
			);
		}
	}

	private function _drawYAxis() {
		$this->_drawYAxisLine();

		list($yAxisSplit, $yPoints, $factor) = $this->_calculateYAxisProperties();
		$this->_drawYAxisTicks($yAxisSplit, $yPoints);
		$this->_drawYAxisValues($yAxisSplit, $yPoints, $factor);

		if(!$this->_hide_grid) {
			$this->_drawYAxisGrid($yAxisSplit, $yPoints);
		}
		
		$this->_drawYAxisLabel();
	}

	private function _drawYAxisLine() {
		// Vertical line
		$this->_setLineThinContinuous();
		$this->_pdf->line(
			$this->_margins['left'],
			$this->_margins['bottom'],
			$this->_margins['left'],
			$this->_pdf->getPageHeight() - $this->_margins['top']
		);
	}

	private function _calculateYAxisProperties() {
		$resultingPoints = $this->_points;
		do {
			$factor = next($this->_tick_steps);

			$resultingPoints = $this->_points / $factor + 1;
			$split = ($this->_pdf->getPageHeight() - $this->_margins['top'] - $this->_margins['bottom']) / ($resultingPoints - 1);

			// Floor result yo get an integer number of points
			$resultingPoints = floor($resultingPoints);
		} while($split < 5);

		return array(
			$split,
			$resultingPoints,
			$factor
		);
	}

	private function _drawYAxisTicks($split, $points) {
		for($i = 0; $i < $points; $i++) {
			$this->_setLineThinContinuous();
			$this->_pdf->line(
				$this->_margins['left'] - ($this->_tick_size / 2),
				$this->_margins['bottom'] + $split * $i,
				$this->_margins['left'] + ($this->_tick_size / 2),
				$this->_margins['bottom'] + $split * $i
			);
		}
	}

	private function _drawYAxisGrid($split, $points) {
		for($i = 1; $i < $points; $i++) {
			$this->_setLineThinDashed();
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
			$width = $this->_pdf->getTextWidth(4, $factor * $i);

			$this->_pdf->addTextWrap(
				$this->_margins['left'] - ($this->_tick_size / 2) - $width - 2,
				$this->_margins['bottom'] + ($split * $i) - 1,
				$width,
				4,
				$factor * $i,
				'left'
			);
		}
	}
	
	private function _drawYAxisLabel() {
		$text = trim($this->_ylabel);
		if(strlen($text) > 0) {
			$size = 5;
			$width = $this->_pdf->getTextWidth($size, $text);
	
			$this->_pdf->addTextWrap(
				$this->_margins['left'] - 7 - $size,
				$this->_pdf->getPageHeight() / 2 - $width / 2,
				$width,
				$size,
				$text,
				'center',
				270
			);
		}
	}

	private function _drawXAxisLabel() {
		$text = trim($this->_xlabel);
		if(strlen($text) > 0) {
			$size = 5;
			$width = $this->_pdf->getTextWidth($size, $text);
	
			$this->_pdf->addTextWrap(
				$this->_pdf->getPageWidth() / 2 - $width / 2,
				$this->_margins['bottom'] - 10 - $size,
				$width,
				$size,
				$text,
				'center',
				0
			);
		}
	}

	private function _drawSpeed() {
		$width = $this->_pdf->getTextWidth(7, $this->_points);

		$this->_pdf->addTextWrap(
			$this->_margins['left'] - $width,
			$this->_pdf->getPageHeight() - $this->_margins['top'] + 3,
			$width,
			7,
			$this->_points
		);

		$this->_setLineThinContinuous();
		$this->_pdf->rectangle(
			$this->_margins['left'] - $width - 1,
			$this->_pdf->getPageHeight() - $this->_margins['top'] + 2,
			$this->_margins['left'] + 1,
			$this->_pdf->getPageHeight() - $this->_margins['top'] + 9
		);
	}

	private function _drawBurndownLine() {
		$color = $this->_convertRGBToColorObject($this->_burndown_color);
		$this->_setLineThickContinuous($color);
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
		if(is_null($color) || !preg_match('/^#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/', strtolower($this->_burndown_color), $c)) {
			return new Color(new Decimal(0), new Decimal(0), new Decimal(0));
		}

		return new Color(
			new Hexadecimal($c[1]),
			new Hexadecimal($c[2]),
			new Hexadecimal($c[3])
		);
	}

	private function _drawAds() {
		$url = 'http://www.burndowngenerator.com';
		$size = 3;
		$width = $this->_pdf->getTextWidth(3, $url);

		$this->_pdf->addTextWrap(
			$this->_pdf->getPageWidth() - $this->_margins['right'] - $width,
			10,
			$width,
			3,
			$url
		);
	}

	private function _setLineThinContinuous() {
		$color = new Color(new Decimal(0), new Decimal(0), new Decimal(0));
		$stroke = new LineStroke(1, new LineStyleContinuous());
		
		$this->_setLineStyleTo($color, $stroke);
	}
	
	private function _setLineThickContinuous(Color $color) {
		$stroke = new LineStroke(5, new LineStyleContinuous());

		$this->_setLineStyleTo($color, $stroke);
	}

	private function _setLineThinDashed() {
		$color = new Color(new Decimal(200), new Decimal(200), new Decimal(200));
		$stroke = new LineStroke(1, new LineStyleDashed(5));
		
		$this->_setLineStyleTo($color, $stroke);
	}

	private function _setLineStyleTo(Color $color, LineStroke $stroke) {
		$styleChanger = new LineStyleChanger();
		$styleChanger->change($this->_pdf, $color, $stroke);
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
