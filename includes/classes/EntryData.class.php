<?php
class EntryData {
	private 
		$_post = null,
		$_errors = null,
		$_days = null,
		$_points = null,
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
			$valueToCheck = utf8_decode($this->_post[$var]);

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
}
