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
class BurndownYAxis {

	public function draw(MetricsPdf $pdf, BurndownMargins $margins, BurndownAxis $axis, Configuration $config) {
		list($yAxisSplit, $factor) = $this->_getSplitAndFactor($pdf, $margins, $config);
		
		$start = new Point($margins->left(), $margins->bottom());
		$end = new Point($margins->left(), $pdf->getPageHeight() - $margins->top());
		$line = new Line($start, $end);
		
		$splitter = new AxisSplitter($yAxisSplit, $line);
		
		$axisElements = new AxisVerticalElements(4, 0, $factor, new Point($margins->left() - 12, $pdf->getPageHeight() / 2));
		
		$gridLineSize = $pdf->getPageWidth() - $margins->right() - $margins->left() - ($config->get('tick_size') / 2);
		
		$axis->draw($splitter, $axisElements, $config->get('ylabel'), $config->get('tick_size'), !$config->get('hide_grid'), $gridLineSize);
	}

	private function _getSplitAndFactor(MetricsPdf $pdf, BurndownMargins $margins, Configuration $config) {
		$axisSize = $pdf->getPageHeight() - $margins->top() - $margins->bottom();
		$minSeparation = 5; // millimeters
		

		$scale = new ScaleBeautifier($axisSize, $config->get('points'), $minSeparation, Scale::$BASIC);
		
		return array(
			$scale->distanceBetweenTicks(), 
			$scale->pointsBetweenTicks());
	}
}