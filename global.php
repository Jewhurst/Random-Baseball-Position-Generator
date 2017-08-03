<?php 
	/*REQUIRE FILES */
	require('functions.php');
	//********************************************************************************
	$database_host = "localhost";
	$database_user = "baseballposition";
	$database_pass = "=C6r37TF8hv0";
	$database_name = "baseballposition";
	try {
		$db = new PDO("mysql:host={$database_host};dbname={$database_name}",$database_user,$database_pass);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch( PDOexception $e) {
		echo $e->getMessage();
	}
	$data = new dbconnect($db);
	//********************************************************************************
	
	if (!defined('_BASE_DIR_')) {
	    define('_BASE_DIR_','/home/jewhurst1138/public_html/');
	}
	//require('class.database.php');
	//require('connect.php');
	/* DEFINE TIMEZONE */
	date_default_timezone_set("America/New_York");
	/*SET HOME DIRECTORY, kinda*/
	$home = "http://".$_SERVER['SERVER_NAME']."/";	
	define("HOME", $home);

	
	define("UPLOADS",'/');
	define("IMG", $home.'i/');
	define("LOGPATH",_BASE_DIR_ . 'beta.baseballposition.com/');
	define('TIMEOUT',30);
	define('ADMINEMAIL','info@baseballposition.com');
	
	/*SOCIAL MEDIA LINKS*/
	define('FACEBOOK','https://www.facebook.com');
	define('TWITTER','https://www.twitter.com');
	define('GOOGLEPLUS','https://plus.google.com');
	define('REDDIT','https://www.reddit.com');
	define('LINKEDIN','https://www.linkedin.com');
	define('YOUTUBE','https://www.youtube.com');
	
	
	define('ICON_BLOTTERS','<i class="fa fa-pencil" aria-hidden="true"></i>');
	define('ICON_MESSAGES','<i class="fa fa-envelope" aria-hidden="true"></i>');
	define('ICON_INBOX','<i class="fa fa-inbox" aria-hidden="true"></i>');
	define('ICON_OUTBOX','<i class="fa fa-paper-plane" aria-hidden="true"></i>');
	define('ICON_ALERTS','<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>');
	define('ICON_FOLLOWERS','<i class="fa fa-thumbs-up" aria-hidden="true"></i>');
	define('ICON_FOLLOWING','<i class="fa fa-star" aria-hidden="true"></i>');
	define('ICON_SETTINGS','<i class="fa fa-cogs" aria-hidden="true"></i>');
	define('ICON_DELETE','<i class="fa fa-trash" aria-hidden="true"></i>');
	define('ICON_CHECK','<i class="fa fa-check" aria-hidden="true"></i>');
	session_start();
	
	
?>