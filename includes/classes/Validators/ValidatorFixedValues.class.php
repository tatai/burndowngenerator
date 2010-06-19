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
include_once(dirname(__FILE__) . '/ValidatorBase.class.php');
abstract class ValidatorFixedValues extends ValidatorBase {
	protected $_values = null;

	public function __construct() {
		parent::__construct();

		$this->_defineValues();

		return $this;
	}

	abstract protected function _defineValues();

	public function check($value) {
		$this->_error = null;

		if(isset($this->_values[$value])) {
			return true;
		}

		$this->_error = '%%name%% is not valid';
		return false;
	}
}
