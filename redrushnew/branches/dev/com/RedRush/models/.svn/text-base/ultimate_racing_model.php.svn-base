<?php 
class ultimate_racing_model extends SQLData{
	var $logs;
	var $debug;
	private $penalties = array(0.05,0.08,0.1);//>10 wins, > 20 wins, > 30 wins
	function __construct(){
		global $ENGINE_PATH;
		include_once $ENGINE_PATH."Utility/Debugger.php";
		parent::SQLData();
		$this->debug = new Debugger();
	}
	function get_user($user_id){
		//$sql = "SELECT * FROM ".RedRushDB.".kana_member WHERE id=".$user_id." LIMIT 1";
		$sql = "SELECT a.*,b.level,c.path_qr_code FROM ".RedRushDB.".kana_member a
				LEFT JOIN ".GameDB.".racing_level b	ON a.id = b.user_id
				LEFT JOIN ".RedRushDB.".tbl_qr_code_user c	ON a.id = c.user_id
				WHERE a.id=".$user_id." LIMIT 1";
		return $this->fetch($sql);
	}

	function get_ultimate_car_by_id($ultimate_id){
	$sql = "SELECT *,ultimate_id as id FROM ".RedRushDB.".rr_ultimate_car WHERE ultimate_id=".$ultimate_id." LIMIT 1";
	return $this->fetch($sql);
	
	}
	
	function get_rank_user($user_id){
	$sql = "SELECT rank FROM tbl_rank_user WHERE user_id=".$user_id." LIMIT 1";
	$qData = $this->fetch($sql);
	return $qData['rank'];
	
	}
	
