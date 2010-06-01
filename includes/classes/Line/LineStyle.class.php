<?php

class LineStyle {
	/**
	 * 
	 * @var Color
	 */
	private $_color = null;
	
	/**
	 * 
	 * @var LineStroke
	 */
	private $_stroke = null;

	public function __construct(Color $color, LineStroke $stroke) {
		$this->_color = $color;
		$this->_stroke = $stroke;
	}
	
	public function getColor() {
		return $this->_color;
	}
	
	public function getStroke() {
		return $this->_stroke;
	}
}