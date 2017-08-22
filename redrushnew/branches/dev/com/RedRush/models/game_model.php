<?php 
class game_model extends SQLData{
	var $logs;
	var $debug;
	private $penalties = array(0.05,0.08,0.1);//>10 wins, > 20 wins, > 30 wins
	
	function __construct(){
		global $ENGINE_PATH;
		include_once $ENGINE_PATH."Utility/Debugger.php";
		parent::SQLData();
		$this->debug = new Debugger();
	}
	
	function apikey_valid($game_id,$api_key){
		$game_id = intval($game_id);
		if($game_id>0 && strlen($api_key)>0){
			$sql = "SELECT game_id, api_key 
					FROM 
					".RedRushDB.".game_api_key
					WHERE 
					game_id=".$game_id." 
					AND api_key='".mysql_escape_string($api_key)."'
					limit 1";
			$rs = $this->fetch($sql);
			if($rs['game_id']==$game_id&&$rs['api_key']==$api_key){
				return true;	
			}
		}
	}
	
	function generate_session($user_id,$game_id,$api_key){
		$user_id = intval($user_id);
		$game_id = intval($game_id);
		$api_key = mysql_escape_string($api_key);
		$session_id = $this->get_old_sessions($user_id,$game_id);
		$this->start_game($user_id,$game_id,$session_id);
		if(strlen($session_id)==0){
			
			$session_id = sha1(date("Ymdhis").$user_id.$game_id.$api_key);
			$_SESSION['session_id'] = $session_id;
			
			
			if(!$this->add_session($user_id,$game_id,$session_id)){
				$session_id=null;
			}
		}
		return $session_id;
	}
	
	function get_last_level_game_id($user_id,$game_id){
		$user_id = intval($user_id);
		$game_id = intval($game_id);
		$sql = "SELECT level_id FROM game_score WHERE user_id=".$user_id." AND game_id=".$game_id." order by submit_time DESC LIMIT 1";
		$data = $this->fetch($sql);
		return $data['level_id'];
	
	}
	
	function add_session($user_id,$game_id,$session_token){
		$user_id = intval($user_id);
		$game_id = intval($game_id);
		$expiry_ts = time()+(60*30);//expired on 30 minutes
		
		
		
		$sql = "INSERT IGNORE INTO ".RedRushDB.".game_session_token
				(user_id,game_id,session_token,expiry_ts,generated_date,n_hit)
				VALUES(".$user_id.",".$game_id.",'".mysql_escape_string($session_token)."',".$expiry_ts.",NOW(),0)";
		
		return $this->query($sql);
	}
	function get_old_sessions($user_id,$game_id){		
		$sql = "SELECT * FROM ".RedRushDB.".game_session_token 
				WHERE user_id=".$user_id." 
				AND game_id=".$game_id." AND n_status=0 
				LIMIT 1";
		$rs = $this->fetch($sql);
		$ts = time();
		if($rs['expiry_ts']<$ts){
			$this->set_session_expired($user_id,$game_id,$rs['session_token']);
			return null;
		}else{
			$this->set_session_hittime($user_id,$game_id,$rs['session_token']);
			return $rs['session_token'];
		}
	}
	function set_session_expired($user_id,$game_id,$session_token){
		return $this->update_session_token($user_id,$game_id,$session_token,2);
	}
	function set_session_hittime($user_id,$game_id,$session_token){
		$sql = "UPDATE ".RedRushDB.".game_session_token
				SET used_date = NOW(),n_hit = n_hit+1 
				WHERE user_id=".$user_id." 
				AND game_id=".$game_id." AND session_token='".$session_token."'";
		return $this->query($sql);
	}
	function update_session_token($user_id,$game_id,$session_token,$flag=1){
		$user_id = intval($user_id);
		$game_id = intval($game_id);
		$flag = intval($flag);
		$sql = "UPDATE ".RedRushDB.".game_session_token
				SET n_status=".$flag.",used_date = NOW(),n_hit = n_hit+1 
				WHERE user_id=".$user_id." 
				AND game_id=".$game_id." AND session_token='".mysql_escape_string($session_token)."'";
		return $this->query($sql);
	}
	
	function start_game($user_id,$game_id,$session_token){
	$sql = "INSERT IGNORE INTO ".RedRushDB.".tbl_activity_mini_game
				(user_id,game_id,session_token,start_date_time)
				VALUES
				(".$user_id.",".$game_id.",'".mysql_escape_string($session_token)."',NOW())";
		
	$this->query($sql);
	
	}
	
	function end_game($user_id,$game_id,$level,$session_token=null){
	if($session_token==null) $session_token = sha1(date("Ymdhis").$user_id.$game_id);
	$sql = "SELECT id FROM  ".RedRushDB.".tbl_activity_mini_game WHERE user_id=".$user_id." AND game_id=".$game_id." AND n_status=0 order by start_date_time DESC LIMIT 1";
	$data = $this->fetch($sql);
	$aut_game = $data['id'];
	$sql = "UPDATE ".RedRushDB.".tbl_activity_mini_game
				SET end_date_time=NOW(), n_status=1,game_level ={$level}
				WHERE id={$aut_game}
				";
		
	$this->query($sql);
	
	}
	
	function save_score($user_id,$game_id,$level,$score,$session_token){
		
		$user_id = intval($user_id);
		$game_id = intval($game_id);
		$level = intval($level);
		$score = intval($score);
		
		$this->end_game($user_id,$game_id,$level,$session_token);
			
		if(strlen($session_token)>10){
			
			$old_session = $this->get_old_sessions($user_id,$game_id);
			
		
			
			if($session_token==$old_session){
				if($this->can_score($user_id,$game_id,$level)){
					
					$sql = "INSERT INTO ".RedRushDB.".game_score
							(game_id, user_id, submit_time, submit_time_ts, score, level_id, game_session_token)
							values
							(".$game_id.", ".$user_id.", NOW(), ".time().", ".$score.", ".$level.",'".$session_token."')";
					$q = $this->query($sql);
					
				
					
					if($q){
						$this->update_session_token($user_id,$game_id,$session_token,0);
						return true;
					}
				}
			}
		}
	}
	function can_score($user_id,$game_id,$level_id){
		$sql = "SELECT COUNT(*) as total FROM ".RedRushDB.".game_score
				WHERE user_id=".$user_id." AND game_id=".$game_id." AND level_id=".$level_id." 
				AND DATE(submit_time) = DATE(NOW())
				LIMIT 1";
		$rs = $this->fetch($sql);
		if($rs['total']==0){
			return true;
		}
	}
}
?>
