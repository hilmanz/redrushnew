<?php
class apiHelper{
	/**
	 * halaman garages lawan
	 */
	function getPlayerData($user_id){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "racing_service";
		$params['method']  = "get_car_profile";
		$params['user_id'] = $user_id;
		$params['apikey'] = $REDRUSH_APIKEY;
		
		//randomize circuit
		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		$data = $o->data;
		$speed = $data->car->LS + 10;
		$handling = round($data->car->SC/2)+($data->car->LC/2) + 10;
		$acceleration = $data->car->SS + 10;
		// print_r('<pre>');print_r($data->user); exit;
		//maksimum statsnya itu 36
		if(! $data->user->nickname) $name = $data->user->name;
		else $name = $data->user->nickname;
		
		$data = array(
					"id"=>$data->user->id,
					"name"=>$name,
					"img"=>$data->user->img,
					"small_img"=>$data->user->small_img,
					"qr_img"=>$data->user->path_qr_code,
					"qr_code"=>$data->user->code,
					"title"=>$data->user_stat->title,
					"level"=>$data->user->level,
					"rank"=>$data->user_stat->rank,
					"points"=>$data->gamePoint,
					"races"=>$data->user_stat->races,
					"wins"=>$data->user_stat->wins,
					"verified"=>$data->user->verified,
					"attributes"=>
						array(
						"speed"=>round(($speed/36)*100),
						"handling"=>round(($handling/36)*100),
						"acceleration"=>round(($acceleration/36)*100)
						));
			
		return json_encode($data);
	}
	/*
	 * ini buat halaman race report (challenge)
	 * @race_request string a serialized hash contains player 1 and player 2's user_id
	 */
	function getRaceReport($race_request){
		global $GAME_API,$REDRUSH_APIKEY;
		
		
		$players = unserialize(urldecode64($race_request));
			
		//caps 50 / 10 playing
		//opponent
		$caps50opponent = json_decode($this->get_user_caps_50($players['player2']));
		if($caps50opponent->result==0) {
		$caps50opponent = 1;
		$data = array("caps50opponent"=>$caps50opponent);
		return json_encode($data);
		}else  $caps50opponent = 0;
		
		//player
		$caps50 = json_decode($this->get_user_caps_50($players['player1']));
		if($caps50->result==0) {
		$caps50 = 1;
		$data = array("caps50"=>$caps50);
		return json_encode($data);
		}else  $caps50 = 0;
		$caps10 = json_decode($this->get_user_caps_10_per_player($players['player1'],$players['player2']));
		if($caps10->result==0) {
		$caps10 = 1;
		$data = array("caps50"=>$caps50,"caps10"=>$caps10);
		return json_encode($data);
		}else $caps10 = 0;
			
		$params = array();
		$params['service'] = "racing_service";
		$params['method']  = "get_circuit";
		$params['apikey'] = $REDRUSH_APIKEY;
		
		//randomize circuit
		$out = curl_get($GAME_API,$params);
		$o = json_decode($out['response']);
		
		$circuit_id = $o->data;
		
		
		//get circuit details
		$params['method'] = "get_circuit_detail";
		$params['circuit_id'] = $circuit_id;
		$out = curl_get($GAME_API,$params);
		$o = json_decode($out['response']);
		$circuit = $o->data;
		
		//create race session id
		$params['method'] = "get_session";
		$params['circuit_id'] = $circuit_id;
		$params['racer1'] = $players['player1'];
		$params['racer2'] = $players['player2'];
		$out = curl_get($GAME_API,$params);
		$o = json_decode($out['response']);
		$race_session_id = $o->data;
		
		//generate race report
		$params['method'] = "race";
		$params['session_id'] = $race_session_id;
		$params['status'] = 1;
		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		$race_report = $o->data;
		// print_r('<pre>');print_r($race_report);exit;
		//player 1
		$player1 = json_decode($this->getPlayerData($players['player1']));
		//player 2
		$player2 = json_decode($this->getPlayerData($players['player2']));
		if($o->status==1){
		
		//caps 50 / 10 playing
		// $caps50 = json_decode($this->get_user_caps_50($players['player1']));
		// if($caps50->result==0) $caps50 = 1;
		// else  $caps50 = 0;
		// $caps10 = json_decode($this->get_user_caps_10_per_player($players['player1'],$players['player2']));
		// if($caps10->result==0) $caps10 = 1;
		// else $caps10 = 0;
		
			$data = array(
					"circuit_name"=>$circuit->name,
					"circuit_distance"=>$circuit->laps."KM",
					"circuit_desc"=>$circuit->brief,
					"race_session_id"=>$race_session_id,
					"caps50"=>$caps50,
					"caps50opponent"=>$caps50opponent,
					"caps10"=>$caps10,
					"results"=>$race_report->raw,
					"player1"=>$player1,
					"player2"=>$player2,
					"txt"=>$race_report->txt);
			return json_encode($data);
		}
	}
	
	
	function getRaceReport_ultimate($race_request){
		global $GAME_API,$REDRUSH_APIKEY;
		
		
		$players = unserialize(urldecode64($race_request));
				
		$params = array();
		$params['service'] = "ultimate_racing_service";
		$params['method']  = "get_circuit";
		$params['apikey'] = $REDRUSH_APIKEY;
		
		//randomize circuit
		$out = curl_get($GAME_API,$params);
		$o = json_decode($out['response']);
		
		$circuit_id = $o->data;
		
		
		//get circuit details
		$params['method'] = "get_circuit_detail";
		$params['circuit_id'] = $circuit_id;
		$out = curl_get($GAME_API,$params);
		$o = json_decode($out['response']);
		$circuit = $o->data;
		
		//create race session id
		$params['method'] = "get_session";
		$params['circuit_id'] = $circuit_id;
		$params['racer1'] = $players['player1'];
		$params['racer2'] = $players['player2'];
		$out = curl_get($GAME_API,$params);
		$o = json_decode($out['response']);
		$race_session_id = $o->data;
		
		//generate race report
		$params['method'] = "race";
		$params['session_id'] = $race_session_id;
		$params['status'] = 1;
		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		$race_report = $o->data;
		// print_r('<pre>');print_r($race_report);exit;
		//player 1
		$player1 = json_decode($this->getPlayerData($players['player1']));
		//player 2
		$player2 = json_decode($this->get_ultimate_car_by_id($players['player2']));
		if($o->status==1){
		
			$data = array(
					"circuit_name"=>$circuit->name,
					"circuit_distance"=>$circuit->laps."KM",
					"circuit_desc"=>$circuit->brief,
					"race_session_id"=>$race_session_id,
					"results"=>$race_report->raw,
					"player1"=>$player1,
					"player2"=>$player2,
					"txt"=>$race_report->txt);
			return json_encode($data);
		}
	}
	/**
	 * ini buat halaman result (finish)
	 */
	function getRaceResult($session_id,$user_id,$opponent_id){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "racing_service";
		$params['method']  = "get_report";
		$params['session_id'] = $session_id;
		$params['apikey'] = $REDRUSH_APIKEY;
		
		//randomize circuit
		$out = curl_get($GAME_API,$params);
		$o = json_decode($out['response']);
		
		$details = unserialize($o->data->details);
		
		if($details['winner']==$user_id){
			$is_winner = 1;
		}else{
			$is_winner = 0;
		}
		
		$data = array("user_id"=>$user_id,
					"opponent_id"=>$opponent_id,
					"point"=>5,
					"is_winner"=>$is_winner,
					"circuit_name"=>$o->data->circuit->circuit_name,
					"circuit_distance"=>$o->data->circuit->laps."KM",
					"circuit_desc"=>$o->data->circuit->brief);
		
		return json_encode($data);
	}
	
