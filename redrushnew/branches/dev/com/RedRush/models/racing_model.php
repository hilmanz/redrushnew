<?php 
class racing_model extends SQLData{
	var $logs;
	var $debug;
	private $penalties = array(0.05,0.08,0.1);//>10 wins, > 20 wins, > 30 wins
	private $arr_top_user = array();
	function __construct(){
		global $ENGINE_PATH;
		include_once $ENGINE_PATH."Utility/Debugger.php";
		parent::SQLData();
		$this->debug = new Debugger();
	}
	function get_user($user_id){
		//$sql = "SELECT * FROM ".RedRushDB.".kana_member WHERE id=".$user_id." LIMIT 1";
		$sql = "SELECT a.*,b.level,c.path_qr_code,c.code FROM ".RedRushDB.".kana_member a
				LEFT JOIN ".GameDB.".racing_level b	ON a.id = b.user_id
				LEFT JOIN ".RedRushDB.".tbl_qr_code_user c	ON a.id = c.user_id
				WHERE a.id=".$user_id." LIMIT 1";
		return $this->fetch($sql);
	}
	
	function get_user_stat($user_id){
		
		$sql = "SELECT count(winner) as totalMenang FROM ".GameDB.".racing_history
				WHERE winner=".$user_id." ";
		$wins = $this->fetch($sql);
		$sql = "SELECT count(id) as totalRaces FROM ".GameDB.".racing_history
				WHERE user1_id=".$user_id." or user2_id=".$user_id."";
		$races = $this->fetch($sql);
		$title = $this->get_title_user($user_id);
		$rank = $this->get_rank_user($user_id);
		return array('wins'=>$wins['totalMenang'],'races'=>$races['totalRaces'],'title'=>$title['title_name'],'rank'=>$rank);
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
		$user1_total_race = $this->races_today($race['racer1_id']);
		$user2_total_race = $this->races_today($race['racer2_id']);
		
		$user1_total_wins = $this->wins_today($race['racer1_id']);
		$user2_total_wins = $this->wins_today($race['racer2_id']);
		
		$user1_stats = $this->get_driver_stats($race['racer1_id']);
		$user2_stats = $this->get_driver_stats($race['racer2_id']);
		$this->debug->info("driver 1 : ".$user1_stats['LS'].",".$user1_stats['SS'].",".$user1_stats['LC'].",".$user1_stats['SC']);
		$this->debug->info("driver 2 : ".$user2_stats['LS'].",".$user2_stats['SS'].",".$user2_stats['LC'].",".$user2_stats['SC']);
		//-->
		//var_dump($user1_stats);
		//var_dump($user2_stats);
		
		//get circuit details
		$circuit = $this->get_circuit_detail($race['circuit_id']);
		$circuit['config'] = unserialize($circuit['track_config']);
		//var_dump($circuit);
		$this->debug->info("Circuit ".$circuit['id']." ".$circuit['laps']." Laps : ".json_encode($circuit['config']));
		
		//simulate the race
		$simulation = $this->simulate_race($user1_stats,$user2_stats,$circuit['config'],$circuit['laps'],$user1_total_race,$user2_total_race,$user1_total_wins,$user2_total_wins);
		
		
		return $simulation;
	}
	
