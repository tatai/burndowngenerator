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
class DrawAxisTicks {
	/**
	 * 
	 * @var DrawLine
	 */
	private $_draw_line = null;

	/**
	 * 
	 * @param DrawLine $drawLine
	 */
	public function __construct(DrawLine $drawLine) {
		$this->_draw_line = $drawLine;
	}

	public function draw(AxisSplitter $splitter, IAxisElements $axisElements, $tickSize) {
		$splitter->rewind();

		for($i = 0; $i < $splitter->splits(); $i++) {
			$at = $splitter->next();

			$tick = $axisElements->tick($at, $tickSize);
			$this->_draw_line->draw($tick);
		}
	}
}