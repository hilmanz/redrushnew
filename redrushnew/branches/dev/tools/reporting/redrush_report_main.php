<?php

include_once "bootstraps.php";
include_once "common2.php";
include_once "UserReport.php";
include_once "OverallReport.php";

error_reporting(0);
$log = new Logger("redrush_report_bot");
$log->logger_namespace("Bot");
$log->verbose(true);
$log->info("starting");

date_default_timezone_set('Asia/Jakarta');

$arg_arr = arguments($argv);
if (is_null($arg_arr['options']['tanggal'] )) {
    $yesterday = date("Y-m-d", time() - 86400);
} else {
    $tanggal = $arg_arr['options']['tanggal'];
    $tanggal_arr = preg_split("/-/", $tanggal);
    
    if (isValidDate($tanggal_arr[0], $tanggal_arr[1], $tanggal_arr[2])) {
        $yesterday = $tanggal_arr[0] . "-" . $tanggal_arr[1] . "-" . $tanggal_arr[2];
    } else {
        die("format 'tanggal' tidak valid");
    }
}

$yesterday_ts = strtotime($yesterday);

print $yesterday . " " . $yesterday_ts . "\n";

$conn = open_db(0);

OverallReport::prepareTempReport($conn, $log, $yesterday_ts, $yesterday);

$users = UserReport::getAllUser($conn, $log);
$user_rp = new UserReport($user['id'], $conn, $log);
//print_r($users);
foreach($users as $user) {
    //break;
    //print_r($user);
    //print $user['id']. "\n";
    $user_rp->user_id($user['id']);
    $user_rp->process($yesterday, $yesterday_ts);
    //break;
}

$overall = new OverallReport($conn, $log);
$overall->process($yesterday, $yesterday_ts);

close_db($conn);

die();

?>