	function get_level_user($user_id){
	$sql = "SELECT level FROM ".GameDB.".racing_level WHERE user_id={$user_id} LIMIT 1";
	$data = $this->fetch($sql);
	return $data['level'];
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
			//update history, racer1_level 	racer2_level
			$sql = "INSERT INTO ".GameDB.".racing_history 
					(user1_id, user2_id, winner, circuit_id, report_id, n_status, racer1_level ,	racer2_level)
					VALUES
					(".$racer1.", ".$racer2.", ".$events['winner'].", ".$race['circuit_id'].", ".$report_id.",1,".$this->get_level_user($racer1).",".$this->get_level_user($racer2).")";
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
	function simulate_race($user1,$user2,$tracks,$laps,$user1_total_race,$user2_total_race,$user1_total_wins=0,$user2_total_wins=0){
		global $WIN_PENALTY;
		$n = sizeof($tracks);
		$total_events = $n*$laps;
		
		$user1_prog = 0;
		$user2_prog = 0;
		
		
		
		$this->debug->info("Total Race Today : ".$user1_total_race." - ".$user2_total_race);
		$this->debug->info("Total Wins Today : ".$user1_total_wins." - ".$user2_total_wins);
		$events = array();
		for($i=0;$i<$laps;$i++){
			$this->debug->info("lap ".($i+1));
			for($j=0;$j<$n;$j++){
				$track = strtoupper($tracks[$j]);
				$user1_prog+=$user1[$track];
				$user2_prog+=$user2[$track];
				$this->debug->info($track." -> ".$user1[$track]." VS ".$user2[$track]);
				$events[] = array("lap"=>($i+1),"track"=>$track,"user1_prog"=>$user1_prog,"user2_prog"=>$user2_prog);
			}
		}
		
		//if draw
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
						$events[$n]['user2_prog']= $events[$n]['user2_prog']-1;
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
				$roll = rand(1,100);
				$this->debug->info("ROLL : ".$roll);
				if($roll<=$safe_area){
					$this->debug->info("user#1 is losing");
					$t = rand(2,$total_events-3);
					foreach($events as $n=>$v){
						if($n>=$t){
							$events[$n]['user1_prog']=$events[$n]['user1_prog']-($events[$n]['user1_prog']-1);
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
				$roll = rand(1,100);
				$this->debug->info("ROLL: ".$roll);
				if($roll<=$safe_area){
					$this->debug->info("user#2 is losing");
					$t = rand(2,$total_events-3);
					foreach($events as $n=>$v){
						if($n>=$t){
							$events[$n]['user2_prog']= $events[$n]['user2_prog']-( $events[$n]['user2_prog']-1);
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
	
	
	//purchase part - section
	
	function get_exp_point($user_id){
		
	$sql = "SELECT SUM(score) as totalEXP FROM ".RedRushDB.".tbl_exp_point WHERE user_id=".$user_id." ";
	return $this->fetch($sql);
	}
	
	function get_game_score($user_id){
		
	$sql = "SELECT SUM(score) as totalPoint FROM ".RedRushDB.".game_score WHERE user_id=".$user_id." ";
	return $this->fetch($sql);
	}
		
	function get_part_detail($part_id){
		$sql = "SELECT * FROM ".GameDB.".racing_parts_inventory WHERE id=".$part_id." LIMIT 1";
		return $this->fetch($sql);
	}
	
	function get_purchase_point($user_id){
		$sql = "SELECT SUM(point) as purchase_point FROM ".RedRushDB.".tbl_purchase_part WHERE user_id=".$user_id." ";
		return $this->fetch($sql);
	
	}
	function get_own_part($user_id,$part_id){
		$sql = "SELECT count(id) as total FROM ".RedRushDB.".tbl_purchase_part WHERE user_id=".$user_id." and part_id=".$part_id." ";
		$data =  $this->fetch($sql);
		return $data['total'];
	}
		
	
	
	//user point - title
	
	function userPoint($user_id){
		$gamePoint = $this->get_game_score($user_id);
		$purchase_part = $this->get_purchase_point($user_id);
		$purchase_merchandise  = $this->get_purchase_merchandise($user_id);
		$userPoint = $gamePoint['totalPoint'] - $purchase_part['purchase_point'] - $purchase_merchandise['purchased_prize'];
		if($userPoint <= 0 ) $userPoint=0;
		return $userPoint;
	} 
	function userTitleReferences($point,$typeUser){
	 	$sql = "SELECT id FROM tbl_ref_title WHERE 	type_user='".$typeUser."' AND  min_point<=".$point." AND max_point>=".$point."";
		return $this->fetch($sql);
	}
	
	function get_user_total_game($user_id){
		$sql = "SELECT user_id, game_id, count(game_id) as totalmain FROM game_score WHERE user_id=".$user_id." group by user_id, game_id";
		$data = $this->fetch($sql,1);
		foreach($data as $value){
		$arrGame['user_id'] = $value['user_id'];
		if($value['game_id']==1 || $value['game_id']==2 || $value['game_id']==3 || $value['game_id']==4 ) $arrGame['mini_game'] += $value['totalmain'];
		else $arrGame['race_game'] += $value['totalmain'];
		}
		return $arrGame;
	}
	
	function add_title_user($user_id){
	
		$game_score = $this->get_game_score($user_id);
		
		//ambil data total bermain mini game dan race game
		$total_game = $this->get_user_total_game($user_id);
		$typeUser = 1; // 1.gamer 2.racer
		if($total_game['mini_game']<$total_game['race_game']) $typeUser = 2;
		$total_game=NULL;
		
		$user_id = intval($user_id);
		$point = intval($game_score['totalPoint']);
		$game_score=NULL;
		
		//untuk membandingkan title sama atau tidak
		$get_title_user = $this->get_title_user($user_id);
		$old_title_id = $get_title_user['title_id'];
		if($old_title_id==NULL) $old_title_id=0;
		
		$title = $this->userTitleReferences($point,$typeUser);
		$title_id = $title['id'];
		if($title_id==NULL ) $title_id=1;
		$title =NULL; $get_title_user=NULL;
		
	
		if($old_title_id!=$title_id){
		
		$sql = "DELETE FROM ".RedRushDB.".rr_user_title
				WHERE 
				user_id = ".$user_id."";
		$result = $this->query($sql);
			if($result){
			$sql = "INSERT INTO ".RedRushDB.".rr_user_title
					( user_id, title_id, point)
					values
					(
					".mysql_escape_string($user_id).",
					".mysql_escape_string($title_id).",
					".mysql_escape_string($point)."				
					)";
			$result = $this->query($sql);
			if($result) return array('message'=>'Sukses Mendapat Title','result'=>1);
			}else return array('message'=>'error pada database title ini','result'=>0);
		}elseif($old_title_id==0 && $title_id==0){
			$sql = "INSERT INTO ".RedRushDB.".rr_user_title
					( user_id, title_id, point)
					values
					(
					".mysql_escape_string($user_id).",
					".mysql_escape_string($title_id).",
					".mysql_escape_string($point)."				
					)";
			$result = $this->query($sql);
			if($result) return array('message'=>'Sukses Mendapat Title','result'=>1);
		}else return array('message'=>'error pada database title ini','result'=>0);
		
	}
	
	function get_title_user($user_id){
	 	$sql = "SELECT a.*,b.title_name
		FROM ".RedRushDB.".rr_user_title a 
		LEFT JOIN ".RedRushDB.".tbl_ref_title b  ON a.title_id = b.id
		WHERE user_id=".$user_id."";
		return $this->fetch($sql);
	}
	
	function add_title_to_all_user($process=false){
		if($process==false) return false;
		else{
			$sql = "SELECT distinct id FROM ".RedRushDB.".kana_member WHERE 1";
		
			$all_user_id = $this->fetch($sql,1);
			foreach($all_user_id as $value){
				$data = $this->add_title_user($value['id']);
				$arrData[] = $data['message'];
			}
			$data = NULL;
			return $arrData;
		}
	}
	
	//purchase part
	function purchase_part($user_id,$part_id){
		
		//use if user buy higher level part from him .. vacuum
		$part = $this->get_part($part_id);
		$partLevel = $part['level'];
		$sql = "SELECT level FROM ".GameDB.".racing_level WHERE user_id=".$user_id." LIMIT 1";
		$currentLevel = $this->fetch($sql);
		if($currentLevel['level']<$partLevel) return array('message'=>'You not have enough level to purchasing this part','result'=>0);
		$havePart = $this->get_own_part($user_id,$part_id);
		if($havePart<=0){
		$data = null;
		
		// $exp = $this->get_exp_point($user_id);
		$userPoint = $this->userPoint($user_id);
		
		$part =  $this->get_part_detail($part_id);
			
		if($userPoint >= $part['price']){
		
		$this->add_user_inventory($user_id,$part_id);
		
		$sql = "INSERT INTO ".RedRushDB.".tbl_purchase_part
				( user_id, part_id, point,date_time, date_time_ts)
				values
				(
				'".mysql_escape_string($user_id)."',
				'".mysql_escape_string($part_id)."',
				".mysql_escape_string($part['price']).",
				'".date('Y-m-d H:i:s')."',
				".time()."
				)";
						
			$result = $this->query($sql);
			if($result) return array('message'=>"you've successfully purchasing this part",'result'=>1);
		
		}else return array('message'=>"You don't have enough points to purchase this part",'result'=>0,'total'=>$userPoint);
		}else return array('message'=>'You have been purchased this part before','result'=>0);
		return array('message'=>'There is an error with the database.','result'=>0);
	}
	
	function cek_winning_ultimate_car($user_id){
		$qul = "SELECT * FROM ".RedRushDB.".rr_ultimate_car";
		$ultimate = $this->fetch($qul,1);
		// return $ultimate;
		$ada = array();
		foreach($ultimate as $ul){
		$q = "SELECT COUNT(user_id) total FROM ".RedRushDB.".tbl_ultimate_battle 
				WHERE user_id='".$user_id."' LIMIT 1";
		$t = $this->fetch($q);
		$ada[] = array( 'level'=>$ul['level'], 'total' => $t['total']);
		}
		return $ada;
	}
	
	function get_inventory_part($level){
		$sql = "SELECT * FROM ".GameDB.".racing_parts_inventory WHERE level <= ".(intval($level)+1)." AND n_status=1";
		return $this->fetch($sql,1);
	}
	
	function get_part($part_id){
		$sql = "SELECT level FROM ".GameDB.".racing_parts_inventory WHERE id=".$part_id." AND n_status=1 LIMIT 1";
		return $this->fetch($sql);
	}
	
	function get_part_id($part_id){
		$sql = "SELECT * FROM ".GameDB.".racing_parts_inventory WHERE id=".$part_id." AND n_status=1 LIMIT 1";
		return $this->fetch($sql);
	}
	
	function add_user_inventory($user_id,$part_id){
	//user_id 	parts_id 	n_status 	purchase_date 	purchase_date_ts
	$sql = "DELETE FROM ".GameDB.".racing_user_inventory WHERE user_id=".$user_id." AND parts_id=".$part_id."";
	$this->query($sql);
	
	$sql = "INSERT INTO ".GameDB.".racing_user_inventory 
					(user_id, parts_id, n_status, purchase_date, purchase_date_ts)
					VALUES
					(
					".mysql_escape_string($user_id).", 
					".mysql_escape_string($part_id).", 
					1, 
					'".date('Y-m-d H:i:s')."', 
					".time()."
					)";
		
	return $this->query($sql);
	}
	
	
	//top user
	
	function get_top_user($limit = 10 ){
		if($limit == 2) $limit = 10;
		$sql = "
		SELECT b.id,b.name,b.nickname,b.email,b.last_name,b.username,b.img,b.small_img,a.level 
		FROM	".RedRushDB.".kana_member b 
		LEFT JOIN ".GameDB.".racing_level a ON b.id = a.user_id
		LEFT JOIN ".RedRushDB.".tbl_rank_user e ON e.user_id=b.id
		ORDER BY a.level DESC,e.score DESC limit ".$limit." ";
		$rand_opponent = $this->fetch($sql,1);
		//random
			for($start=1;$start<=$limit;$start++){
				$queue = rand(0,sizeof($rand_opponent)-1);
				if(sizeof($rand_opponent) == sizeof($this->arr_top_user) ) $this->arr_top_user = null;
				if(! in_array($queue,$this->arr_top_user)) array_push($this->arr_top_user,$queue);
				else {
				while(in_array($queue,$this->arr_top_user)){
				$queue = rand(0,sizeof($rand_opponent)-1);
				}
				array_push($this->arr_top_user,$queue);
				}
				$x[] = $rand_opponent[$queue];
			}
		return $x;
	}
	
	function add_rank_user($process=false){
	if($process==true) {
	$sql = "
	TRUNCATE TABLE tbl_rank_user;";
	$this->query($sql);
	$sql = "INSERT IGNORE INTO tbl_rank_user (user_id,score)
	(
		SELECT exp.user_id, sum(score) as score  
		FROM ".RedRushDB.".tbl_exp_point exp
		LEFT JOIN  ".GameDB.".racing_level level
		ON exp.user_id = level.user_id
		GROUP BY user_id 
		ORDER BY level DESC , score DESC
		
	);"; 
	$result = $this->query($sql);
	}else return false;
	}
	
	// add race point
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
	
	//merchandise
	function get_merchandise_item(){
		$sql = "SELECT * FROM ".RedRushDB.".rr_merchandise WHERE n_status='1' ORDER BY level ASC";
		$data = $this->fetch($sql,1);
		
		$n=0;
		foreach($data as $val){
			$data[$n]['display_item'] = false;
			$amount = $this->checkMerchandiseStock($val['id']);
			$prize = $this->getMerchandiseStockPrize($val['id']);
		
			if($prize) $data[$n]['prize']=$prize;
			else{
			// $data[$n]['prize'] = $this->getMerchandiseByGroup($val['group_merchandise'],true);
			$primaryMerch = $this->getMerchandiseByGroup($val['group_merchandise'],true);
			$data[$n]['prize'] =  $primaryMerch['prize'];
			$data[$n]['amount'] =  $primaryMerch['amount'];
			$data[$n]['display_item'] = true;
			}
		
			if($amount) $data[$n]['amount'] = $amount;
			$n++;
		}
		
		return $data;
	}
	
	function get_merchandise_item_by_id($merchandise_id=NULL){
		$sql = "SELECT * FROM ".RedRushDB.".rr_merchandise WHERE id = ".intval($merchandise_id)."  LIMIT 1" ;
		$data =  $this->fetch($sql);
		$prize = $this->getMerchandiseStockPrize($data['id']);
		$data['prize']=$prize;
		return $data;
	}
	
	function get_purchase_merchandise($user_id){
		$sql = "SELECT SUM(prize) as purchased_prize FROM ".RedRushDB.".rr_purchase_merchandise WHERE user_id=".$user_id." AND n_status !='2' ";
		return $this->fetch($sql);
	}
	
	function get_own_merchandise($user_id,$merchandise_id){
	
		//get merch id from their group
		$group_merchandise = $merchandise_id;
		$q = "
				SELECT id FROM ".RedRushDB.".rr_merchandise
				WHERE group_merchandise ='".$group_merchandise."';";
		$r = $this->fetch($q,1);
		
		//get own part each merch id
		foreach($r as $val){
		$sql = "SELECT count(id) as total FROM ".RedRushDB.".rr_purchase_merchandise WHERE user_id=".$user_id." AND merchandise_id =".$val['id']." AND n_status !='2' ";
		$data =  $this->fetch($sql);
		if($data['total']>0) $ownMerch[] = 1;
		else $ownMerch[] = 0;
		}
		// print_r($r);exit;
		//conditional if each of value have own merch
		if(in_array(1,$ownMerch)) $ownMerchandise = 1;
		else $ownMerchandise = 0;
		
		//return own merch data
		return $ownMerchandise;
	}
	
	
	function purchase_merchandise($user_id,$merchandise_id,$data_personal){
		$ownMerchandise = $this->get_own_merchandise($user_id,$merchandise_id);
		$merchandisePrice = $this->get_merchandise_item_by_id($merchandise_id);
		$data_personal = $data_personal;
		$checkStock = $this->checkMerchandiseStock($merchandise_id);
		if($checkStock==true){
		if( $ownMerchandise <= 0){
		
		$userPoint = $this->userPoint($user_id);
		$purchased_id = $user_id.$merchandise_id.date('Ymd');
		//cek level
		$sql = "SELECT level FROM ".GameDB.".racing_level WHERE user_id=".$user_id." LIMIT 1";
		$currentLevel = $this->fetch($sql);
		if($currentLevel['level']<$merchandisePrice['level']) return array('message'=>'You not have enough level to purchasing this merchandise','result'=>0);
		
		if($userPoint >= $merchandisePrice['prize']){
		$sql = "
		INSERT INTO 
		".RedRushDB.".rr_purchase_merchandise
		(purchase_merchandise_id, user_id, merchandise_id, purchase_date, prize,variant,level) 
		VALUES 
		('".$purchased_id."',".$user_id.", ".$merchandise_id.", NOW(), ".$merchandisePrice['prize'].",'".$data_personal['variant']."',".$currentLevel['level'].");
		";
		
		$result = $this->query($sql);
		if($result) {
		$sql = "
		INSERT INTO 
		".RedRushDB.".tbl_form_merchandise
		(purchase_merchandise_id, user_id, city_name,address,phone,mobile,zip_code) 
		VALUES 
		('".$purchased_id."', ".$user_id.",'".$data_personal['city_name']."','".$data_personal['address']."','".$data_personal['phone']."','".$data_personal['mobile']."','".$data_personal['zip_code']."');
		";
		
		$result = $this->query($sql);
		
		if($result) return array('message'=>"you've successfully redeemed your merchandise",'result'=>1);
		} else return array('message'=>'There is an error with the database.','result'=>0);
		} else return array('message'=>'you do not have enough points to redeem this merchandise','result'=>0,'total'=>$userPoint);
		} else return array('message'=>'You have been redeemed this merchandise before' ,'result'=>0);
		} else  return array('message'=>'All merchandise items are currently out of stock due to great demand. We are doing our best to procure new stock as soon as possible. Please check this page next week for new stock. Thank you for your patience.' ,'result'=>0);
		return array('message'=>'There is an error with the database.','result'=>0);
	
	}
	
	function checkMerchandiseStock($id){
	
	//get merchandise all stock
	$sql="SELECT sum(amount) as total FROM ".RedRushDB.".tbl_merchandise_stock WHERE merchandise_id=".$id." GROUP BY merchandise_id";
	$mercData =  $this->fetch($sql);
	//get purchased merchandise
	$sql="SELECT count(merchandise_id) as total FROM ".RedRushDB.".rr_purchase_merchandise WHERE n_status in ('0','1') and merchandise_id=".$id." GROUP BY merchandise_id";
	$mercPurchaseData =  $this->fetch($sql);
	//count merchandise
	$currentStock = round($mercData['total'] - $mercPurchaseData['total']);

	if($currentStock<=0) return false;
	else return true;
	
	}
	
	function getMerchandiseStockPrize($id){
		//get merchandise all stock
		$sql="SELECT prize as total FROM ".RedRushDB.".tbl_merchandise_stock WHERE merchandise_id=".$id." ORDER BY date DESC LIMIT 1";
		$mercData =  $this->fetch($sql);

		return $mercData['total'];
		
		}
		
	function getMerchandiseByGroup($group_merchandise=null,$avg=false){
			if($group_merchandise==null)return false;
			//id 	item_name 	amount 	prize 	img 	created_date 	n_status 
			$q = "
				SELECT * FROM ".RedRushDB.".rr_merchandise
				WHERE group_merchandise ='".$group_merchandise."';";
			$r = $this->fetch($q,1);
		
			$n=0;
			foreach($r as $val){
			$prize = $this->getMerchandiseStockPrize($val['id']);
			$r[$n]['prize'] = $prize;			
			$n++;
			}
			
			if($avg==true){
				foreach($r as $valPrize){
				$sumPrize+=$valPrize['prize'];
				$sumAmount+=$this->checkMerchandiseStock($valPrize['id']);
				}
				// $r=ceil($sumPrize/(count($r)-1));
				$prize=ceil($sumPrize/(count($r)-1));
				$amount=$sumAmount ;
				$r['prize'] = $prize;
				$r['amount'] = $amount;
			}
			return $r;
		}
		
	//caps racing < 10 per user opponent racing game
	function caps10peruser($user_id,$opponent_id){
	$sql = "
	SELECT count(id) as total 
	FROM ".GameDB.".racing_history
	WHERE 
	(user1_id = ".$user_id." AND user2_id=".$opponent_id." OR user1_id = ".$opponent_id." AND user2_id=".$user_id.") 
	AND
	date_format(date_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d') AND
	n_status = 1	";
	
	$result = $this->fetch($sql);
	if($result['total'] < 10 ) $caps10peruser = array('result'=>1,'total'=>$result['total']);
	else $caps10peruser = array('result'=>0,'total'=>$result['total']);
	return $caps10peruser;
	}
	
	//caps racing < 50 winning racing game
	function caps50win($user_id){
	$sql = "
	SELECT count(id) as total 
	FROM ".GameDB.".racing_history
	WHERE 
	winner = ".$user_id." AND
	date_format(date_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d') AND
	n_status = 1
	";

	$result = $this->fetch($sql);
	
	if( $result['total'] < 50 ) $caps50win = array('result'=>1,'total'=>$result['total']);
	else $caps50win = array('result'=>0,'total'=>$result['total']);
	
	return $caps50win;
	}
	
	//level racer
	function level_player($user_id,$part_id){
	
	$sql = "SELECT level,count(user_id) as total FROM ".GameDB.".racing_level WHERE user_id=".$user_id." LIMIT 1";
	$cekUserInlevel = $this->fetch($sql);
	$currentLevel = $cekUserInlevel['level'];
	$new_level = $currentLevel+1;
	//check is he has been complete basic part
	$sql = "SELECT id FROM ".GameDB.".racing_parts_inventory WHERE level=".$currentLevel."";
	$getPartCurrentLevelPlayer = $this->fetch($sql,1);
	foreach($getPartCurrentLevelPlayer as $part_id){
	$sql = "SELECT parts_id FROM ".GameDB.".racing_user_inventory WHERE parts_id=".$part_id['id']." and user_id=".$user_id." LIMIT 1";
	$getPlayerPart = $this->fetch($sql);
	if($getPlayerPart) $hasPart[] = true;
	else $hasPart[] = false;
	}
	if(in_array(false,$hasPart)) return array('message'=>'','result'=>1);
	$old_level = $cekUserInlevel['level'];
	if($cekUserInlevel['total']<=0) { 
	$this->add_level_player($user_id,$new_level);
	return array('message'=>'You Now Level '.$new_level,'result'=>1);
	}
	else{
	if($old_level<$new_level) {
	//cek player has winning ultimate car
		if($old_level==3 || $old_level==5 ){
		$hasWinningUltimate = $this->check_winning_ultimate_car_for_level_up($user_id,$old_level);
		if($hasWinningUltimate==0) return array('message'=>'','result'=>1);
		}
	$this->update_level_player($user_id,$new_level);
	return array('message'=>"You're Now Level ".$new_level,'result'=>1);
	}else return array('message'=>'','result'=>1);
	}
	return  array('message'=>'There is an error with the database.','result'=>0);
	}
	
	
	function check_winning_ultimate_car_for_level_up($user_id,$level){
	$sql = " SELECT count(user_id) as total FROM ".RedRushDB.".tbl_ultimate_battle 
	WHERE user_id={$user_id} AND level={$level} LIMIT 1
	";
	$qLevel = $this->fetch($sql);
	return $qLevel['total'];
	
	}
	// function level_player($user_id,$part_id){
	
	// $sql = "SELECT level,count(user_id) as total FROM ".GameDB.".racing_level WHERE user_id=".$user_id." LIMIT 1";
	// $cekUserInlevel = $this->fetch($sql);
	// $part = $this->get_part($part_id);
	// $new_level = $part['level'];
	// $old_level = $cekUserInlevel['level'];
	// if($cekUserInlevel['total']<=0) { 
	// $this->add_level_player($user_id,$new_level);
	// return array('message'=>'You Now Level '.$new_level,'result'=>1);
	// }
	// else{
	// if($old_level<$new_level) {
	// $this->update_level_player($user_id,$new_level);
	// return array('message'=>"You're Now Level ".$new_level,'result'=>1);
	// }else return array('message'=>'','result'=>1);
	// }
	// return  array('message'=>'There is an error with the database.','result'=>0);
	// }
	
	function add_level_player($user_id,$new_level){
	$sql ="
		INSERT IGNORE INTO ".GameDB.".racing_level 
		(user_id,level) 
		VALUES 
		(".$user_id.",".$new_level.")		
		";
	
	$qLevel = $this->query($sql);
	
	$sql ="
		INSERT IGNORE INTO ".GameDB.".racing_level_history 
		(user_id,level,level_up_date) 
		VALUES 
		(".$user_id.",".$new_level.",NOW())		
		";
	
	$qLevelHistory = $this->query($sql);
	}
	
	function update_level_player($user_id,$new_level){
	$sql ="
		UPDATE ".GameDB.".racing_level 		
		SET user_id=".$user_id.",level = ".$new_level."
		WHERE user_id =".$user_id." LIMIT 1
		";
	
	$qLevel = $this->query($sql);
	$sql ="
		INSERT IGNORE INTO ".GameDB.".racing_level_history 
		(user_id,level,level_up_date) 
		VALUES 
		(".$user_id.",".$new_level.",NOW())		
		";
	
	$qLevelHistory = $this->query($sql);
	}
	
	function get_ultimate_car($level){
	$sql = "SELECT *,ultimate_id as id FROM ".RedRushDB.".rr_ultimate_car WHERE level=".$level." AND n_status=1 LIMIT 1";
	return $this->fetch($sql);
	
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
	
	$allow = 1;
	return $allow;
	
	}
	
	function get_ultimate_car_by_id($ultimate_id){
	$sql = "SELECT *,ultimate_id as id FROM ".RedRushDB.".rr_ultimate_car WHERE ultimate_id=".$ultimate_id." LIMIT 1";
	return $this->fetch($sql);
	
	}
	
	function get_ultimate_battle($user_id,$level){
	$sql = "SELECT count(user_id) as total FROM ".RedRushDB.".tbl_ultimate_battle WHERE user_id=".$user_id." AND level=".$level." ORDER BY battle_date DESC LIMIT 1";
	$data= $this->fetch($sql);
	// return $sql;
	return $data['total'];
	}
	
	
	function win_ultimate_car($user_id,$ultimate_car_id,$level){
	$sql ="
	INSERT INTO  ".RedRushDB.".tbl_ultimate_battle (ultimate_car_id,user_id,level,battle_date,battle_date_ts)
	VALUES 
	(".$ultimate_car_id.",".$user_id.",".$level.",now(),'".time()."')
	";
	$result= $this->query($sql);
	if($result)	return array('message'=>"You Won The Race",'result'=>1);
	else return array('message'=>"failed".$sql,'result'=>0);
	}
}
?>
