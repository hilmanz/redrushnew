<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $APP_PATH."Social/SocialApp.php";
include_once $APP_PATH."Interaction/Interaction.php";

//Interaction Point Tracker
$tracker = new Interaction(&$req);
//-->hanya untuk di dev

//--->
$view = new BasicView();
$app = new SocialApp(&$req);
$app->main();

print $app;
?>