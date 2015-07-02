<?php

	class Input {
		public static function exists($type = 'post', $value = null) {
			switch($type) {
				case 'post':
					if ($value) {
						return (isset($_POST[$value])) ? true : false;		
					}
					return (!empty($_POST)) ? true : false;
					break;
				case 'get':
					if ($value) {
						return (isset($_GET[$value])) ? true : false;		
					}
					return (!empty($_GET)) ? true : false;
					break;
				default:
					return false;
					break;
			}
		}
		
		public static function get($item) {
			if (isset($_POST[$item])) {
				return $_POST[$item];
			} else if(isset($_GET[$item])) {
				return $_GET[$item];
			}
			return '';
		}
		
//		public static function post($item) { if(isset($_POST[$item])) { return $_POST[$item]; } return false; }
//		public static functionn get($item) { if(isset($_GET[$item])) { return $_GET[$item]; } return false; }
		
	}
	
?>
