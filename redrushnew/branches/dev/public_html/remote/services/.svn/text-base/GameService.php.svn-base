<?php
class GameService{
	//helpers
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
	function out($status,$msg,$data=null){
		$arr = array("status"=>$status,"message"=>$msg,"data"=>$data);
		return json_encode($arr);
	}
	//---->
	
	//the api methods
	public function get_session($access_token,$game_id,$api_key){
		global $API_URL;
		$params = array();
		$params['service'] = "game_service";
		$params['method'] = "get_session";
		$params['access_token'] = $access_token;
		$params['game_id'] = $game_id;
		$params['api_key'] = $api_key;
		$url = $API_URL."?".http_build_query($params);
		
		return $this->get($url);
	}
	public function save_score($access_token,$game_id,$level,$session_token){
		global $API_URL;
		$params = array();
		$params['service'] = "game_service";
		$params['method'] = "save_score";
		$params['access_token'] = $access_token;
		$params['game_id'] = $game_id;
		$params['level']= $level;
		$params['session_token'] = $session_token;
		
		$url = $API_URL."?".http_build_query($params);
		
		return $this->get($url);
	}
	//-->
	
}
?>