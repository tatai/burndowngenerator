<?php

interface IPdf {
	public function line($x1, $y1, $x2, $y2);
	public function addTextWrap($x, $y, $width, $size, $text, $just = 'left', $angle = 0);
	public function getPageWidth();
	public function getPageHeight();
	public function rectangle($x1, $y1, $x2, $y2);
	public function getTextWidth($size, $text);
	public function setStrokeColor($r, $g, $b, $force = 0);
	public function setLineStyle($width = 1, $cap = '', $join = '', $dash = '', $phase = 0);
}