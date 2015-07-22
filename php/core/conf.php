<?php 
// SITE INFO
$site_name = "Your Site Name";
$site_url = "http://www.siteurl.com";
$favicon = "../images/FAVICON.ICO";
// OPTIONS
$cookie = 'hash';
$expires = 604800;
$session_name = 'user';
$token_name = 'token';
// DATABASE INFO
$db_host = "";	
$db_user = "";
$db_pass = "";
$db_select = "";
/*
// IF YOU USE A DIFFERENT DB LOCALLY
if ($_SERVER['REMOTE_ADDR'] == '::1' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
	$db_host = "localhost";
	$db_user = "";
	$db_pass = "";
	$db_select = "";
}
*/

// DATA ACCESS WITH Config::get() FUNCTION
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
