<?php
include_once "locale.inc.php";
$GLOBAL_PATH = "../";
$APP_PATH = "../com/";
$ENGINE_PATH = "../engines/";
$WEBROOT = "../html/";
//DB WEB, local ex method: open()
$CONFIG['DATABASE'][0]['HOST'] = "localhost";
$CONFIG['DATABASE'][0]['USERNAME'] = "sample";

$CONFIG['DATABASE'][0]['PASSWORD'] = "sample";
$CONFIG['DATABASE'][0]['DATABASE'] = "sba";

/*
$CONFIG['DATABASE'][0]['HOST'] = "202.52.131.12";
=======
$CONFIG['DATABASE'][0]['PASSWORD'] = "root";
$CONFIG['DATABASE'][0]['DATABASE'] = "sittiweb";

/*
$CONFIG['DATABASE'][0]['HOST'] = "202.52.131.12";
>>>>>>> .r160
$CONFIG['DATABASE'][0]['USERNAME'] = "juragansitti";
$CONFIG['DATABASE'][0]['PASSWORD'] = "cotaxEdonatagosE";
$CONFIG['DATABASE'][0]['DATABASE'] = "db_web2";
*/

//DB WORDS, online ex method : open(1)







$CONFIG['FileUploaderServicePath'] = "http://baspace.com/uploader.php";

$UPLOAD_DIRS[0] = "contents/";
$UPLOAD_DIRS[1] = "contents/Picture_Gallery/";
$UPLOAD_DIRS[2] = "contents/media/";

/*openx webservice config*/
$OX_CONFIG['username'] = "admin";
$OX_CONFIG['password'] = "admin";
$OX_CONFIG['uri'] = "localhost";
//$OX_CONFIG['uri'] = "soekarno";
$OX_CONFIG['host'] = "localhost";
$OX_CONFIG['service'] = '/openx/www/api/v2/xmlrpc/';
$OX_CONFIG['tracker_uri'] = "http://localhost/openx";
$OX_CONFIG['debug'] = false;

$CONFIG['MOP_URL'] = "http://www.ba-space.com/index2.php";
$CONFIG['page_path'] = "http://www.ba-space.com/page.php?u=";
$CONFIG['page_home'] = "http://www.ba-space.com/page.php";
$CONFIG['API_URL'] = "http://www.ba-space.com/api/";
$CONFIG['TRACKER_URL'] = "http://www.ba-space.com/track.php";
?>