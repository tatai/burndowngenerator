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
class EntryData {
	private 
		$_post = null,
		$_errors = null,
		$_days = null,
		$_points = null,
		$_page_size = null,
		$_options = null
		;

	public function __construct() {
		$this->_errors = array();
		$this->_post = $_POST['YABOG'];
		$this->_options = array();

		$this->_initialize();
	}

	protected function _initialize() {
		$this->_checkFor['days'] = array(
			'name' => 'Days',
			'required' => true,
			'validators' => array(
				'ValidatorIntegerGreaterThanZero'
				)
			);
		$this->_checkFor['points'] = array(
			'name' => 'Points',
			'required' => true,
			'validators' => array(
				'ValidatorIntegerGreaterThanZero'
				)
			);
		$this->_checkFor['title'] = array(
			'name' => 'Title',
			'required' => true,
			'validators' => array(
				'ValidatorNonEmptyString'
				)
			);
		$this->_checkFor['hide_speed'] = array(
			'name' => 'Hide speed',
			'required' => false,
			'validators' => array(
				)
			);
		$this->_checkFor['hide_grid'] = array(
			'name' => 'Hide grid',
			'required' => false,
			'validators' => array(
				)
			);
		$this->_checkFor['burndown_color'] = array(
			'name' => 'Burndown line color',
			'required' => false,
			'validators' => array(
				'ValidatorRGBString'
				)
			);
		$this->_checkFor['page_size'] = array(
			'name' => 'Page size',
			'required' => false,
			'validators' => array(
				'ValidatorPageSize'
				)
			);
		$this->_checkFor['chart_type'] = array(
			'name' => 'Chart type',
			'required' => false,
			'validators' => array(
				'ValidatorChartType'
				)
			);
		$this->_checkFor['xlabel'] = array(
			'name' => 'X label',
			'required' => false,
			'validators' => array(
				)
			);
		$this->_checkFor['ylabel'] = array(
			'name' => 'Y label',
			'required' => false,
			'validators' => array(
				)
			);
	}

	/**
	 * Checks if data posted is correct
	 *
	 * Return an array with all errors found
	 *
	 * @return array of errors
	 * @public
	 */
	public function check() {
		foreach($this->_checkFor AS $var => $data) {
			$result = true;

			// Check if it has been set
			if($data['required'] && !isset($this->_post[$var])) {
				$this->_errors[$var][] = $data['name'] . ' is required';
				continue;
			}

			// Avoid problem with PDF Class
			$valueToCheck = '';
			if(isset($this->_post[$var])) {
				$valueToCheck = utf8_decode($this->_post[$var]);
			}

			foreach($data['validators'] AS $validatorName) {
				include_once(dirname(__FILE__) . '/validators/' . $validatorName . '.class.php');
				$validator = new $validatorName();
				$check = $validator->check($valueToCheck);

				$result = $result && $check;
				if($check == false) {
					$this->_errors[$var][] = $validator->getError($data['name']);
				}
				unset($validator);
			}

			if($result) {
				if($var == 'days') {
					$this->_days = $valueToCheck;
				}
				else if($var == 'points') {
					$this->_points = $valueToCheck;
				}
				else if($var == 'page_size') {
					$this->_page_size = $valueToCheck;
				}
				else {
					$this->_options[$var] = $valueToCheck;
				}
			}
		}

		return $this->_errors;
	}

	public function getDays() {
		return $this->_days;
	}

	public function getPoints() {
		return $this->_points;
	}

	public function getOptions() {
		return $this->_options;
	}

	public function getPageSize() {
		return $this->_page_size;
	}
}
