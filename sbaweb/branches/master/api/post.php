<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $APP_PATH."Social/SocialAPI.php";
include_once $APP_PATH."Interaction/Interaction.php";

//Interaction Point Tracker
$tracker = new Interaction(&$req);

$view = new BasicView();

$api = new SocialAPI($req);
if($_POST['reply']=="1"){
	print $api->Reply($req->getPost("user_id"),$req->getPost('post_id'),$req->getPost("text"),$req->getPost("signed_request"));
}else if($_POST['text']){
	print $api->postFeed($req->getPost("user_id"),$req->getPost("text"),$req->getPost("signed_request"));
}else if($_GET['total_reply']=="1"){
	print $api->total_reply($req->getParam("post_id"));
}else if($_GET['get_reply']=="1"){
	print $api->getComments($req->getParam("post_id"));
}else if($_GET['more']=="1"){
	print $api->getMoreFeed( $_GET['user_id'], $_GET['start'] );
}else{
	print $api->getFeed($_REQUEST['user_id'],$_REQUEST['last_id']);
}
?>