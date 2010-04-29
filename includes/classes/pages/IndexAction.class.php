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
