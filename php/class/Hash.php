<?php
class Hash {
	public static function make($s, $salt = '') {
		return hash('sha256', $s.$salt);
	}
	
	public static function passHash($pass, $salt = null) {
		if ($salt) {
			$params = array('salt'=>$salt);
			return password_hash($pass, PASSWORD_DEFAULT, $params);
		} 
		return password_hash($pass, PASSWORD_DEFAULT);
	}
	
	public static function salt($length) {
		return mcrypt_create_iv($length);
	}
	
	public static function unique() {
		return self::make(uniqid());
	}
	
}
	
?>
