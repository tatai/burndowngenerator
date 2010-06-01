<?php
class Point {
	private $_x = null;
	private $_y = null;

	public function __construct($x, $y) {
		if(is_numeric($x)) {
			$this->_x = $x;
		}
		if(is_numeric($y)) {
			$this->_y = $y;
		}
	}

	public function x() {
		return $this->_x;
	}

	public function y() {
		return $this->_y;
	}
	
	public function isValid() {
		return (!is_null($this->_x) && !is_null($this->_y));
	}
	
	public function isEqual(Point $point) {
		return ($this->_x == $point->x() && $this->_y == $point->y());
	}
}