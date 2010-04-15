<?php
class Dispatcher {
	public function __construct() {
	}

	public function dispatch() {
		$name = $this->_extractPagename();

		return $this->_decide($name);
	}

	protected function _extractPagename() {
		$url = $_SERVER['SCRIPT_URL'];

		if($url == '') {
			return 'index';
		}
		else if($url == '/') {
			return 'index';
		}
		else if($url == '/index.php') {
			return 'index';
		}
		else if(preg_match('/^\/index.php\/([^\/]+)$/', $url, $matches)) {
			return $matches[1];
		}
		else if(preg_match('/^\/([^\/]+)$/', $url, $matches)) {
			return $matches[1];
		}

		return null;
	}

	protected function _decide($name) {
		switch($name) {
			case 'instructions':
			case 'changelog':
				$action = array(
					'program' => 'SimplePageAction',
					'params' => array('name' => $name)
				);
				break;
			case 'index':
			case 'burndown':
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
