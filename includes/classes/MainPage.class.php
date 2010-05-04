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
include_once(dirname(__FILE__) . '/XTemplate.class.php');
class MainPage {
	private
		$_comments = null,
		$_errors = null,
		$_data = null
		;

	public function __construct() {
		$this->_comments = array(
			'fold' => 'close'
		);

		$this->_errors = array();

		$this->_data = array(
			'burndown_color' => '#000000'
		);
	}

	public function setErrors($errors) {
		$this->_errors = $errors;
	}

	public function setData($data) {
		$this->_data = $data;
	}

	public function setCommentsOpen() {
		$this->_comments['fold'] = 'open';
	}

	public function render() {
		$xtpl = $this->_loadTemplate();

		$xtpl->assign('COMMENTS', $this->_comments);

		$this->_renderData($xtpl);
		$this->_renderErrors($xtpl);

		if($GLOBALS['config']['ads'] == true) {
			$xtpl->parse('main.ads');
		}

		$xtpl->parse('main');
		$xtpl->out('main');
	}

	private function _loadTemplate() {
		return new XTemplate('main.xtpl', dirname(__FILE__) . '/../../xtpl');
	}

	private function _renderErrors($xtpl) {
		foreach($this->_errors AS $name => $errors) {
			$error = array(
				'text' => implode('. ', $errors)
			);
			$xtpl->assign('ERROR', $error);
			$xtpl->parse('main.error_' . $name);
		}
	}

	private function _renderData($xtpl) {
		$this->_changeSelectors();

		$this->_parseColorRainbow();

		$xtpl->assign('YABOG', $this->_data);
	}

	private function _parseColorRainbow() {
		if(preg_match('/^#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/', strtolower($this->_data['burndown_color']), $matches)) {
			$this->_data['burndown_color_parsed'] = '[' . $matches[1] . ',' . $matches[2] . ',' . $matches[3] . ']';
		}
	}

	private function _changeSelectors() {
		$checks = array(
			'hide_grid',
			'hide_speed'
		);

		foreach($checks AS $check) {
			if(isset($this->_data[$check]) && $this->_data[$check]) {
				$this->_data[$check] = 'checked="checked"';
			}
		}
	}
}