	/**
	* Purchasing Part
	*/
	
	function getRacingPart($level_user=1){
		
		global $GAME_API,$REDRUSH_APIKEY;
		if($level_user==NULL) $level_user = 1;
		// print_r('<pre>');print_r($level_user);		exit;	
		$params = array();
		$params['service'] = "parts_service";
		$params['method']  = "get_part";
		$params['level_user'] = $level_user;
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
							
		return json_encode($data);
	}
	
	function getOwnPart($user_id,$part_id){
		
		global $GAME_API,$REDRUSH_APIKEY;
		// print_r('<pre>');print_r($level_user);		exit;	
		$params = array();
		$params['service'] = "parts_service";
		$params['method']  = "get_own_part";
		$params['user_id'] = $user_id;
		$params['part_id'] = $part_id;
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
							
		return json_encode($data);
	}
	
	function purchase_part($user_id,$part_id){
		
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "parts_service";
		$params['method']  = "purchase_part";
		$params['user_id'] = $user_id;
		$params['part_id'] = $part_id;
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
		// print_r('<pre>');print_r($data);		exit;						
		return json_encode($data);
	}
	
	function cek_winning_ultimate_car($user_id){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "parts_service";
		$params['method']  = "cek_winning_ultimate_car";
		$params['user_id'] = $user_id;
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		// print_r($out);exit;
		$o = json_decode($out['response']);
		// print_r($o);exit;
		$data = $o->data;
		// print_r('<pre>');print_r($data);		exit;						
		return json_encode($data);
	}
	
	//top user
	
