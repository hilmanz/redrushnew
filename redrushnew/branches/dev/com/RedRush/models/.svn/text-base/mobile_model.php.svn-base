<?php 
class mobile_model extends SQLData{
	var $logs;
	var $debug;
		private $activity_note = array();
		private $arr_use_equal = array();
	function __construct(){
		global $ENGINE_PATH;
		include_once $ENGINE_PATH."Utility/Debugger.php";
		parent::SQLData();
		$this->debug = new Debugger();
	}
	
	function get_access_token(){
	$sql = "SELECT api_key,secret_key FROM tbl_mobile_api_secret_key LIMIT 1";
	return $this->fetch($sql);
	
	}
	
	function get_user_id_from_register_id($register_id){
	$sql = "SELECT id FROM kana_member WHERE ( n_status=1 OR verified='1') AND register_id=".$register_id." LIMIT 1";
	$rs = $this->fetch($sql);
	return $rs['id'] ;
	}
	
	function get_player_stat($user_id){
		
		$sql = "SELECT count(winner) as totalMenang FROM ".GameDB.".racing_history
				WHERE winner=".$user_id." ";
		$wins = $this->fetch($sql);
		$sql = "SELECT count(id) as totalRaces FROM ".GameDB.".racing_history
				WHERE user1_id=".$user_id." or user2_id=".$user_id."";
		$races = $this->fetch($sql);		
		return array('wins'=>$wins['totalMenang'],'races'=>$races['totalRaces']);
	}

	function get_game_score($user_id){
		
	$sql = "SELECT SUM(score) as totalPoint FROM ".RedRushDB.".game_score WHERE user_id=".$user_id." ";
	return $this->fetch($sql);
	}
	
	function get_purchase_point($user_id){
		$sql = "SELECT SUM(point) as purchase_point FROM ".RedRushDB.".tbl_purchase_part WHERE user_id=".$user_id." ";
		return $this->fetch($sql);
	
	}
	
	function get_purchase_merchandise($user_id){
		$sql = "SELECT SUM(prize) as purchased_prize FROM ".RedRushDB.".rr_purchase_merchandise WHERE user_id=".$user_id." AND n_status !='2' ";
		return $this->fetch($sql);
	}
	
	function add_point_first_login($user_id,$mobile_type){
	$sql = "INSERT IGNORE ".RedRushDB.".tbl_first_login_activity (user_id,date_time,date_time_ts ) VALUES ({$user_id},NOW(),'".time()."')";
	$this->query($sql);
	$sql = "SELECT count(id) as total FROM ".RedRushDB.".game_score WHERE game_id=99 and user_id=".$user_id." LIMIT 1";
	$data = $this->fetch($sql);
	if($data['total']>0) return array('message'=>"You've been got first login points");
	$session_temp = sha1(date('YmdHis').$user_id.$mobile_type);	
	$sql = "SELECT points FROM ".RedRushDB.".tbl_first_login_point WHERE id=".$mobile_type." LIMIT 1";
	$data = $this->fetch($sql);
	$sql = "INSERT INTO ".RedRushDB.".game_score
	(game_id, user_id, submit_time, submit_time_ts, score, level_id, game_session_token) 
	VALUES 
	(99,".$user_id.",now(),'".time()."',".$data['points'].",0,'".$session_temp."')
	";
	$result =  $this->query($sql);
	$this->add_level_first_login($user_id);
	if($result) return array('message'=>"You've got first login event + ".$data['points']." point");
	else return array('message'=>"You've been got first login points");
	
	}
	
	function add_level_first_login($user_id){
	$level = "INSERT INTO ".GameDB.".racing_level
	(user_id, level) 
	VALUES 
	(".$user_id.",1)";
	$this->query($level);
	}
	
		
	function userPoint($user_id){
		$this->add_point_first_login($user_id,3);
		$gamePoint = $this->get_game_score($user_id);
		$purchase_part = $this->get_purchase_point($user_id);
		$purchase_merchandise  = $this->get_purchase_merchandise($user_id);
		$userPoint = $gamePoint['totalPoint'] - $purchase_part['purchase_point'] - $purchase_merchandise['purchased_prize'];
		if($userPoint<=0) $userPoint = 0;
		return $userPoint;
	} 
		
	
	function get_player($user_id){
		$sql = "SELECT a.*,b.level,c.path_qr_code,c.code FROM ".RedRushDB.".kana_member a
				LEFT JOIN ".GameDB.".racing_level b	ON a.id = b.user_id
				LEFT JOIN ".RedRushDB.".tbl_qr_code_user c	ON a.id = c.user_id
				WHERE a.id=".$user_id." AND a.verified='1' LIMIT 1";
				// return $sql;
		$rs = $this->fetch($sql);
		if($rs){
		if($rs['nickname']=='') $rs['nickname'] = $rs['name'] ;
		
		if($rs['last_name']!=NULL&&$rs['last_name']!='Array'){
			$rs['name'] = $rs['name']." ".$rs['last_name'];	
		}
		}
		
		
		return $rs;
	}
	
