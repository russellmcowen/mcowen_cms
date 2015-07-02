<?php
class Redirect {
	public static function to($loc = null) {
		if ($loc) {
			if(is_numeric($loc)) {
				switch($loc) {
					case 404:
						header('HTTP/1.0 404 Not Found');
						include '../php/error/404.php';
						exit();
						break;
					case 999:
						//header('HTTP/1.0 404 Not Found');
						include '../php/error/999.php';
						exit();
						break;
					case 500:
						header('HTTP/1.0 500 Internal Server Error');
						include '../php/error/500.php';
						exit();
				}
			}
			
			header("Location: ".$loc."");
			exit();
		}
		
	}
}
?>
