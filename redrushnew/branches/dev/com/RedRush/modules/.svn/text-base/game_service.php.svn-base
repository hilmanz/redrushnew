<?php
include_once $APP_PATH."RedRush/models/game_model.php";
class game_service extends API_Module{
	protected $model;
	
	function init(){
		$this->model = new game_model();
		
	}
	function get_session(){
		$access_token = $this->_get('access_token');
		$foo = unserialize(urldecode64($access_token));
		$user_id = $foo[1];
		$game_id = $this->_get('game_id');
		$api_key = $this->_get('api_key');
		return $this->generate_session($user_id,$game_id,$api_key);
	}
	function generate_session($user_id,$game_id,$api_key){
		$this->model->open(0);
		if($this->model->apikey_valid($game_id,$api_key)){
			$status=1;
			$session_id = $this->model->generate_session($user_id,$game_id,$api_key);
			$msg = "get_session";
		}else{
			$status=0;
			$msg = "Invalid API Key";
		}
		$lastLevelID = $this->getlastLevelID($user_id,$game_id);
		$this->model->close();
		
		return $this->toJson($status,$msg,array('session' => $session_id,'lastLevelID'=>$lastLevelID));
	}

	function getlastLevelID($user_id,$game_id){
		$last_id = $this->model->get_last_level_game_id($user_id,$game_id);		
		return $last_id;
	}
	
	function save_score(){
		global $MINIGAME_SCORES;
		
		$access_token = $this->_get('access_token');
		$foo = unserialize(urldecode64($access_token));
		$user_id = $foo[1];
		$game_id = $this->_get('game_id');
		$level = $this->_get('level');
		$score = $MINIGAME_SCORES[$level];
		$session_token = $this->_get('session_token');
		
		$this->model->open(0);
		if($this->model->save_score($user_id,$game_id,$level,$score,$session_token)){
			$status=1;
			$msg = "save_score";
			$data = $score;
		}else{
			
			$status=0;
			$msg = "save_score";
			$data = 0;
		}
		$this->model->close();
		return $this->toJson($status,$msg,$data);
	}
	
}
?>