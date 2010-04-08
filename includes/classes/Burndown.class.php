<?php
class Burndown {
	private
		$_pdf = null,
		$_points = null,
		$_days = null,
		$_margins = null,
		$_tick_size = null,
		$_tick_steps = null
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
	}

	public function output() {
		$this->_drawTitle();
		$this->_drawXAxis();
		$this->_drawYAxis();
		$this->_drawBurndown();

		$this->_pdf->ezStream();
	}

	private function _drawTitle() {
		$text = 'Burndown online generator';
		$size = 10;
		$width = $this->_pdf->getTextWidth($size, $text);

		$this->_pdf->addTextWrap(
			297 / 2 - $width / 2,
			210 - $this->_margins['top'] + $size / 2,
			$width,
			$size,
			$text
		);
	}

	private function _drawXAxis() {
		// Horizontal line
		$this->_setLineThinContinuous();
		$this->_pdf->line(
			$this->_margins['left'],
			$this->_margins['bottom'],
			297 - $this->_margins['right'],
			$this->_margins['bottom']
		);

		// Ticks
		$split = (297 - $this->_margins['right'] - $this->_margins['left']) / ($this->_days - 1);
		for($i = 0; $i < $this->_points; $i++) {
			$this->_setLineThinContinuous();
			$this->_pdf->line(
				$this->_margins['left'] + $split * $i,
				$this->_margins['bottom'] - ($this->_tick_size / 2),
				$this->_margins['left'] + $split * $i,
				$this->_margins['bottom'] + ($this->_tick_size / 2)
			);

			if($i > 0) {
				$this->_setLineThinDashed();
				$this->_pdf->line(
					$this->_margins['left'] + $split * $i,
					$this->_margins['bottom'] + ($this->_tick_size / 2),
					$this->_margins['left'] + $split * $i,
					210 - $this->_margins['top']
				);
			}

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
		// Vertical line
		$this->_setLineThinContinuous();
		$this->_pdf->line(
			$this->_margins['left'],
			$this->_margins['bottom'],
			$this->_margins['left'],
			210 - $this->_margins['top']
		);

		// Ticks
		$resultingPoints = $this->_points;
		do {
			$factor = next($this->_tick_steps);

			$resultingPoints = $this->_points / $factor + 1;
			$split = (210 - $this->_margins['top'] - $this->_margins['bottom']) / ($resultingPoints - 1);

			// Floor result yo get an integer number of points
			$resultingPoints = floor($resultingPoints);
		} while($split < 5);

		for($i = 0; $i < $resultingPoints; $i++) {
			$this->_setLineThinContinuous();
			$this->_pdf->line(
				$this->_margins['left'] - ($this->_tick_size / 2),
				$this->_margins['bottom'] + $split * $i,
				$this->_margins['left'] + ($this->_tick_size / 2),
				$this->_margins['bottom'] + $split * $i
			);

			if($i > 0) {
				$this->_setLineThinDashed();
				$this->_pdf->line(
					$this->_margins['left'] + ($this->_tick_size / 2),
					$this->_margins['bottom'] + $split * $i,
					297 - $this->_margins['right'],
					$this->_margins['bottom'] + $split * $i
				);
			}

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

		$this->_drawSpeed();
	}

	private function _drawSpeed() {
		$width = $this->_pdf->getTextWidth(7, $this->_points);

		$this->_pdf->addTextWrap(
			$this->_margins['left'] - $width,
			210 - $this->_margins['top'] + 3,
			$width,
			7,
			$this->_points
		);

		$this->_setLineThinContinuous();
		$this->_pdf->line(
			$this->_margins['left'] - $width - 1,
			210 - $this->_margins['top'] + 2,
			$this->_margins['left'] + 1,
			210 - $this->_margins['top'] + 2
		);
		$this->_pdf->line(
			$this->_margins['left'] + 1,
			210 - $this->_margins['top'] + 2,
			$this->_margins['left'] + 1,
			210 - $this->_margins['top'] + 9
		);
		$this->_pdf->line(
			$this->_margins['left'] + 1,
			210 - $this->_margins['top'] + 9,
			$this->_margins['left'] - $width - 1,
			210 - $this->_margins['top'] + 9
		);
		$this->_pdf->line(
			$this->_margins['left'] - $width - 1,
			210 - $this->_margins['top'] + 9,
			$this->_margins['left'] - $width - 1,
			210 - $this->_margins['top'] + 2
		);
	}

	private function _drawBurndown() {
		$this->_setLineThickContinuous();
		$this->_pdf->line(
			$this->_margins['left'],
			210 - $this->_margins['top'],
			297 - $this->_margins['right'],
			$this->_margins['bottom']
		);
	}

	private function _setLineThinContinuous() {
		$this->_pdf->setStrokeColor(0, 0, 0);
		$this->_pdf->setLineStyle(1, '', '', array(1,0));
	}

	private function _setLineThickContinuous() {
		$this->_pdf->setStrokeColor(0, 0, 0);
		$this->_pdf->setLineStyle(5, '', '', array(5,0));
	}

	private function _setLineThinDashed() {
		$this->_pdf->setStrokeColor(0.8, 0.8, 0.8);
		$this->_pdf->setLineStyle(1, '', '', array(5));
	}
}
