<?php
	class Session {
		public static function exists($value) { return (isset($_SESSION[$value])) ? true : false; }

		public static function get($value) { return $_SESSION[$value]; }
		
		public static function put($name, $value) { return $_SESSION[$name] = $value; }
				
		public static function delete($name) {
			if(self::exists($name)) {
				unset($_SESSION[$name]);
				return true;
			}
			return false;
		}
	}
	
?>