	function search_player($searchtxt){
		$searchtxt = mysql_escape_string($searchtxt);
		$sql = "SELECT id FROM ".RedRushDB.".kana_member
				WHERE ( id ='".$searchtxt."' OR  email like '%".$searchtxt."%' OR  last_name like '%".$searchtxt."%' OR name like '%{$searchtxt}%' OR nickname like '%{$searchtxt}%' OR last_name like '%{$searchtxt}%') 
				AND ( n_status=1 OR verified='1' ) LIMIT 1";
				// return $sql;
		return $this->fetch($sql);
	}
	
	
	function get_profile_player($user_id){

		$data = $this->get_player($user_id);
		// return $data;
		if($data){
		$dataStat = $this->get_player_stat($user_id);
		$playerPoint = $this->userPoint($user_id);
		
		$dataProfile = array(
						"playerid"=>$data['code'],
						"userid"=>$data['id'],
						"qrcodeurl"=>BASEURL.'contents/qr_code/'.$data['path_qr_code'],
						"fullname"=>$data['nickname'],
						"nickname"=>$data['nickname'],
						"level"=>$data['level'],
						"points"=>$playerPoint,
						"race"=>$dataStat['races'],
						"wins"=>$dataStat['wins'],
						"photo_small"=>BASEURL.'contents/avatar/small_avatar/'.$data['img'],
						"photo_big"=>BASEURL.'contents/avatar/big/'.$data['img'],
						"photo_medium"=>BASEURL.'contents/avatar/medium/'.$data['img'],
						"photo_profile"=>BASEURL.'contents/avatar/profile/'.$data['img'],
						);
	}else $dataProfile = array ('result'=>NULL,'message'=>'this account is not valid');
	return $dataProfile;
	}
	
	function search_profile_player($searchtxt){
	$searchtxt = $this->search_player($searchtxt);
	$user_id = $searchtxt['id'] ;
	// return $searchtxt;
	if($searchtxt){
	$data = $this->get_player($user_id);
	$dataStat = $this->get_player_stat($user_id);
	$playerPoint = $this->userPoint($user_id);
	
	$dataProfile = array(
					"playerid"=>$data['code'],
					"userid"=>$data['id'],
					"qrcodeurl"=>BASEURL.'contents/qr_code/'.$data['path_qr_code'],
					"fullname"=>$data['nickname'],
					"level"=>$data['level'],
					"points"=>$playerPoint,
					"race"=>$dataStat['races'],
					"wins"=>$dataStat['wins'],
					"photo_small"=>BASEURL.'contents/avatar/small_avatar/'.$data['img'],
					"photo_big"=>BASEURL.'contents/avatar/big/'.$data['img'],
					"photo_medium"=>BASEURL.'contents/avatar/medium/'.$data['img'],
					"photo_profile"=>BASEURL.'contents/avatar/profile/'.$data['img'],
					);

	}else $dataProfile = array ('result'=>NULL,'message'=>'this account is not valid');
	return $dataProfile;
	}
	
	function checkUser($username,$password){
	$username = mysql_escape_string(trim(strtolower($username)));
	$password = trim($password);
	
	$sql = "SELECT * FROM kana_member WHERE (n_status=1 OR verified='1') AND username='".$username."' LIMIT 1";
	$rs = $this->fetch($sql);

	$hash = sha1($password.$username.$rs['salt']);
		
	$sql = "SELECT id, count(id) as total FROM ".RedRushDB.".kana_member WHERE password='".$hash."' AND username='".$username."' AND  (n_status=1 OR verified='1') LIMIT 1";
		// return $sql;
	$data = $this->fetch($sql);
	return $data;
	}
	
