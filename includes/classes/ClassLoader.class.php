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
class ClassLoader {
	
	protected $_classes = null;

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
				print 'Warning: repeated file ' . $file . "<br />\n";
			}
			else {
				$this->_classes[$file] = $path . '/' . $file;
			}
		}
	
	}

	private function _checkIsClass($file) {
		return preg_match('/\.class\.php$/', $file);
	}

	public function includeClass($classname) {
		if(isset($this->_classes[$classname])) {
			include_once($this->_classes[$classname]);
		}
	}
}