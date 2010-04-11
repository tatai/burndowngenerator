<?php
include_once(dirname(__FILE__) . '/XTemplate.class.php');
class MainPage {
	private
		$_comments = null,
		$_errors = null
		;

	public function __construct() {
		$this->_comments = array(
			'fold' => 'close'
		);

		$this->_errors = array();
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
		$xtpl->assign('YABOG', $this->_data);
	}
}
