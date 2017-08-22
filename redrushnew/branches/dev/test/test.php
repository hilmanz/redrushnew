<?php
include_once "../engines/functions.php";
function get($url){
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_TIMEOUT,15);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
	//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); 
	
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$response = curl_exec ($ch);
	$info = curl_getinfo($ch);
	curl_close ($ch);
	return $response;
}
$params = array();
$params['service'] = "racing_service";
$params['method'] = "get_circuit";
$params['apikey'] = "66baff41a1eec8eeeefe2b2a5dcbade7a92e0cb8";
$url = "http://localhost/redrush/dev/branches/dev/api/?".http_build_query($params);
$resp_txt = get($url);
print $resp_txt."\n";
$response = json_decode($resp_txt);
$circuit_id = $response->data;
print "circuit_id -> ".$circuit_id;

$circuit_id = 5;
$params = array();
$params['service'] = "racing_service";
$params['method'] = "get_session";
$params['circuit_id'] = $circuit_id;
$params['racer1'] = 3;
$params['racer2'] = 4;
$params['apikey'] = "66baff41a1eec8eeeefe2b2a5dcbade7a92e0cb8";

$url = "http://localhost/redrush/dev/branches/dev/api/?".http_build_query($params);
$response = get($url);
$obj = json_decode($response);
$session_id = $obj->data;
print $response."\n";
print "retrieved session_id : ".$session_id."\n";

$params = array();
$params['service'] = "racing_service";
$params['method'] = "race";
$params['session_id'] = $session_id;
$params['status'] = 1;
$params['apikey'] = "66baff41a1eec8eeeefe2b2a5dcbade7a92e0cb8";
print "RACING SIMULATION : \n";
$url = "http://localhost/redrush/dev/branches/dev/api/?".http_build_query($params);
print $url."\n";
$response = get($url);
$result = json_decode($response);
foreach($result->data->txt as $feed){
	print $feed."\n";
}


print "\n";
/*
$params = array();
$params['service'] = "racing_service";
$params['method'] = "close_session";
$params['session_id'] = $session_id;
$params['status'] = 1;

$url = "$GAME_API?".http_build_query($params);
print get($url);
print "\n";
*/

?>