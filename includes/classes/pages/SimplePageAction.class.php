<?php
class SimplePageAction {
	private $_options = null;

	public function __construct($options) {
		$this->_options = $options;
	}

	public function execute() {
		include_once(dirname(__FILE__) . '/../XTemplate.class.php');
		$xtpl = new XTemplate($this->_options['name'] . '.xtpl', dirname(__FILE__) . '/../../../xtpl');

		$xtpl->parse('main');
		$xtpl->out('main');
	}
}
