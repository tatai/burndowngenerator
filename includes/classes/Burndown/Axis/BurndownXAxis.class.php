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
class BurndownXAxis {

	public function draw(IPdf $pdf, BurndownMargins $margins, BurndownAxis $axis, Configuration $config) {
		list($xAxisSplit, $factor) = $this->_getSplitAndFactor($pdf, $margins, $config);
		
		$start = new Point($margins->left(), $margins->bottom());
		$end = new Point($pdf->getPageWidth() - $margins->left(), $margins->bottom());
		$line = new Line($start, $end);

		$splitter = new AxisSplitter($xAxisSplit, $line);
		
		$axisElements = new AxisHorizontalElements(4, 0, $factor, new Point($pdf->getPageWidth() / 2, $margins->bottom() - 14));
		
		$gridLineSize = $pdf->getPageHeight() - $margins->top() - $margins->bottom();
		
		$axis->draw($splitter, $axisElements, $config->get('xlabel'), $config->get('tick_size'), !$config->get('hide_grid'), $gridLineSize);
	}

	private function _getSplitAndFactor(IPdf $pdf, BurndownMargins $margins, Configuration $config) {
		return array(
			($pdf->getPageWidth() - $margins->right() - $margins->left()) / ($config->get('days') - 1), 
			1);
	}
}