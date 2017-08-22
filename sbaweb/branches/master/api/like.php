<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $APP_PATH."Social/SocialAPI.php";
include_once $APP_PATH."Interaction/Interaction.php";

//Interaction Point Tracker
$tracker = new Interaction(&$req);

$view = new BasicView();
$api = new SocialAPI($req);
//print $api->postFeed($req->getPost("user_id"),$req->getPost("text"),$req->getPost("signed_request"));
print $api->like($req->getPost("user_id"),$req->getPost("post_id"),$req->getPost("signed_request"));
?>