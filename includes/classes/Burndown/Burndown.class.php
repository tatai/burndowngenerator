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
	
	/**
	 * 
	 * @var Configuration
	 */
	private $_config = null;
	
	public function __construct($pdf, $points, $days) {
		$this->_config = new Configuration();

		$this->_pdf = $pdf;
		$this->_config->set('points', $points);
		$this->_config->set('days', $days);

		$this->_config->set('tick_size', 4);
		$this->_config->set('hide_speed', false);
		$this->_config->set('hide_grid', false);
		$this->_config->set('burndown_color', '#000000');
		$this->_config->set('chart_type', 'burndown');

		$this->_text = new DrawText($this->_pdf);
		$this->_styleChanger = new LineStyleChanger();
		$this->_drawLine = new DrawLine($this->_pdf, $this->_styleChanger);
		
		$this->_margins = new BurndownMargins(20, 25, 20, 20);
	}

	public function setOptions($options) {
		foreach($options as $k => $v) {
			$this->_config->set($k, $v);
		}
	}

	public function output() {
		$this->_log();
		
		$this->_drawTitle();
		
		$this->_drawXAxis();
		$this->_drawYAxis();
		
		$this->_drawBurndownLine();
		
		if(!$this->_config->get('hide_speed')) {
			$this->_drawSpeed();
		}
		
		$this->_drawAds();
		
		$this->_pdf->ezStream();
	}

	private function _drawTitle() {
		$title = new BurndownTitle($this->_pdf, $this->_text, $this->_margins);
		$title->draw($this->_config->get('title'));
	}

	private function _drawXAxis() {
		list($xAxisSplit, $factor) = $this->_getXSplitAndFactor();
		
		$start = new Point($this->_margins->left(), $this->_margins->bottom());
		$end = new Point($this->_pdf->getPageWidth() - $this->_margins->left(), $this->_margins->bottom());
		$line = new Line($start, $end);

		$splitter = new AxisSplitter($xAxisSplit, $line);
		
		$axisElements = new AxisHorizontalElements(4, 0, $factor);
		
		$gridLineSize = $this->_pdf->getPageHeight() - $this->_margins->top() - $this->_margins->bottom();
		
		$axis = new BurndownAxis($this->_pdf, $this->_margins, $this->_drawLine, $this->_text, $this->_styleChanger);
		$axis->draw($splitter, $axisElements, $this->_config->get('tick_size'), $this->_config->get('xlabel'), !$this->_config->get('hide_grid'), $gridLineSize);
	}

	private function _getXSplitAndFactor() {
		return array(
			($this->_pdf->getPageWidth() - $this->_margins->right() - $this->_margins->left()) / ($this->_config->get('days') - 1),
			1
		);
	}

	private function _drawYAxis() {
		list($yAxisSplit, $factor) = $this->_getYSplitAndFactor();
		
		$start = new Point($this->_margins->left(), $this->_margins->bottom());
		$end = new Point($this->_margins->left(), $this->_pdf->getPageHeight() - $this->_margins->top());
		$line = new Line($start, $end);

		$splitter = new AxisSplitter($yAxisSplit, $line);
		
		$axisElements = new AxisVerticalElements(4, 0, $factor);
		
		$gridLineSize = $this->_pdf->getPageWidth() - $this->_margins->right() - $this->_margins->left() - ($this->_config->get('tick_size') / 2);
		
		$axis = new BurndownAxis($this->_pdf, $this->_margins, $this->_drawLine, $this->_text, $this->_styleChanger);
		$axis->draw($splitter, $axisElements, $this->_config->get('tick_size'), $this->_config->get('ylabel'), !$this->_config->get('hide_grid'), $gridLineSize);
	}
	
	private function _getYSplitAndFactor() {
		$axisSize = $this->_pdf->getPageHeight() - $this->_margins->top() - $this->_margins->bottom();
		$minSeparation = 5; // millimeters

		$scale = new ScaleBeautifier($axisSize, $this->_config->get('points'), $minSeparation, Scale::$BASIC);
		
		return array(
			$scale->distanceBetweenTicks(),
			$scale->pointsBetweenTicks()
		);
	}

	private function _drawSpeed() {
		$speed = new BurndownSpeed($this->_pdf, $this->_styleChanger, $this->_text);
		$speed->draw($this->_config->get('points'), $this->_margins);
	}

	private function _drawBurndownLine() {
		$color = $this->_convertRGBToColorObject($this->_config->get('burndown_color'));
		
		$upperLeft = new Point($this->_margins->left(), $this->_pdf->getPageHeight() - $this->_margins->top());
		$lowerRight = new Point($this->_pdf->getPageWidth() - $this->_margins->right(), $this->_margins->bottom());
		
		$line = new BurndownLine($this->_pdf, $this->_styleChanger);
		if($this->_config->get('chart_type') == 'burnup') {
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
			$this->_config->get('points'), 
			$this->_config->get('days'), 
			$this->_config->get('title'), 
			($this->_config->get('hide_grid')) ? 'N' : 'Y', 
			($this->_config->get('hide_speed')) ? 'N' : 'Y', 
			$_SERVER['HTTP_USER_AGENT'], 
			$this->_config->get('xlabel'), 
			$this->_config->get('ylabel'), 
			$this->_config->get('burndown_color'), 
			$this->_config->get('chart_type'));
		
		@fwrite($fd, implode("\t", $data) . "\n");
		
		fclose($fd);
	}
}
