<?php
include_once "common.php";

include_once APP_PATH.APPLICATION."/helper/loginHelper.php";
$loginHelper = new loginHelper();
$user = $loginHelper->getProfile();

require_once APP_PATH.APPLICATION."/helper/activityReportHelper.php";
$track = new activityReportHelper($req,$user->id);
$track->activityTime();		
$track->log('logout');

// print_r($user);
// exit;
session_destroy();
global $CONFIG;
	sendRedirect($CONFIG['MOP_URL_LOGIN']);
exit;