	function user_login($username,$password){
		$data = array();
		$login = $this->checkUser($username,$password);
			// return $login;
		if($login['total'] > 0) {
		$user_id = $login['id'];
		$data['resultdata'] = array('code'=>'OK','msg'=>'Login Success');
		$data['profile'] = $this->get_profile_player($user_id);
	
		}else{
		$data['resultdata'] = array('code'=>'KO','msg'=>'Login failed');
		}
		
		return $data;
	}
	
	
	// function get_news_feed($page,$access_token){
		// $total= 5;
		// $page = mysql_escape_string(trim(strtolower($page)));
		// if($page==null || $page==0) $page=1;
		// $nextPage = $page+1;
		// $page = ($page*$total)-$total;
		// $sql = "SELECT id,title,detail as content,posted_date as datetime FROM ".RedRushDB.".rr_news ORDER BY posted_date DESC ";
		// $totalNewsFeed = count($this->fetch($sql,1));
		// $sql = "SELECT id,title,detail as content,posted_date as datetime FROM ".RedRushDB.".rr_news ORDER BY posted_date DESC LIMIT ".$page.",".$total."";
		// $news_feed = $this->fetch($sql,1);
		// $data['numrows'] = $totalNewsFeed;
		// $data['nexturl'] = 'http://preview.kanadigital.com/redrush/api/index.php?service=mobile_service&method=news_feed&page='.$nextPage."&access_token=".$access_token;
		// $data['rows'] = $news_feed;
		// return $data;
	// }
	
	function get_news_feed($page,$access_token){
		global $GAME_API;
		require_once APP_PATH.APPLICATION."/helper/dateHelper.php";
		$date = new dateHelper();
		$note =  array();
		$total= 5;
		$page = mysql_escape_string(trim(strtolower($page)));
		if($page==null || $page==0) $page=1;
		$nextPage = $page+1;
		// return 'masuk';
		$page = ($page*$total)-$total;
		$sql = "(SELECT a.user1_id as user_id, a.user2_id as action_value, concat(a.circuit_id,'_',a.winner) as info, a.date_time as date_time, 4 as action_id FROM ".GameDB.".racing_history a ORDER BY date_time DESC LIMIT 30)
				UNION
				(SELECT b.user_id as user_id, b.merchandise_id as action_value, b.prize as info , b.purchase_date as date_time, 6 as action_id FROM ".RedRushDB.".rr_purchase_merchandise b ORDER BY date_time DESC LIMIT 30)
				ORDER BY date_time DESC ";
			// return $sql;
		$totalTimeLine = count($this->fetch($sql,1));	
			
		$sql = "(SELECT a.user1_id as user_id, a.user2_id as action_value, concat(a.circuit_id,'_',a.winner) as info, a.date_time as date_time, 4 as action_id FROM ".GameDB.".racing_history a ORDER BY date_time DESC LIMIT 30)
				UNION
				(SELECT b.user_id as user_id, b.merchandise_id as action_value, b.prize as info , b.purchase_date as date_time, 6 as action_id FROM ".RedRushDB.".rr_purchase_merchandise b ORDER BY date_time DESC LIMIT 30)
				ORDER BY date_time DESC LIMIT ".$page.",".$total."";
		$timeline = $this->fetch($sql,1);
			
		foreach($timeline as $notification){
			$player = $this->get_profile_player($notification['user_id']);
			if($notification['action_id'] == 4 ) $note[] =  array('user_id'=>$player['userid'],'avatar'=>$player['photourl'],'fullname'=>$player['nickname'],'content'=>$this->get_user_race_notification($notification),'datetime'=>$date->datediff($notification['date_time'])); //racing
			if($notification['action_id'] == 6 ) $note[] =  array('user_id'=>$player['userid'],'avatar'=>$player['photourl'],'fullname'=>$player['nickname'],'content'=>$this->get_user_merchandise_notification($notification),'datetime'=>$date->datediff($notification['date_time']));  //purchase merchandise
		
		}
		$data['numrows'] = $totalTimeLine;
		$data['nexturl'] = $GAME_API."index.php?service=mobile_service&method=news_feed&userid=".$user_id."&page=".$nextPage."&access_token=".$access_token;
		$data['rows'] = $note;
		// return $sql;
		return $data;
	}
	
	function get_user_car_avatar($userid){
		$userid = mysql_escape_string(trim(strtolower($userid)));
		$player = $this->get_player($userid);
		$data = BASEURL.'contents/avatar/small/'.$player['small_img'];
		return $data;
	}
	
	//user time line
	function get_circuit_name($circuit_id){
	$sql = "SELECT name FROM ".GameDB.".racing_circuit WHERE id=".$circuit_id." LIMIT 1";
		$data = $this->fetch($sql);
	return $data['name'];
	}
	
	function get_merchandise_item_name($merchandise_id){
		$sql = "SELECT item_name FROM ".RedRushDB.".rr_merchandise WHERE id=".$merchandise_id." LIMIT 1";
		$data = $this->fetch($sql);
	return $data['item_name'];
	}
	
