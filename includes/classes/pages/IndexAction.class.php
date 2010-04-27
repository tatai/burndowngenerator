<?php
class IndexAction {
	private $_action = null;

	public function __construct($options) {
		$this->_action = $options['action'];
	}

	public function execute() {
		include_once(dirname(__FILE__) . '/../MainPage.class.php');

		$action = 'main';
		switch($this->_action) {
			case 'burndown':
				$action = $this->_action;
				break;
		}

		$errors = array();
		$data = array();

		// First lets check out if data to draw burndown is available
		if($action == 'burndown') {
			include(dirname(__FILE__) . '/../EntryData.class.php');
			$entry = new EntryData();
			$errors = $entry->check();

			if(count($errors)) {
				$action = 'main_with_input';
				$data = array_merge(
					array('points' => $entry->getPoints()),
					array('days' => $entry->getDays()),
					array('page_size' => $entry->getPageSize()),
					$entry->getOptions()
				);
			}
			else {
				$action = 'render_burndown';
			}
		}

		if($action == 'main' || $action == 'main_with_input') {
			$main = new MainPage();

			if($action == 'main_with_input') {
				$main->setErrors($errors);
				$main->setData($data);
			}

			$main->render();
		}
		else if($action == 'render_burndown') {
			header('Cache-Control: no-store, no-cache, must-revalidate');
			header('Cache-Control: post-check=0, pre-check=0', false);
			header('Pragma: no-cache');

			include(dirname(__FILE__) . '/../MetricsPdf.class.php');
			include(dirname(__FILE__) . '/../Burndown.class.php');

			$pdf = new MetricsPdf($entry->getPageSize(), 'landscape');

			$burndown = new Burndown($pdf, $entry->getPoints(), $entry->getDays());
			$burndown->setOptions($entry->getOptions());
			$burndown->output();
		}
	}
}
