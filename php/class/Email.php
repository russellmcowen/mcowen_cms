<?php
class Email {
	
	public function __contruct() {
		
	}
	public static function send($to = null, $subject = null, $body = null) {
		if(!$to || !$subject || !$body) {
			return false;
		}
		mail($to,$subject,$body);
		return true;
	}
}
?>
