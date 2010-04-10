<?php
include_once(dirname(__FILE__) . '/validators/ValidatorNonEmptyString.class.php');
class Comments {
	private 
		$_recipients = null,
		$_errors = null,
		$_name = null,
		$_mail = null,
		$_comment = null
		;

	public function __construct() {
		$this->_initialize();

		$this->_recipients = 'info@burndowngenerator.com';
	}

	public function check() {
		$this->_initialize();
		if(!isset($_POST['INFO'])) {
			$this->_errors[] = 'No available data';
			return false;
		}

		$ok = true;

		$info = $_POST['INFO'];

		$ok = $ok & $this->_checkName($info['name']);
		$ok = $ok & $this->_checkEmail($info['mail']);
		$ok = $ok & $this->_checkAndCleanComment($info['comment']);

		return $ok;
	}

	private function _checkName($name) {
		$validator = new ValidatorNonEmptyString();
		$check = $validator->check($name);

		if($check == false) {
			$this->_errors[] = array(
				'field' => 'name',
				'text' => 'Name is empty'
			);
			$ok = false;
		}
		else {
			$this->_name = $name;
			$ok = true;
		}
		unset($validator);

		return $ok;
	}

	private function _checkEmail($email) {
		$check = filter_var($email, FILTER_VALIDATE_EMAIL);

		if($check == false) {
			$this->_errors[] = array(
				'field' => 'mail',
				'text' => 'Email is not valid'
			);
			$ok = false;
		}
		else {
			$this->_email = $check;
			$ok = true;
		}

		return $ok;
	}

	private function _checkAndCleanComment($comment) {
		$cleanComment = strip_tags(trim($comment));

		$validator = new ValidatorNonEmptyString();
		$check = $validator->check($cleanComment);

		if($check == false) {
			$this->_errors[] = array(
				'field' => 'comment',
				'text' => 'Comment is empty'
			);
			$ok = false;
		}
		else {
			$this->_comment = $cleanComment;
			$ok = true;
		}
		unset($validator);

		return $ok;
	}

	public function send() {
		$to = $this->_recipients;
		$subject = 'New comment from burndowngenerator.com';
		$message = $this->_comment;
		$headers = 'From: "' . $this->_name . '" <' . $this->_email . ">\r\n" .
			'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
	}

	public function response() {
		$data = array(
			'ok' => (count($this->_errors) == 0),
			'errors' => $this->_errors
		);

		return json_encode($data);
	}

	private function _initialize() {
		$this->_errors = array();

		$this->_name = null;
		$this->_mail = null;
		$this->_comment = null;
	}
}