	function get_user_race_notification($notification){
	$player = $this->get_player($notification['user_id']);
	$opponent = $this->get_player($notification['action_value']);
	$arrInfo = explode('_',$notification['info']);
	$circuit = $this->get_circuit_name($arrInfo[0]);
	$winner =  $this->get_player($arrInfo[1]);

	if($notification['user_id']==$arrInfo[1]) $txt =  $this->note_activity($player['nickname'],$opponent['nickname']);
	if($notification['user_id']!=$arrInfo[1]) $txt = $this->note_activity($opponent['nickname'],$player['nickname']);
	$arrInfo =null;
	return strip_tags($txt);
	//$notification=null;
	// return "".$player['nickname']." racing with ".$opponent['nickname']." at ".$circuit.", ".$winner['nickname']." is the winner and got +5 Game Points";
	}
	
	function get_user_merchandise_notification($notification){
	$player = $this->get_player($notification['user_id']);
	$merchandise = $this->get_merchandise_item_name($notification['action_value']);
	
	return "".$player['nickname']." has bought merchandise ".$merchandise."";
	}
	
	
	function note_activity($winner,$loser){
		include "../config/activity_events.php";	
		$queue = rand(0,sizeof($recent_activity)-1);
			if(sizeof($recent_activity) == sizeof($this->activity_note) ) $this->activity_note = null;
			if(! in_array($queue,$this->activity_note)) array_push($this->activity_note,$queue);
			else {
			while(in_array($queue,$this->activity_note)){
			$queue = rand(0,sizeof($recent_activity)-1);
			}
			array_push($this->activity_note,$queue);
			}
		$txt = $recent_activity[$queue];
		return $txt;
	}
	
	function get_user_timeline($user_id,$page,$access_token){
		global $GAME_API;
		require_once APP_PATH.APPLICATION."/helper/dateHelper.php";
		$date = new dateHelper();
		$note =  array();
		$total= 5;
		$user_id = mysql_escape_string(trim(strtolower($user_id)));
		$page = mysql_escape_string(trim(strtolower($page)));
		if($page==null || $page==0) $page=1;
		$nextPage = $page+1;
		$page = ($page*$total)-$total;
		$sql = "(SELECT a.user1_id as user_id, a.user2_id as action_value, concat(a.circuit_id,'_',a.winner) as info, a.date_time as date_time, 4 as action_id FROM ".GameDB.".racing_history a WHERE user1_id=".$user_id." OR user2_id=".$user_id.")
				UNION
				(SELECT b.user_id as user_id, b.merchandise_id as action_value, b.prize as info , b.purchase_date as date_time, 6 as action_id FROM ".RedRushDB.".rr_purchase_merchandise b WHERE user_id=".$user_id.")
				ORDER BY date_time DESC ";
		$totalTimeLine = count($this->fetch($sql,1));		
		$sql = "(SELECT a.user1_id as user_id, a.user2_id as action_value, concat(a.circuit_id,'_',a.winner) as info, a.date_time as date_time, 4 as action_id FROM ".GameDB.".racing_history a WHERE user1_id=".$user_id." OR user2_id=".$user_id.")
				UNION
				(SELECT b.user_id as user_id, b.merchandise_id as action_value, b.prize as info , b.purchase_date as date_time, 6 as action_id FROM ".RedRushDB.".rr_purchase_merchandise b WHERE user_id=".$user_id.")
				ORDER BY date_time DESC LIMIT ".$page.",".$total."";
		$timeline = $this->fetch($sql,1);
		$player = $this->get_profile_player($user_id);
		foreach($timeline as $notification){
			$player1 = $this->get_profile_player($notification['user_id']);
			$player2 = $this->get_profile_player($notification['action_value']);
			$p1 = array("user_id"=>$player1['userid'],"name"=>$player1['nickname']);
			$p2 = array("user_id"=>$player2['userid'],"name"=>$player2['nickname']);
			if($notification['action_id'] == 4 ) $note[] =  array('entities'=>array($p1,$p2),'avatar'=>$player['photourl'],'fullname'=>$player['nickname'],'content'=>$this->get_user_race_notification($notification),'datetime'=>$date->datediff($notification['date_time'])); //racing
			if($notification['action_id'] == 6 ) $note[] =  array('entities'=>array($p1,$p2),'avatar'=>$player['photourl'],'fullname'=>$player['nickname'],'content'=>$this->get_user_merchandise_notification($notification),'datetime'=>$date->datediff($notification['date_time']));  //purchase merchandise
		
		}
		$data['numrows'] = $totalTimeLine;
		$data['nexturl'] = $GAME_API."index.php?service=mobile_service&method=user_timeline&userid=".$user_id."&page=".$nextPage."&access_token=".$access_token;
		$data['rows'] = $note;
		// return $sql;
		return $data;
	}
	
