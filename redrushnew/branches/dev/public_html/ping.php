<?php 
include_once "../config/config.inc.php";
include_once "common_track.php";
include_once $APP_PATH."MOP/MOPClient_2.php";
$mop = new MopClient($req);

$params = unserialize(urldecode64($_REQUEST['r']));

$sessId = $params['sessId'];
$PageRef = $params['PageRef'];
$ActivityName = $params['ActivityName'];
$ActivityValue = $params['ActivityValue'];
$CPMOO = $params['CPMOO'];
$user = $params['user'];
$handler = $params['handler'];
//session_id($handler);
session_start();

$sessId=$_SESSION['mop_sess_id'];

$username = $_SESSION['MOP_SESSION']['UserProfile']['Login'];
$str = date("Y-m-d H:i:s")."[{$username}] ping#".$handler." before : ".$sessId."\n";


//$foo = json_encode($_SESSION['MOP_SESSION']);
$sess_id=$mop->checkReferral($sessId);
$str.= date("Y-m-d H:i:s")."[{$username}] ping#".$handler." after : ".$sess_id."\n";
$str.="------------------------\n";

$fp = fopen("../logs/track2.log","a+");
fwrite($fp,$str,strlen($str));
fclose($fp);
if( $sess_id < 0 || $sess_id == '99' || $sess_id == '' ){
	//session_destroy();
	//sendRedirect('index.php');
	$_SESSION['mop_sess_id'] = "-1";
	exit;
}else{
	$_SESSION['mop_sess_id']=$sess_id;
}

//render image
header('content-type:image/gif');
readfile('track.gif');
exit();
?>