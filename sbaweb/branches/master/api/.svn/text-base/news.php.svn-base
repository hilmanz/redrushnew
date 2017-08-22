<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $APP_PATH."Social/SocialAPI.php";


$view = new BasicView();

$api = new SocialAPI($req);
print $api->getNews($_REQUEST['user_id'],$_REQUEST['last_id']);

?>