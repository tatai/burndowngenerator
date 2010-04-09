<?php
include(dirname(__FILE__) . '/../pdf/class.ezpdf.php');

class MetricsPdf extends Cezpdf {
	protected $_ratio = null;

	public function __construct($paper, $orientation) {
		parent::__construct($paper, $orientation);

		$this->_ratio = 595.28 / 210;
	}

	protected function _convert($value) {
		return $value * $this->_ratio;
	}

	public function line($x1, $y1, $x2, $y2) {
		return parent::line($this->_convert($x1), $this->_convert($y1), $this->_convert($x2), $this->_convert($y2));
	}

	public function addTextWrap($x, $y, $width, $size, $text, $just = 'left', $angle = 0) {
		return parent::addTextWrap($this->_convert($x), $this->_convert($y), $this->_convert($width), $this->_convert($size), $text, $just, $angle);
	}

	public function getPageWidth() {
		return round($this->ez['pageWidth'] / 72 * 2.54 * 10);
	}

	public function getPageHeight() {
		return round($this->ez['pageHeight'] / 72 * 2.54 * 10);
	}

	public function rectangle($x1, $y1, $x2, $y2) {
		$this->line($x1, $y1, $x2, $y1);
		$this->line($x2, $y1, $x2, $y2);
		$this->line($x2, $y2, $x1, $y2);
		$this->line($x1, $y2, $x1, $y1);
	}
}
