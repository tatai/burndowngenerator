<?php
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
			if($this->_data[$check]) {
				$this->_data[$check] = 'checked="checked"';
			}
		}
	}
}