	function get_opponent($user_id=0,$searchtxt=null,$level=0,$page=0,$total=0,$all=true){
		//$user_id,null, $player['level'],0,5,false)
		$limit = '';
		$qLevel='';
		$qSearch = '';
		$qUserid = '';
	
		
			if($all==false) $limit=" LIMIT ".$page.",".$total." ";
			if($level!=0) $qLevel = " b.level<=".($level+1)." AND ";
			if($user_id!=0) $qUserid = " a.id <>".$user_id." AND  ";
			if($searchtxt!=null) $qSearch = ' AND (a.name like "%'.$searchtxt.'%" or a.nickname like "%'.$searchtxt.'%" or a.last_name like "%'.$searchtxt.'%" ) ';
			
		
		$sql = "SELECT a.id as userid,a.nickname as fullname,b.level FROM ".RedRushDB.".kana_member a
				LEFT JOIN ".GameDB.".racing_level b	ON a.id = b.user_id
				WHERE 
				".$qLevel." 
				".$qUserid."
				( a.n_status=1 OR a.verified ='1')
				".$qSearch." 
				ORDER BY b.level DESC 
				".$limit." ";
				// return $sql;
				
		return $this->fetch($sql,1);
	}
	
	function get_suggest_opponent($user_id){
	
	$user_id = mysql_escape_string(trim(strtolower($user_id)));
	$player = $this->get_player($user_id);
	$page = 1;
	$total = 5;
	$nextPage = $page+1;
	$page = ($page*$total)-$total;
	$opponent = $this->get_opponent($user_id,null, $player['level'],$page,$total,true);
	//random
			for($start=1;$start<=$total;$start++){
				$queue = rand(0,sizeof($opponent)-1);
				if(sizeof($opponent) == sizeof($this->arr_use_equal) ) $this->arr_use_equal = null;
				if(! in_array($queue,$this->arr_use_equal)) array_push($this->arr_use_equal,$queue);
				else {
				while(in_array($queue,$this->arr_use_equal)){
				$queue = rand(0,sizeof($opponent)-1);
				}
				array_push($this->arr_use_equal,$queue);
				}
				$x[] = $opponent[$queue];
			}
	$totalOpponent =  count($this->get_opponent($user_id,null, 0,0,0,true));
		$data['numrows'] = $totalOpponent;
		$data['rows'] = $x;
	return $data;
	
	}
	
	
	function search_opponent($user_id,$searchtxt,$page,$access_token){
	$user_id = mysql_escape_string(trim(strtolower($user_id)));
	$searchtxt = mysql_escape_string(trim(strtolower($searchtxt)));
	$page = mysql_escape_string(trim(strtolower($page)));
	if($page==null || $page==0) $page=1;
	$total = 5;
	$nextPage = $page+1;
	$page = ($page*$total)-$total;
	$opponent = $this->get_opponent($user_id,$searchtxt, 0,$page,$total,false);
	$totalOpponent =  count($this->get_opponent($user_id,null, 0,0,0,true));
		$data['numrows'] = $totalOpponent;
		$data['nexturl'] = $GAME_API."index.php?service=mobile_service&method=search_opponent&userid=".$user_id."&searchtxt=".$searchtxt."&page=".$nextPage."&access_token=".$access_token;
		$data['rows'] = $opponent;
	return $data;
	
	}
	
	
	function add_registration_ipad_data(){
	//{"timestamp":1334600596,"lastid":"5","numrows":5,"regdata":[
	//{"id":"1","firstname":"hugo","lastname":"adhityo","nickname":"hugo","birthdate":"1994-01-04","gender":"M","email":"hugoadhityo@yahoo.com","brandpref":"MARLBORO RED","survey_date":"2012-04-03 01:22:11","ba_name":"YESIKA AGUSTINA","ba_userid":"yesika.jkt"},
	//{"id":"2","firstname":"arsha","lastname":"algadri","nickname":"arsha","birthdate":"1991-03-25","gender":"M","email":"arsha_25@hotmail.com","brandpref":"MARLBORO LIGHTS","survey_date":"2012-04-03 16:46:10","ba_name":"ARSHA FUADI A.","ba_userid":"arsha.jkt"},
	//{"id":"3","firstname":"adillatest","lastname":"test","nickname":"test","birthdate":"1989-10-04","gender":"F","email":"dil_kuu@yahoo.com","brandpref":"AMILD","survey_date":"2012-04-03 04:19:37","ba_name":"Adillah Rahmawati","ba_userid":"dst28dps"},
	//{"id":"4","firstname":"gabbie","lastname":"thung","nickname":"gabe","birthdate":"1989-03-15","gender":"M","email":"mcyoungg","brandpref":"MARLBORO BLACK MENTHOL","survey_date":"2012-04-03 16:38:50","ba_name":"GABBIE THUNG","ba_userid":"gabbie.jkt"},
	//{"id":"5","firstname":"gabbie","lastname":"thung","nickname":"gabe","birthdate":"1989-03-15","gender":"M","email":"mcyoungg@gmail.com","brandpref":"MARLBORO BLACK MENTHOL","survey_date":"2012-04-03 18:34:32","ba_name":"GABBIE THUNG","ba_userid":"gabbie.jkt"}]}
	//check lastid in table
	$sql = "SELECT DISTINCT id FROM ".ReportDB.".tbl_ipad_data_registration ORDER BY id DESC LIMIT 1";
	$qData = $this->fetch($sql);
	$lastid = $qData['id'];
	if(!$lastid) $lastid=0;
	// $lastid=0;
	$beterbeApiKey = 'marlboro_redrush_kana_4pik3y_@CC355';
	$beterbeSecretKey = 'marlboro_redrush_@ppsecr3T_K3Y';
	$token = sha1('getdatareg'.$beterbeSecretKey.$lastid.$beterbeApiKey);
	//give lastid
	// return IPADURL.'getdatareg/'.$token.'/'.$lastid;
	$data  = json_decode(file_get_contents(IPADURL.'getdatareg/'.$token.'/'.$lastid));
		
		
	if($data->numrows!=0){
	$data_gather_date_time = date('Y-m-d H:i:s');
	$data_gather_date_ts = time();
	$total = 1;
	$q = array();
	foreach($data->regdata as $value){
	
	$sql = "
	INSERT INTO 
	".ReportDB.".tbl_ipad_data_registration 
	(id,email,survey_date,surveyor,data_gather_date_time,data_gather_date_ts,brandpref,nickname,birthdate,gender) 
	VALUES 
	(".intval($value->id).",'".$value->email."','".$value->survey_date."','".$value->ba_userid."','".$data_gather_date_time."','".$data_gather_date_ts."','".$value->brandpref."','".$value->nickname."','".$value->birthdate."','".$value->gender."')
	";
	$this->query($sql);
	$brand1='';
	$brand1='';
	$brand3='OTHER';
	if((preg_match("/amild/i",$value->brandpref))){ $brand1='AMILD';$brand3='';}
	if((preg_match("/marlboro/i",$value->brandpref))) {$brand2='MARLBORO';$brand3='';}
	
	$sql = "
	INSERT IGNORE INTO 
	".RedRushDB.".kana_member
	(email,n_status,verified,mobile_type,nickname,birthday,sex,Brand1_ID,Brand2_ID,Brand3_ID) 
	VALUES 
	('".$value->email."',1,'1','2','".$value->nickname."','".$value->birthdate."','".$value->gender."','".$brand1."','".$brand2."','".$brand3."')
	";
	$this->query($sql);
	
	
	$q[] = "id => ".intval($value->id);
	$total+=$total;
	if($total > 10) break;
	}
	}
	return $q;
	}
	
