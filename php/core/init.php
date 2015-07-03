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
	
	require_once 'index/html.php';
	/*
	if(Cookie::exists(Config::get('remember/cookie')) && !Session::exists(Config::get('session/session_name'))) {
		$hash = Cookie::get(Config::get('remember/cookie'));
		$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));
		if($hashCheck->count()) {
			$user = new User($hashCheck->first()->user_id);
			$user->login();
		}
	}
	if(Input::get('p')) {
		$page = Input::get('p');
		$subpage = null;
		if(strpos($page, '/')) {
			$temp = explode('/', $page);
			$page = $temp[0];
			$subpage = array();
			$subpage = $temp;
		}
	} else { $page = "home"; }
	$user = new User();
	/*
	if(!Cookie::exists('firsttime')) {
		Cookie::put('firsttime', 1, 0);
		include_once"index/first-time.php";
	} else {
		require_once("index/html.php");
	}
	
	// REFERRAL ID
	if (Input::exists('get', 'ref_id')) {
		Session::put('ref_id', Input::get('ref_id'));
		Redirect::to('/');
	}

	require_once("index/html.php");
	
*/	
?>
