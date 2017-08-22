<?php
$CONFIG['LOG_DIR'] = "../logs/";
$GLOBAL_PATH = "../";
$APP_PATH = "../com/";
$ENGINE_PATH = "../engines/";
$WEBROOT = "../html/";

//DEFINE VARIABLE
define('APPLICATION','RedRushWeb');	//set aplikasi yang digunakan
define('DB_PREFIX','rr');	//set DB prefix for frontend
define('BASEURL','http://localhost/redrush-web/public_html/');	//set BASEURL frontend
define('BASEURL_ADMIN','http://localhost/redrush-web/admin');	//set BASEURL admin
define('APP_PATH',$APP_PATH);
define('RedRushDB','redrush');
define('GameDB','redrush_game');

//set database
$CONFIG['DEVELOPMENT'] = true;
if($CONFIG['DEVELOPMENT']){
	$CONFIG['DATABASE'][0]['HOST'] 		= "localhost";
	$CONFIG['DATABASE'][0]['USERNAME'] 	= "sample";
	$CONFIG['DATABASE'][0]['PASSWORD'] 	= "sample";
	$CONFIG['DATABASE'][0]['DATABASE'] 	= "redrush";
	//error_reporting(E_ERROR);
}else{
	$CONFIG['DATABASE'][0]['HOST'] 				= "";
	$CONFIG['DATABASE'][0]['USERNAME'] 	= "";
	$CONFIG['DATABASE'][0]['PASSWORD'] 	= "";
	$CONFIG['DATABASE'][0]['DATABASE'] 	= "";
}

/* DATETIME SET */
$timeZone = 'Asia/Jakarta';
date_default_timezone_set($timeZone);

/* SET MOP */
$CONFIG['MOP'] = false;

$WIN_PENALTY = array(0.05,0.1,0.2);
$GAME_API = "http://localhost/redrush-web/api/";

$MINIGAME_SCORES = array(0,30,40,50);

//this is hash for accessing RedRush Racing game API.
$REDRUSH_APIKEY = sha1("RedRushAPIKanaKana9i8u");

//this is hash for urlencode64 and urldecode64
$HASH_SECRET_KEY = sha1("RedRushRunner");

?>