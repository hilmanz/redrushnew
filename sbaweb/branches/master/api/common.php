<?php
session_start();
//header("Set-Cookie: PHPSESSID=" . session_id() . "; path=/");
include_once "../engines/Gummy.php";
include_once "../engines/functions.php";
include_once "../com/Application.php";
$MAIN_TEMPLATE = "sample/default.html";

$req = new RequestManager();

?>