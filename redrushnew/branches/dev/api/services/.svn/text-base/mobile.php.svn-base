<?php

class mobile{
private $curl;

function __construct(){
include_once "curl_class.php";
$this->curl = new curl_class();
}

function userLogin(){
global $REDRUSH_APIKEY,$GAME_API;
		$params = array();
		$params['service'] = "mobile_service";
		$params['method']  = "user_login";
		$params['username'] = @$_REQUEST['username'];
		$params['password'] =  @$_REQUEST['password'];
				$params['access_token']  = @$_REQUEST['access_token'];
		$params['apikey'] = $REDRUSH_APIKEY;
$url = "{$GAME_API}?".http_build_query($params);
$resp_txt = $this->curl->get($url);
print_r('request: <br>');
print_r($url.' <br>');
print_r('response: <br>');
print_r(($resp_txt));


}

function getProfile(){
global $REDRUSH_APIKEY,$GAME_API;
		$params = array();
		$params['service'] = "mobile_service";
		$params['method']  = "user_profile";
		$params['userid'] = @$_REQUEST['userid'];
				$params['access_token']  = @$_REQUEST['access_token'];
		$params['apikey'] = $REDRUSH_APIKEY;
$url = "{$GAME_API}?".http_build_query($params);

$resp_txt = $this->curl->get($url);
print_r('request: <br>');
print_r($url.' <br>');
print_r('response: <br>');
print_r(($resp_txt));

}

function searchProfile(){
global $REDRUSH_APIKEY,$GAME_API;
		$params = array();
		$params['service'] = "mobile_service";
		$params['method']  = "search_profile";
		$params['searchtxt'] = @$_REQUEST['searchtxt'];
		$params['apikey'] = $REDRUSH_APIKEY;
		$params['access_token']  = @$_REQUEST['access_token'];
$url = "{$GAME_API}?".http_build_query($params);
$resp_txt = $this->curl->get($url);
print_r('request:<br> ');
print_r($url.' <br>');
print_r('response: <br>');
print_r(($resp_txt));

}


function newsFeed(){
global $REDRUSH_APIKEY,$GAME_API;
		$params = array();
		$params['service'] = "mobile_service";
		$params['method']  = "news_feed";
		$params['page'] = @$_REQUEST['page'];
				$params['access_token']  = @$_REQUEST['access_token'];
		$params['apikey'] = $REDRUSH_APIKEY;
$url = "{$GAME_API}?".http_build_query($params);
$resp_txt = $this->curl->get($url);
print_r('request: <br>');
print_r($url.' <br>');
print_r('response:<br> ');
print_r(($resp_txt));

}



function userCarAvatar(){
global $REDRUSH_APIKEY,$GAME_API;
		$params = array();
		$params['service'] = "mobile_service";
		$params['method']  = "car_avatar";
		$params['userid'] = @$_REQUEST['userid'];
				$params['access_token']  = @$_REQUEST['access_token'];
		$params['apikey'] = $REDRUSH_APIKEY;
$url = "{$GAME_API}?".http_build_query($params);
$resp_txt = $this->curl->get($url);
print_r('request:<br> ');
print_r($url.' <br>');
print_r('response: <br>');
print_r(($resp_txt));

}



function userTimeline(){
global $REDRUSH_APIKEY,$GAME_API;
		$params = array();
		$params['service'] = "mobile_service";
		$params['method']  = "user_timeline";
		$params['userid'] = @$_REQUEST['userid'];
		$params['page'] = @$_REQUEST['page'];
				$params['access_token']  = @$_REQUEST['access_token'];
		$params['apikey'] = $REDRUSH_APIKEY;
$url = "{$GAME_API}?".http_build_query($params);
$resp_txt = $this->curl->get($url);
print_r('request: <br>');
print_r($url.' <br>');
print_r('response:<br> ');
print_r(($resp_txt));

}


function suggestOpponent(){
global $REDRUSH_APIKEY,$GAME_API;
		$params = array();
		$params['service'] = "mobile_service";
		$params['method']  = "suggest_opponent";
		$params['userid'] = @$_REQUEST['userid'];
				$params['access_token']  = @$_REQUEST['access_token'];
		$params['apikey'] = $REDRUSH_APIKEY;
$url = "{$GAME_API}?".http_build_query($params);
$resp_txt = $this->curl->get($url);
print_r('request: <br>');
print_r($url.' <br>');
print_r('response: <br>');
print_r(($resp_txt));

}

function searchOpponent(){
global $REDRUSH_APIKEY,$GAME_API;
		$params = array();
		$params['service'] = "mobile_service";
		$params['method']  = "search_opponent";
		$params['searchtxt'] = @$_REQUEST['searchtxt'];
		$params['page'] = @$_REQUEST['page'];
		$params['userid'] = @$_REQUEST['userid'];
				$params['access_token']  = @$_REQUEST['access_token'];
		$params['apikey'] = $REDRUSH_APIKEY;
$url = "{$GAME_API}?".http_build_query($params);
$resp_txt = $this->curl->get($url);
print_r('request: <br>');
print_r($url.' <br>');
print_r('response:<br> ');
print_r(($resp_txt));

}


function getRaceDialog(){
global $REDRUSH_APIKEY,$GAME_API;
		$params = array();
		$params['service'] = "mobile_service";
		$params['method']  = "get_race_dialog";
		$params['userid'] = @$_REQUEST['userid'];
		$params['opponentid'] = @$_REQUEST['opponentid'];
				$params['access_token']  = @$_REQUEST['access_token'];
		$params['apikey'] = $REDRUSH_APIKEY;
$url = "{$GAME_API}?".http_build_query($params);
$resp_txt = $this->curl->get($url);
print_r('request:<br> ');
print_r($url.' <br>');
print_r('response:<br> ');
print_r(($resp_txt));

}
function syncMop(){
global $REDRUSH_APIKEY,$GAME_API;
		// $register_id,$firstname,$lastname,$nickname,$email,$avtype
		$params = array();
		$params['service'] = "mobile_service";
		$params['method']  = "sync_data";
		$params['register_id'] = @$_REQUEST['register_id'];
		$params['firstname'] = @$_REQUEST['firstname'];
		$params['lastname'] = @$_REQUEST['lastname'];
		$params['nickname'] = @$_REQUEST['nickname'];
		$params['email'] = @$_REQUEST['email'];
		$params['avtype'] = @$_REQUEST['avtype'];
		$params['access_token']  = @$_REQUEST['access_token'];
		$params['apikey'] = $REDRUSH_APIKEY;
$url = "{$GAME_API}?".http_build_query($params);
$resp_txt = $this->curl->get($url);
print_r('request: <br>');
print_r($url.' <br>');
print_r('response:<br> ');
print_r(($resp_txt));

}

}

?>