<?php
	session_start();
	require_once"conf.php";
	require_once'../php/function/sanitize.php';
	try {
		spl_autoload_register(function($class) { require_once "../php/class/".$class.".php"; });
	} catch (Exception $e) { Redirect::to(500); }
	
	if(Input::get('p')) {
		$page = Input::get('p');
		$subpage = null;
		if(strpos($page, '/')) {
			$temp = explode('/', $page);
			$page = $temp[0];
			$subpage = array();
			$subpage = array_shift($temp);
		} 
	} else { $page = "home"; }
	
	// REMEMBER ME
	if(Cookie::exists(Config::get('remember/cookie')) && !Session::exists(Config::get('session/session_name'))) {
		$hash = Cookie::get(Config::get('remember/cookie'));
		$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));
		if($hashCheck->count()) {
			$user = new User($hashCheck->first()->user_id);
			$user->login();
		}
	}
	
	$user = new User();

		require_once 'index/html.php';
/*
	* FIRST VISIT
	* To display a page to users that visit for the first time.  Saves a Cookie otherwise.
	if(!Cookie::exists('firsttime')) {
		Cookie::put('firsttime', 1, 0);
		include_once"index/first-time.php";
	} else {
		require_once("index/html.php");
	}

	* REFER USER
	* ID of the Referring Account taken from the $_GET in the url.  Saved in Session.
	if (Input::exists('get', 'ref_id')) {
		Session::put('ref_id', Input::get('ref_id'));
		Redirect::to('/');
	}
*/	
?>
