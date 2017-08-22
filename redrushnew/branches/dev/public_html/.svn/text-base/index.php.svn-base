<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
/** DEVICE TRACKING **/
include_once "deviceTrack.php";
/** END **/
/** MOBILE REDIRECTION **/
	include_once "../engines/Utility/Mobile_Detect.php";
	$detect = new Mobile_Detect();
	if ($detect->isMobile()) {
		header('Location: http://m.marlboro.co.id/');
	}
/** END **/
include_once $APP_PATH . APPLICATION . '/App.php';
include_once $APP_PATH."Interaction/Interaction.php";
$view = new BasicView();
$app = new App($req);
$app->main();
print $app;
die();