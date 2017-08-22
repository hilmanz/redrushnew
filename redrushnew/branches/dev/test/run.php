<?php
include_once "../engines/functions.php";

$max_run = 1000;
$user1 = 3;
$user2 = 4;
$win = array($user1=>0,$user2=>0);
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
$url = "http://localhost/redrush/src/api/?".http_build_query($params);
$resp_txt = get($url);
print $resp_txt."\n";
$response = json_decode($resp_txt);
$circuit_id = $response->data;
print "circuit_id -> ".$circuit_id;

$circuit_id = 5;

//print $response."\n";
//print "retrieved session_id : ".$session_id."\n";

print "Running Simulation : \n";
for($i=0;$i<$max_run;$i++){
	$params = array();
	$params['service'] = "racing_service";
	$params['method'] = "get_session";
	$params['circuit_id'] = $circuit_id;
	$params['racer1'] = $user1;
	$params['racer2'] = $user2;
	
	$url = "http://localhost/redrush/src/api/?".http_build_query($params);
	$response = get($url);
	$obj = json_decode($response);
	$session_id = $obj->data;

	$params = array();
	$params['service'] = "racing_service";
	$params['method'] = "race";
	$params['session_id'] = $session_id;
	$params['status'] = 1;
	//print "RACING SIMULATION : \n";
	$url = "http://localhost/redrush/src/api/?".http_build_query($params);
	//print $url."\n";
	$response = get($url);
	$result = json_decode($response);
	$winner = $result->data->winner;
	//print $winner."n";
	//print "\n";
	$win[$winner]++;
}
$total = $win[$user1]+$win[$user2];
$percent1 = round($win[$user1]/$total*100);
$percent2 = round($win[$user2]/$total*100);

print $user1." -> ".$percent1."%\n";
print $user2." -> ".$percent2."%\n";
/*
$params = array();
$params['service'] = "racing_service";
$params['method'] = "close_session";
$params['session_id'] = $session_id;
$params['status'] = 1;

$url = "http://localhost/redrush/src/api/?".http_build_query($params);
print get($url);
print "\n";
*/

?>