	function add_game_ipad_data(){
	
	//check lastid in table
	$sql = "SELECT DISTINCT id FROM ".ReportDB.".tbl_ipad_data_game ORDER BY id DESC LIMIT 1";
	$qData = $this->fetch($sql);
	$lastid = $qData['id'];
		if(!$lastid) $lastid=0;
	$beterbeApiKey = 'marlboro_redrush_kana_4pik3y_@CC355';
	$beterbeSecretKey = 'marlboro_redrush_@ppsecr3T_K3Y';
	$token = sha1('getdatagame'.$beterbeSecretKey.$lastid.$beterbeApiKey);	
	//give lastid
	$data  = json_decode(file_get_contents(IPADURL.'getdatagame/'.$token.'/'.$lastid));
	
	if($data->numrows!=0){
	$data_gather_date_time = date('Y-m-d H:i:s');
	$data_gather_date_ts = time();
	$total = 1;
	$q = array();
	foreach($data->gamedata as $value){
	
	$sql = "
	INSERT INTO 
	".ReportDB.".tbl_ipad_data_game 
	(id,user_id,game_id,survey_date,surveyor,data_gather_date_time,data_gather_date_ts) 
	VALUES 
	(".intval($value->id).",'".$value->userid."','".$value->gameid."','".$value->survey_date."','".$value->surveyor."','".$data_gather_date_time."','".$data_gather_date_ts."')
	";
	$this->query($sql);
	$q[] = "id => ".intval($value->id);
	$total+=$total;
	if($total > 10) break;
	}
	}
	return $q;
	}
	
	
	function distribution_point_on_mobile(){
	
	// return false;
	//cek all user
	$sql="
	SELECT user_id 
	FROM ".ReportDB.".tbl_ipad_data_game 
	WHERE n_status=0
	GROUP BY user_id
	";
	$user_mobile_game_ipad = $this->fetch($sql,1);
		// print_r($sql);exit;
	$loop = 0;
	$maxLoop = 100;
	$debug_data = array();
	foreach ($user_mobile_game_ipad as $user_data){
	$sql ="
	SELECT id,user_id,game_id,survey_date,date_format(survey_date,'%Y-%m-%d') as tgl 
	FROM ".ReportDB.".tbl_ipad_data_game 
	WHERE user_id ='{$user_data['user_id']}'
	AND n_status=0 
	GROUP BY game_id, user_id, tgl
	";
	$mobile_game_ipad = $this->fetch($sql,1);
	$count_this_day_data = 0;
	$new_data_id = NULL;
	
	//loop data nya mobile
	
	foreach($mobile_game_ipad as $data){
	
	//search user_id if data user_id is qrcode/email.. 
	$user_id = $this->checkUserID($data['user_id']);
		
	
	//count data game_id, user_id, date klo uda lebih dari 2 break, ke next user_id,date
	if($user_id) 
	{
	$date_game = explode(' ',$data['survey_date']);
	
	$data_id = $user_id.'|'.$date_game[0];
	if($data_id==$new_data_id) $arrDataUserID_Date[$data_id]++;
	if($arrDataUserID_Date[$data_id]==2) break;
	
	$new_data_id = $user_id.'|'.$date_game[0];
	$debug_data[]=$data_id.'|'.$count_this_day_data.'<br>';
	// print_r($user_id);exit;
	
	//select score untuk game enggagement
	$sql = "
	SELECT points,mobile_type
	FROM ".RedRushDB.".tbl_distribution_point_mobile	
	WHERE 
	game_id = '{$data['game_id']}' LIMIT 1
	";
	
	$game_points = $this->fetch($sql);
		//insert data
	if($game_points['mobile_type']!=4) $debug_data[] = $this->game_mobile_points($data,$user_id,$game_points,$date_game);
	else $debug_data[] = $this->game_mobile_level_booster_points($data,$user_id,$game_points,$date_game);

	}
	
	$sqlUpdate ="
	UPDATE ".ReportDB.".tbl_ipad_data_game 
	SET n_status = '2' 
	WHERE
	n_status = '0' 
	AND user_id like '%{$data['user_id']}%' 
	AND survey_date = '{$data['survey_date']}' 
	AND game_id = '{$data['game_id']}' 
	LIMIT 1 ;
	";
	$sqlupdateResult = $this->query($sqlUpdate);
	if($sqlupdateResult) $arrUpdate[] = true; 

	}
	
	} 
	// print_r($user_mobile_game_ipad);exit;
		return array('total data'=>count($debug_data),'total user has change status'=>$arrUpdate);
	
	}
	
