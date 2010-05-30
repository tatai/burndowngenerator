<?php
class ClassLoader {
	
	private $_classes = null;

	public function __construct() {
	}

	public function addPath($path) {
		if(file_exists($path)) {
			$this->_addFilesInPath($path);
		}
	}

	private function _addFilesInPath($directory) {
		$dir = opendir($directory);
		
		if(!$dir) {
			return false;
		}
		
		while(($file = readdir($dir)) !== false) {
			if($this->_checkNameIsValid($file)) {
				$this->_dispatchFileOrDirectory($directory, $file);
			}
		}
		
		closedir($dir);
	}

	private function _checkNameIsValid($file) {
		return ($file != '.' && $file != '..');
	}

	private function _dispatchFileOrDirectory($path, $file) {
		if(is_dir($path . '/' . $file)) {
			$this->_addFilesInPath($path . '/' . $file);
		}
		else {
			$this->_addFile($path, $file);
		}
	}

	private function _addFile($path, $file) {
		if($this->_checkIsClass($file)) {
			if(isset($this->_classes[$file])) {
				print 'Warning: repeated file ' . $file . '<br />';
			}
			else {
				$this->_classes[$file] = $path . '/' . $file;
			}
		}
	
	}

	private function _checkIsClass($file) {
		return !(preg_match('/\.class\.php$/', $file) === false);
	}

	public function includeClass($classname) {
		if(isset($this->_classes[$classname])) {
			include_once($this->_classes[$classname]);
		}
	}
}