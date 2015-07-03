<div id="content">
	<?php 
	if(isset($page)) {
		if(file_exists('content/'.$page.'.php')) {
			include_once 'content/'.$page.'.php';
		} else {
			Redirect::to(404);
		}
	} else {
		include_once 'content/home.php';
	} ?>
</div>