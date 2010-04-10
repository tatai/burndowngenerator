<?php
include_once(dirname(__FILE__) . '/XTemplate.class.php');
class MainPage {
	private $_comments = null;

	public function __construct() {
		$this->_comments = array(
			'fold' => 'close'
		);
	}

	public function setCommentsOpen() {
		$this->_comments['fold'] = 'open';
	}

	public function render() {
		$xtpl = $this->_loadTemplate();

		$xtpl->assign('COMMENTS', $this->_comments);

		$xtpl->parse('main');
		$xtpl->out('main');
	}

	public function _loadTemplate() {
		return new XTemplate('main.xtpl', dirname(__FILE__) . '/../../xtpl');
	}
}