	function create_session($racer1,$racer2,$circuit_id){
		/*$sql = "SELECT * FROM ".GameDB.".racing_session WHERE session_id=".$session_id." LIMIT 1";
		$rs = $this->fetch($sql);
		*/
		$retry = 0;
		do{
			$session_id = sha1($racer1."_".$racer2."_".$circuit_id."_".date("Ymdhis").rand(0,999));
			$sql = "SELECT * FROM ".GameDB.".racing_session WHERE session_id='".$session_id."' LIMIT 1";
			$session = $this->fetch($sql);
			
			//give up when necessary
			$retry++;
			if($retry>=100){
				return -1;
			}
			//-->
		}while($session['n_status']==1);
			
		$racer1 = mysql_escape_string($racer1);
		$racer2 = mysql_escape_string($racer2);
		$circuit_id = mysql_escape_string($circuit_id);
		$race_date = date("Y-m-d H:i:s");
		$race_date_ts = strtotime($race_date); 
		$sql = "INSERT IGNORE INTO ".GameDB.".racing_session(session_id, 
		circuit_id, race_date, race_date_ts, racer1_id, racer2_id, n_status)
		VALUES('".$session_id."',".$circuit_id.",'".$race_date."',".$race_date_ts.",".$racer1.",".$racer2.",0)";
		$q = $this->query($sql);
		if($q){
			return $session_id;
		}else{
			return -1;
		}
	}
	function get_race_session($session_id){
		$session_id = mysql_escape_string($session_id);
		$sql = "SELECT * FROM ".GameDB.".racing_session WHERE session_id='".$session_id."' LIMIT 1";
		$rs = $this->fetch($sql);
		return $rs;
	}
	function calculate_result($session_id,$race){
		
		$this->debug->info("calculating result");
		$this->debug->info("Racing between : ".$race['racer1_id']." VS ".$race['racer2_id']);
		//get driver's stats
		$ultimate_id = $race['racer2_id'];
		$user1_total_race = $this->races_today($race['racer1_id']);
		$user2_total_race = 0;
		
		$user1_total_wins = $this->wins_today($race['racer1_id']);
		$user2_total_wins = 0;
		
		$user1_stats = $this->get_driver_stats($race['racer1_id']);
		$user2_stats = $this->get_driver_stats($race['racer1_id']);
		$this->debug->info("driver 1 : ".$user1_stats['LS'].",".$user1_stats['SS'].",".$user1_stats['LC'].",".$user1_stats['SC']);
		$this->debug->info("Ultimate Racer : ".$user2_stats['LS'].",".$user2_stats['SS'].",".$user2_stats['LC'].",".$user2_stats['SC']);
		//-->
		//var_dump($user1_stats);
		//var_dump($user2_stats);
		
		//get circuit details
		$circuit = $this->get_circuit_detail($race['circuit_id']);
		$circuit['config'] = unserialize($circuit['track_config']);
		//var_dump($circuit);
		$this->debug->info("Circuit ".$circuit['id']." ".$circuit['laps']." Laps : ".json_encode($circuit['config']));
		
		//simulate the race
		$simulation = $this->simulate_race($user1_stats,$user2_stats,$circuit['config'],$circuit['laps'],$user1_total_race,$user2_total_race,$user1_total_wins,$user2_total_wins,$ultimate_id,$race['racer1_id']);
		
		
		return $simulation;
	}
	function generate_report($events,$session_id,$race){
		$racer1 = mysql_escape_string($race['racer1_id']);
		$racer2 = mysql_escape_string($race['racer2_id']);
		$sql = "INSERT INTO ".GameDB.".racing_report 
				(game_session_id, published_date, details, racer1_id, racer2_id)
				VALUES
				('".$session_id."',NOW(), '".mysql_escape_string(serialize($events))."',".$racer1.",".$racer2.")";
		$q = $this->query($sql);
		
		$report_id = mysql_insert_id();
		$this->debug->info("generate report : ".$report_id);
		if($report_id>0){
			//update history
			$sql = "INSERT INTO ".GameDB.".racing_history 
					(user1_id, user2_id, winner, circuit_id, report_id, n_status)
					VALUES
					(".$racer1.", ".$racer2.", ".$events['winner'].", ".$race['circuit_id'].", ".$report_id.",1)";
			$q = $this->query($sql);
			$this->debug->status('add to history',$q);
			//$this->debug->info($sql);
			
			//update fixtures
			$sql = "INSERT IGNORE INTO ".GameDB.".racing_user_fixture(user_id,wins,loses,last_update)
					VALUES(".$racer1.",0,0,NOW())";
			$this->query($sql);
			
			$sql = "INSERT IGNORE INTO ".GameDB.".racing_user_fixture(user_id,wins,loses,last_update)
					VALUES(".$racer2.",0,0,NOW())";
			$this->query($sql);
			//print $events['winner']."==".$racer1."\n";
			if($racer1==$events['winner']){
				$sql = "UPDATE ".GameDB.".racing_user_fixture SET wins = wins+1 WHERE user_id=".$racer1."";
				$this->query($sql);
				$sql = "UPDATE ".GameDB.".racing_user_fixture SET loses = loses+1 WHERE user_id=".$racer2."";
				$this->query($sql);
			}else{
				$sql = "UPDATE ".GameDB.".racing_user_fixture SET wins = wins+1 WHERE user_id=".$racer2."";
				$this->query($sql);
				$sql = "UPDATE ".GameDB.".racing_user_fixture SET loses = loses+1 WHERE user_id=".$racer1."";
				$this->query($sql);
			}
			
		}
		return $q;
	}
	function races_today($user_id){
		$sql = "SELECT COUNT(id) as total_race FROM ".GameDB.".racing_session 
				WHERE (racer1_id = ".$user_id." OR racer2_id=".$user_id.") 
				AND DATE(race_date) = DATE(NOW()) 
				AND n_status=1 LIMIT 1";
		$rs = $this->fetch($sql);
		return $rs['total_race'];
	}
	function wins_today($user_id){
		$sql = "SELECT COUNT(b.winner) as total FROM ".GameDB.".racing_report a
				INNER JOIN ".GameDB.".racing_history b
				ON a.id = b.report_id 
				WHERE DATE(a.published_date) = DATE(NOW()) AND b.winner=".$user_id." LIMIT 1";
		$rs = $this->fetch($sql);
		//print $user_id."\n";
		return $rs['total'];
	}
	function get_report($session_id){
		$sql = "SELECT 	id, game_session_id, published_date, details, racer1_id, racer2_id 
				FROM 
				".GameDB.".racing_report
				WHERE game_session_id='".mysql_escape_string($session_id)."' 
				LIMIT 1";
		
		$rs = $this->fetch($sql);
		if(is_array($rs)){
			//get circuit info
			$sql = "SELECT b.id as circuit_id,b.name as circuit_name,b.brief,b.laps  
			FROM ".GameDB.".racing_session a
			INNER JOIN ".GameDB.".racing_circuit b
			ON a.circuit_id = b.id
			WHERE a.session_id='".mysql_escape_string($session_id)."'
			LIMIT 1";
			
			$circuit = $this->fetch($sql);
		
			$rs['circuit'] = $circuit;
		}
		return $rs;
	}
	
	function get_player_part_for_ultimate($user_id,$level){
		
	$sql ="
	SELECT count(inv.user_id) as totalPart
	FROM ".GameDB.".racing_user_inventory inv 
	WHERE user_id = {$user_id} 
	GROUP BY inv.user_id
	";
	
	$data= $this->fetch($sql);
	// return $sql;
	$partComplete = 15;
	if($level==3) $partComplete = 7;
	if($level==5) $partComplete = 13;
	
	if ($data['totalPart'] < $partComplete) $allow = 0;
	else $allow = 1;
	
	// $allow = 1;
	return $allow;
	
	}
	
	
	function simulate_race($user1,$user2,$tracks,$laps,$user1_total_race,$user2_total_race,$user1_total_wins=0,$user2_total_wins=0,$ultimate_id=99999,$user_id){
		global $WIN_PENALTY;
		$n = sizeof($tracks);
		$total_events = $n*$laps;
		
		$user1_prog = 0;
		$user2_prog = 0;
		
		$ultimate_car = $this->get_ultimate_car_by_id($ultimate_id);
		$player1 = $this->get_user($user_id);
		$partComplete = $this->get_player_part_for_ultimate($user_id,$player1['level']);
		$player1 = null;
		if($partComplete==0) $range_persentase_lose = 0;
		else $range_persentase_lose = $ultimate_car['persentase_lose'];
		
		$ultimate_lose_persentase = rand(1,100);
				
		$this->debug->info("Total Race Today : ".$user1_total_race." - ".$user2_total_race);
		$this->debug->info("Total Wins Today : ".$user1_total_wins." - ".$user2_total_wins);
		$this->debug->info("ultimate persen lose : ".$range_persentase_lose);
		$this->debug->info("user 1 role : ".$ultimate_lose_persentase);
		$events = array();
		for($i=0;$i<$laps;$i++){
			$this->debug->info("lap ".($i+1));
			for($j=0;$j<$n;$j++){
				
				$track = strtoupper($tracks[$j]);
				$user1_prog+=$user1[$track];
				
				if($ultimate_lose_persentase <= $range_persentase_lose ) 
					{	
						
						$losing_ability_ultimate =  ceil($user2[$track]-($user2[$track]*($range_persentase_lose/100))); //if ultimate car got lose on roll, count it penalty lose
						$user2track = $user2[$track]-$losing_ability_ultimate;
						$this->debug->info("ultimate losing ability : ".$losing_ability_ultimate.'-'.$user2[$track].'-'.$range_persentase_lose.'-'.($user2[$track]*($range_persentase_lose/100)));
						if($losing_ability_ultimate <= $user2[$track]) $user2_prog += $user2track;
						else  $user2_prog += $losing_ability_ultimate;
					}
				else {
				$user2track = ($user2[$track]+ceil($user2[$track]/2));
				$user2_prog+=$user2track; //if ultimate car safe from roll 
				}
				
				$this->debug->info($track." -> ".$user1[$track]." VS ".$user2track);
				$events[] = array("lap"=>($i+1),"track"=>$track,"user1_prog"=>$user1[$track],"user2_prog"=>$user2track);
			}
		}
		
		//if draw
		$this->debug->info("total -> ".$user1_prog." VS ".$user2_prog);
		if($user1_prog==$user2_prog){
			//roll dice to decide who wins
			$this->debug->info("it's a tie.. so we roll the dice to decide who's the winner");
			//user 1 rolls
			$roll1 = rand(1,12);
			//user 2 rolls
			do{
				$roll2 = rand(1,12);
			}while($roll2==$roll1);
			$this->debug->info($roll1." vs ".$roll2);
			//if user 1 is the winner.. then we decide which events that make the user2 losing pace
			if($roll1>$roll2){
				$t = rand(2,$total_events-3);
				foreach($events as $n=>$v){
					if($n>=$t){
						$events[$n]['user1_prog']=$events[$n]['user1_prog']-1;
					}
					if($n==$t){
						$events[$n]['state'] = 4;
					}
				}
				
			}else{
				//if user 2 is the winner.. then we decide which events that make the user 1 losing pace.
				$t = rand(2,$total_events-3);
				foreach($events as $n=>$v){
					if($n>=$t){
						$events[$n]['user1_prog']=$events[$n]['user1_prog']-1;
					}
					if($n==$t){
						$events[$n]['state'] = 4;
					}
				}
			}
		}else{
			//special conditions.
			//if one of the winning user have won more than 10 or so... we'll role for he's chance of losing
			if($user1_prog>$user2_prog){
				if($user1_total_wins>10&&$user1_total_wins<20){
					$penalty = $WIN_PENALTY[0];	
				}else if($user1_total_wins>20&&$user1_total_wins<30){
					$penalty = $WIN_PENALTY[1];	
				}else if($user1_total_wins>30){
					$penalty = $WIN_PENALTY[2];	
				}else{
					$penalty = 0;
				}
				if($penalty>0){
					$safe_area = $penalty*100;
					$this->debug->info("user#1 got winning penalty : ".$safe_area);
				}
				$roll = rand(0,100);
				$this->debug->info("ROLL : ".$roll);
				if($roll<=$safe_area){
					$this->debug->info("user#1 is losing");
					$t = rand(2,$total_events-3);
					foreach($events as $n=>$v){
						if($n>=$t){
							$events[$n]['user1_prog']=$events[$n]['user1_prog']-1;
						}
						if($n==$t){
							$events[$n]['state'] = 4;
						}
					}
				}
			}else{
				if($user2_total_wins>10&&$user2_total_wins<20){
					$penalty = $WIN_PENALTY[0];	
				}else if($user2_total_wins>20&&$user2_total_wins<30){
					$penalty = $WIN_PENALTY[1];	
				}else if($user2_total_wins>30){
					$penalty = $WIN_PENALTY[2];	
				}else{
					$penalty = 0;
				}
				if($penalty>0){
					$safe_area = $penalty*100;
					$this->debug->info("user#2 got winning penalty : ".$safe_area);
				}
				$roll = rand(0,100);
				$this->debug->info("ROLL: ".$roll);
				if($roll<=$safe_area){
					$this->debug->info("user#1 is losing");
					$t = rand(2,$total_events-3);
					foreach($events as $n=>$v){
						if($n>=$t){
							$events[$n]['user1_prog']=$events[$n]['user1_prog']-1;
						}
						if($n==$t){
							$events[$n]['state'] = 4;
						}
					}
				}
			}
		}
		//create racing states
		//states : ->
		// 0 tie position
		// 1 leading position
		// 2 user1 catching up
		// 3 user2 catching up
		$state=0;
		foreach($events as $n=>$v){
			if($events[$n]['user1_prog']==$events[$n]['user2_prog']){
				if($state==1){
					//jika state sebelumnya leading
					if($events[$n-1]['user1_prog']<$events[$n-1]['user2_prog']){
						$state=2;
					}else{
						$state=3;
					}
				}else{
					$state=0;
				}
			}else{
				$state=1;
			}
			if($events[$n]['state']!=4){
				$events[$n]['state']=$state;
			}
		}
		return $events;
	}
	function get_driver_stats($user_id){
		$sql = "SELECT SUM(ss) as SS,SUM(ls) as LS,SUM(sc) as SC,SUM(lc) as LC FROM ".GameDB.".racing_user_inventory a
				INNER JOIN ".GameDB.".racing_parts_inventory b
				ON a.parts_id = b.id
				WHERE a.user_id=".$user_id." LIMIT 1";
		$rs = $this->fetch($sql);
		return $rs;
	}
	function set_session_status($session_id,$status){
		$session_id = mysql_escape_string($session_id);
		$status = intval($status);
		$sql = "UPDATE ".GameDB.".racing_session SET n_status=".$status." WHERE session_id='".$session_id."'";
		$rs = $this->query($sql);
		return $rs;
	}
	function add_circuit($name,$track_config,$brief){
		$sql = "INSERT INTO redrush_game.racing_circuit 
				( name, track_config, brief)
				values
				('".mysql_escape_string($name)."',
				'".mysql_escape_string(serialize($track_config))."',
				'".mysql_escape_string($brief)."')";
		return $this->query($sql);
	}
	function get_circuit_detail($circuit_id){
		$circuit_id = intval($circuit_id);
		$sql = "SELECT * FROM ".GameDB.".racing_circuit WHERE id = ".$circuit_id." LIMIT 1";
		$rs = $this->fetch($sql);
		
		return $rs;
	}
	function get_circuits(){
		$sql = "SELECT * FROM ".GameDB.".racing_circuit";
		return $this->fetch($sql,1);
	}
	
	
	function apikey_valid($game_id,$gameApiKey){
		$game_id = intval($game_id);
		if($game_id>0 && strlen($gameApiKey)>0){
			$sql = "SELECT game_id, api_key 
					FROM 
					".RedRushDB.".game_api_key
					WHERE 
					game_id=".$game_id." 
					AND api_key='".mysql_escape_string($gameApiKey)."'
					limit 1";
			$rs = $this->fetch($sql);
			if($rs['game_id']==$game_id&&$rs['api_key']==$gameApiKey){
				return true;	
			}
		}
	}
	function generate_session($user_id,$game_id,$gameApiKey){
		$user_id = intval($user_id);
		$game_id = intval($game_id);
		$gameApiKey = mysql_escape_string($gameApiKey);
		$session_id = $this->get_old_sessions($user_id,$game_id);
		
		if(strlen($session_id)==0){
			
			$session_id = sha1(date("Ymdhis").$user_id.$game_id.$gameApiKey);
			$_SESSION['session_id'] = $session_id;
			if(!$this->add_session($user_id,$game_id,$session_id)){
				$session_id=null;
			}
		}
		return $session_id;
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
	
	function save_score($user_id,$game_id,$score,$session_token){
		
		$user_id = intval($user_id);
		$game_id = intval($game_id);
		$score = intval($score);
		if(strlen($session_token)>10){
			
			$old_session = $this->get_old_sessions($user_id,$game_id);
			
			if($session_token==$old_session){
				
					$sql = "INSERT INTO ".RedRushDB.".game_score
							(game_id, user_id, submit_time, submit_time_ts, score, level_id, game_session_token)
							values
							(".$game_id.", ".$user_id.", NOW(), ".time().", ".$score.", 0,'".$session_token."')";
					$qData = $this->query($sql);
					
					if($qData){	
						$this->update_session_token($user_id,$game_id,$session_token,1);
						return true;
					}
				
			}
		}
	
	}
	
	
	
	
	
}
?>