<?php
class test extends App{
	var $Request;
	
	var $View;
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
	}
	
	function home(){
		print $this->test_race_report();
		die();
	}
	function test_race_report(){
		include_once APP_PATH.APPLICATION."/helper/apiHelper.php";
		$api = new apiHelper();
		$players = urlencode64(serialize(array("player1"=>1,"player2"=>2)));
		print $api->getRaceReport($players);
		
	}
	function test_race_get_report(){
		include_once APP_PATH.APPLICATION."/helper/apiHelper.php";
		$api = new apiHelper();
		$session_id = $this->Request->getParam("session_id");
		print $api->getRaceResult($session_id,1,2);
	}
	function test_profile(){
		include_once APP_PATH.APPLICATION."/helper/apiHelper.php";
		$api = new apiHelper();
		print $api->getPlayerData(1);
		
	}

}
?>