	function game_mobile_level_booster_points($data,$user_id,$game_points,$date_game){
	//get level up
	$sql ="SELECT level_booster FROM ".RedRushDB.".tbl_level_booster WHERE game_id='{$data['game_id']}' LIMIT 1";
	$qData = $this->fetch($sql);
	//update level on player
	$sql = "UPDATE ".GameDB.".racing_level
	SET level={$qData['level_booster']}
	WHERE user_id={$user_id}
	";
	$insertToPlayerLevel = $this->query($sql);
	
	if($insertToPlayerLevel){
		$sql="
		UPDATE ".ReportDB.".tbl_ipad_data_game 
		SET n_status=1 
		WHERE
		user_id ='{$data['user_id']}' AND game_id = '{$data['game_id']}' AND survey_date like '%{$date_game[0]}%'
		
		";
		// print_r($sql);
	$result = 	$this->query($sql);
	//complete the part level
		if($result){
		// racing_parts_inventory 
			//name 	level
		$sql ="SELECT id,name,level,price FROM ".GameDB.".racing_parts_inventory WHERE level<={$qData['level_booster']}";
		$partData = $this->fetch($sql,1);
		foreach($partData as $part){
			//tbl_purchase_part
				//user_id 	part_id 	point 	date_time 	date_time_ts
			$sql ="INSERT IGNORE INTO 
					".RedRushDB.".tbl_purchase_part (user_id, 	part_id ,	point, 	date_time,	date_time_ts) 
					VALUES
					({$user_id},{$part['id']},0,NOW(),'".time()."')
			";	
			$this->query($sql);
			//racing_user_inventory
				//user_id 	parts_id 	n_status 	purchase_date 	purchase_date_ts
			$sql = "INSERT IGNORE INTO 
					".GameDB.".racing_user_inventory
					(user_id ,	parts_id 	,n_status 	,purchase_date ,	purchase_date_ts)
					VALUES
					({$user_id},{$part['id']},1,NOW(),'".time()."')";
			$this->query($sql);
			}
		}
	}
	return $result;
	
	
	}
	