	function getTopUser($limit){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "user_rank_service";
		$params['method']  = "get_top_user";
		$params['limit'] = $limit;
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
		// print_r('<pre>');print_r($data);		exit;						
		return json_encode($data);
	}
	
	
	// race point-- session token
	function addSessionRaceToken($user_id,$game_id=5){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params = array();
		$params['service'] = "racing_service";
		$params['method']  = "generate_session";
		$params['user_id'] = $user_id;
		$params['game_id'] = $game_id;
		$params['key'] = '99912222';
		
		$params['apikey'] = $REDRUSH_APIKEY;
	
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
		
		// print_r($data);exit;
		return json_encode($data);
	
	
	}
	
	function addGameRacePoint($user_id,$session_game_key,$score=5,$game_id=5){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "racing_service";
		$params['method']  = "save_score";
		$params['user_id'] = $user_id;
		$params['game_id'] = $game_id;
		$params['score'] = $score;
		$params['session_token'] = $session_game_key;
		
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
		return json_encode($data);
	
	
	}
	
	
	// merchandise
	
	function getMerchandise($user_id){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "merchandise_service";
		$params['method']  = "get_merchandise";
		$params['user_id'] = $user_id;
	
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
		
		// print_r($data);exit;
		return json_encode($data);
	
	
	}
	
	function getMerchandiseByID($merchandise_id){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "merchandise_service";
		$params['method']  = "get_merchandiseByID";
		$params['merchandise_id'] = $merchandise_id;
	
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
		
		// print_r($data);exit;
		return json_encode($data);
	
	
	}
	
	
	function purchaseMerchandise($user_id,$merchandise_id,$data_personal){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "merchandise_service";
		$params['method']  = "purchase_merchandise";
		$params['user_id'] = $user_id;
		$params['merchandise_id'] = $merchandise_id;
		$params['address'] = $data_personal['address'];
		$params['zip_code'] = $data_personal['zip_code'];
		$params['phone'] = $data_personal['phone'];
		$params['mobile'] = $data_personal['mobile'];
		$params['city_name'] = $data_personal['city_name'];
		$params['variant'] = $data_personal['variant'];
		
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
		// print_r('<pre>');print_r($out);exit;
		return json_encode($data);
	
	
	}
		
	function getOwnMerchandise($user_id,$merchandise_id){
		
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "merchandise_service";
		$params['method']  = "get_own_merchandise";
		$params['user_id'] = $user_id;
		$params['merchandise_id'] = $merchandise_id;
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
		// print_r($data);exit;					
		return json_encode($data);
	}
	
	
	function add_user_title($user_id){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "user_rank_service";
		$params['method']  = "add_title_user";
		$params['user_id'] = $user_id;
				
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
		// print_r('<pre>');	print_r($data);exit;	
		return json_encode($data);
	
	
	}
	
	function add_title_to_all_user($process){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "user_rank_service";
		$params['method']  = "add_title_to_all_user";
		$params['process'] = $process;
				
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
		// print_r('<pre>');	print_r($data);exit;	
		return json_encode($data);
	
	
	}
	
	function add_qr_user($user_id){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "qrcode_service";
		$params['method']  = "add_qrcode_user";
		$params['user_id'] = $user_id;
				
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
	
		return json_encode($data);
	
	
	}
	
	
	function add_all_qr_user($process){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "qrcode_service";
		$params['method']  = "add_all_qrcode_user";
		$params['process'] = $process;
				
		$params['apikey'] = $REDRUSH_APIKEY;

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
			// print_r('<pre>');print_r($data);exit;
		return json_encode($data);
	
	
	}
	
	
	function get_user_notification($user_id,$total){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "user_service";
		$params['method']  = "get_user_notification";
		$params['user_id'] = $user_id;
		$params['total'] = $total;				
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
			// print_r('<pre>');print_r($data);exit;
		return json_encode($data);
	
	
	}
	
	function get_all_user_notification($total){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "user_service";
		$params['method']  = "get_all_user_notification";
		$params['total'] = $total;				
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
			// print_r('<pre>');print_r($data);exit;
		return json_encode($data);
	
	
	}
	
	function get_user_caps_50($user_id){
	global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "racing_service";
		$params['method']  = "caps50win";
		$params['user_id'] = $user_id;
				
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
	// print_r($data);exit;
		return json_encode($data);
	
	
	}
	
	
	function get_user_caps_10_per_player($user_id,$opponent_id){
	global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "racing_service";
		$params['method']  = "caps10peruser";
		$params['user_id'] = $user_id;
		$params['opponent_id'] = $opponent_id;		
		$params['apikey'] = $REDRUSH_APIKEY;
		
	
		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
			
		return json_encode($data);
	
	
	}
	
	function add_level_player($user_id,$part_id){
	global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "user_rank_service";
		$params['method']  = "add_level_player";
		$params['user_id'] = $user_id;
		$params['part_id'] = $part_id;		
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
		// print_r($data);exit;
		return json_encode($data);
	
	
	}
	
