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
		
		public static function post($item) {
			if(self::exists('post', $item)) {
				return filter_input(INPUT_POST, $item, FILTER_SANITIZE_SPECIAL_CHARS);
			}
			return false;
		}
		
		public static function get($item) {
			if(self::exists('get', $item)) {
				return filter_input(INPUT_GET, $item, FILTER_SANITIZE_SPECIAL_CHARS);
			}
			return false;
		}
	}
	
?>