	function game_mobile_points($data,$user_id,$game_points,$date_game){
	
	$strtime = strtotime($data['survey_date']);
	$token = sha1($data['game_id'].$user_id.$data['survey_date']);
	$checkGamePoints = "SELECT count(game_session_token) as total FROM ".RedRushDB.".game_score WHERE game_session_token ='{$token}' LIMIT 1";
	$found = $this->fetch($checkGamePoints);
	if($found['total']==0){
	$sql = "INSERT IGNORE INTO ".RedRushDB.".game_score 
	(game_id ,	user_id ,	submit_time ,	submit_time_ts, 	score ,	level_id,game_session_token) 
	VALUES
	('{$data['game_id']}',{$user_id},'{$data['survey_date']}','{$strtime}',{$game_points['points']},0,'{$token}')
	";
	$insertToGameScore = $this->query($sql);
	}

		$sql="
		UPDATE ".ReportDB.".tbl_ipad_data_game 
		SET n_status=1 
		WHERE
		user_id ='{$data['user_id']}' AND game_id = '{$data['game_id']}' AND survey_date like '%{$date_game[0]}%'
		
		";
		// print_r($sql);
		$this->query($sql);

	return $sql;
	}
	
	function checkUserID($searchIt){
	// return false;
	$qData = "
		SELECT id 
		FROM ".RedRushDB.".kana_member km 
		LEFT JOIN ".RedRushDB.".tbl_qr_code_user qr ON qr.user_id = km.id 
		WHERE 
		( km.id='{$searchIt}' OR qr.code ='{$searchIt}' OR km.email='{$searchIt}' )
		AND (km.n_status = 1 AND km.verified ='1') LIMIT 1
		";
		$data =  $this->fetch($qData);
		// return $qData;
		return $data['id'];
	}
	
	
	/**
	 * sync mop data
	 * @param $register_id
	 * @param $firstname
	 * @param $lastname
	 * @param $email
	 * @param $avtype
	 * @return user_id
	 */
	function sync_data($register_id,$firstname,$lastname,$nickname,$email,$avtype){
		$register_id = mysql_escape_string($register_id);
		$firstname = mysql_escape_string($firstname);
		$lastname = mysql_escape_string($lastname);
		$nickname = mysql_escape_string($nickname);
		$email = mysql_escape_string($email);
		$avtype = mysql_escape_string($avtype);
		
		if($avtype==1||$avtype==3){
			$status=1;
		}else{
			$status=0;
		}
		$sql = "SELECT * FROM kana_member WHERE register_id='{$register_id}'";
		$user = $this->fetch($sql);
		
		$sql = "INSERT IGNORE INTO kana_member(register_id,name,last_name,email,n_status,verified,mobile_type)
				VALUES('{$register_id}','{$firstname}','{$lastname}','{$email}',1,'{$status}','3')";
		$q = $this->query($sql);
		if($q){
			//auto-generate nickname
			$ok = false;
			$n=1;
			$nn = $nickname;
			if(strlen($user['nickname'])==0){
				do{				
					$sql = "SELECT nickname FROM kana_member WHERE nickname='{$nn}' LIMIT 1";
					$row = $this->fetch($sql);
					if($row['nickname']!=$nn||strlen($row['nickname'])==0){
						$sql = "UPDATE kana_member SET nickname='{$nn}' WHERE email='{$email}'";
						$q = $this->query($sql);
						$ok=true;
						break;	
					}else{
						$nn=$nickname.$n;
						$n++;
					}
					if($n==100){
						//no nickname available
						break;
					}
				}while(!$ok);
			}
			//update status
			$sql = "UPDATE kana_member SET n_status=1,verified='{$status}' WHERE email='{$email}'";
			$q = $this->query($sql);
			//-->
		}
		$sql = "SELECT id FROM kana_member WHERE email='{$email}' LIMIT 1";
		$rs = $this->fetch($sql);
		return $rs['id'];
	}
	
}
?>
