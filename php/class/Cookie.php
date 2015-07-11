<?php
class Cookie {
	public static function exists($name) { return (isset($_COOKIE[$name])) ? true : false; }
	
	public static function get($name) { return filter_input(INPUT_COOKIE, $name); }
	
	public static function put($name, $value, $expires) {
		if(setcookie($name, $value, time() + $expires, '/')) {
			return true;
		}
		return false;
	}
	
	public static function delete($name) {
		if(self::put($name, '', time() - 1)) {
			return true;
		}
		return false;
	}
}
?>
