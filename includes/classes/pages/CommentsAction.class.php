<?php
class CommentsAction {
	public function __construct() {
	}

	public function execute() {
		include_once(dirname(__FILE__) . '/../Comments.class.php');
		$comments = new Comments();
		if($comments->check()) {
			$comments->send();
		}

		print $comments->response();
	}
}
