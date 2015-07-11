<?php
class Email {
	private $to = null, 
			$subject = null, 
			$body = null;
	
	public function __contruct($to, $subject, $body) {
		$this->to = $to;
		$this->subject = $subject;
		$this->body = $body;
	}
	
	public static function send() {
		if ($to && $subject && $body) {
			return true;
		}
		return false;
	}
}
?>
