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
class ScaleBeautifier {
	private $_points_between_ticks = null;
	private $_number_ticks = null;
	private $_distance_between_ticks = null;

	public function __construct($axisSize, $maxPointsToDraw, $minSizeBetweenSteps, array $scaleRange) {
		$this->_calculate($axisSize, $maxPointsToDraw, $minSizeBetweenSteps, $scaleRange);
	}
	
	public function _calculate($axisSize, $maxPointsToDraw, $minSizeBetweenSteps, $scale) {
		reset($scale);
		do {
			$separationDistance = next($scale);
			//print 'separationDistance: ' . $separationDistance . "\n";
			
			$pointsPerSplit = $maxPointsToDraw / $separationDistance;
			//print 'pointsPerSplit: ' . $pointsPerSplit . "\n";
			
			$split = $axisSize / $pointsPerSplit;
			//print 'split: ' . $split . "\n";
		} while($split < $minSizeBetweenSteps);
		
		$this->_points_between_ticks = $separationDistance;
		$this->_number_ticks = floor($maxPointsToDraw / $separationDistance + 1);
		$this->_distance_between_ticks = $axisSize / ($maxPointsToDraw / $separationDistance);
	}
	
	public function pointsBetweenTicks() {
		return $this->_points_between_ticks;
	}
	
	public function numberTicks() {
		return $this->_number_ticks;
	}
	
	public function distanceBetweenTicks() {
		return $this->_distance_between_ticks;
	}
}