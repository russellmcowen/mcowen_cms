<?php 

// SITE INFO
$site_name = "GamePoints4Free.com";
$site_url = "http://gamepoints4free.com";
$favicon = "../images/FAVICON.ICO";

// DATABASE INFO
$db_host = "68.178.216.37";
$db_user = "freegamer";
$db_pass = "t3hP@sswurd";
$db_select = "freegamer";

// IF YOU USE A DIFFERENT DB LOCALLY
//if ($_SERVER['REMOTE_ADDR'] === '::1' || $_SERVER['REMOTE_ADDR'] === '127.0.0.1') {
//	$db_host = "";
//	$db_user = "";
//	$db_pass = "";
//	$db_select = "";
//}

// OPTIONS
$cookie = 'hash';
$expires = 604800;
$session_name = 'user';
$token_name = 'token';


$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => $db_host,
		'username' => $db_user,
		'password' => $db_pass,
		'db' => $db_select
	),
	'remember' => array(
		'cookie' => $cookie,
		'expires' => $expires
	),
	'session' => array(
		'session_name' => $session_name,
		'token_name' => $token_name
	)
);
