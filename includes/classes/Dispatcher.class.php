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
class Dispatcher {
	public function __construct() {
	}

	public function dispatch() {
		$name = $this->_extractPagename();
		return $this->_route($name);
	}

	protected function _extractPagename() {
		$url = $_SERVER['REQUEST_URI'];
		if(preg_match('/^\/([^\/]+)$/', $url, $matches)) {
			return $matches[1];
		}

		return '';
	}
	
	private function getUrl() {
		return array_shift(explode('?', $_SERVER['REQUEST_URI']));
	}


	protected function _route($name) {
		switch($name) {
			case 'instructions':
			case 'changelog':
			case 'roadmap':
				$action = array(
					'program' => 'SimplePageAction',
					'params' => array('name' => $name)
				);
				break;
			case 'index':
			case 'burndown':
			case '':
				$action = array(
					'program' => 'IndexAction',
					'params' => array('action' => $name)
				);
				break;
			case 'comment':
				$action = array(
					'program' => 'CommentsAction',
					'params' => array()
				);
				break;
			default:
				$action = null;
				break;
		}

		return $action;
	}
}
