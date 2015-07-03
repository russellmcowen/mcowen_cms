<?php
class Redirect {
	public static function to($loc = null) {
		if ($loc) {
			if(is_numeric($loc)) {
				switch($loc) {
					case 404:
						header('HTTP/1.0 404 Not Found');
						include '../php/error/404.php';
						break;
					case 999:
						//header('HTTP/1.0 404 Not Found');
						include '../php/error/999.php';
						break;
					case 500:
						header('HTTP/1.0 500 Internal Server Error');
						include '../php/error/500.php';
						break;
				}
			} else {
				header("Location: ".$loc."");
				exit();
			}
		}
	}
}
?>