	function add_user_rank($process){
	global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "user_rank_service";
		$params['method']  = "add_user_rank";
		$params['process'] = $process;
		$params['apikey'] = $REDRUSH_APIKEY;
		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
		// print_r('<pre>');print_r($data);exit;
		return json_encode($data);
	}
	
	function find_player($search){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "user_service";
		$params['method']  = "find_player";
		$params['search'] = $search;
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
			// print_r('<pre>');print_r($data);exit;
		return json_encode($data);
	
	
	}
	
	function get_ultimate_car($user_id,$level){
		
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "racing_service";
		$params['method']  = "get_ultimate_car";
		$params['level'] = $level;
		$params['user_id'] = $user_id;
		$params['apikey'] = $REDRUSH_APIKEY;
		
		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
			// print_r('<pre>');print_r($data);exit;
		return json_encode($data);
	
	
	}
	
	function get_ultimate_car_by_id($ultimate_id){
		
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "racing_service";
		$params['method']  = "get_ultimate_car_by_id";
		$params['ultimate_id'] = $ultimate_id;
		$params['apikey'] = $REDRUSH_APIKEY;
		
		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
			// print_r('<pre>');print_r($data);exit;
		return json_encode($data);
	
	
	}
	
	function win_ultimate_car($user_id,$ultimate_car_id,$level,$get_point){
		
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "racing_service";
		$params['method']  = "win_ultimate_car";
		$params['user_id'] = $user_id;
		$params['ultimate_car_id'] = $ultimate_car_id;
		$params['level'] = $level;
		$params['get_point'] = $get_point;
		$params['apikey'] = $REDRUSH_APIKEY;
		
		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
			// print_r('<pre>');print_r($data);exit;
		return json_encode($data);
	
	
	}
		
	function first_login_got_point($user_id){
		
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "user_service";
		$params['method']  = "first_login_got_point";
		$params['user_id'] = $user_id;
		$params['apikey'] = $REDRUSH_APIKEY;
		
		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
			// print_r('<pre>');print_r($o);exit;
		return json_encode($data);
	
	
	}
	
	function send_user_notification_email($user_id,$opponent_id,$winner,$email_notification_report){
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "user_service";
		$params['method']  = "send_user_notification_email";
		$params['user_id'] = $user_id;
		$params['opponent_id'] = $opponent_id;
		$params['winner'] = $winner;
		
		$report = implode('|',$email_notification_report['report']);
		
		$params['report'] = $report;
		$params['circuit'] = $email_notification_report['circuit'];
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
			// print_r('<pre>');print_r($report);exit;
		return json_encode($data);
	
	
	}
	
	function change_profile($user_id,$nickname,$image,$small_image){
	global $GAME_API,$REDRUSH_APIKEY;
	
		$params = array();
		$params['service'] = "user_service";
		$params['method']  = "change_profile";
		$params['user_id'] = $user_id;
		$params['nickname'] = $nickname;
		$params['image'] = $image;
		$params['small_image'] = $small_image;
		$params['apikey'] = $REDRUSH_APIKEY;
		

		
		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
			// print_r('<pre>');print_r($report);exit;
		return json_encode($data);
	
	}
	
	function add_progress_bar($part_id){
	global $GAME_API,$REDRUSH_APIKEY;
	
		$params = array();
		$params['service'] = "parts_service";
		$params['method']  = "get_part_by_id";
		$params['part_id'] = $part_id;
		$params['apikey'] = $REDRUSH_APIKEY;
		

		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
		// return json_encode($data);
		$speed = $data->ls;
		$handling = round($data->sc/2)+($data->lc/2);
		$acceleration = $data->ss;
		$progress = array("speed"=>round($speed),
						"handling"=>round($handling),
						"acceleration"=>round($acceleration));	

		return json_encode($progress);
	
						
	}
	
	
	function get_race_report_notification($report_id){
		
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "user_service";
		$params['method']  = "get_race_report_notification";
		$params['report_id'] = $report_id;
		$params['apikey'] = $REDRUSH_APIKEY;
		
		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
			// print_r('<pre>');print_r($o);exit;
		return json_encode($data);
	
	
	}
	
	function get_message_inbox_from_admin($user_id){
		
		global $GAME_API,$REDRUSH_APIKEY;
		
		$params = array();
		$params['service'] = "user_service";
		$params['method']  = "get_message_inbox_from_admin";
		$params['user_id'] = $user_id;
		$params['apikey'] = $REDRUSH_APIKEY;
		
		$out = curl_get($GAME_API,$params);
		
		$o = json_decode($out['response']);
		
		$data = $o->data;
			// print_r('<pre>');print_r($o);exit;
		return json_encode($data);
	
	
	}
		
}
?>