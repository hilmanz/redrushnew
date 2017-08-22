<?php 
class user_model extends SQLData{
	var $logs;
	var $debug;
	
	private $lose_note = array();
	private $win_note = array();
	private $challenge_note = array();
	private $activity_note = array();
	private $first_login_note= array();
	private $after_cooking_game_note= array();
	private $after_segway_game_note = array();
	private $after_findobject_game_note = array();
	private $after_puzzle_game_note = array();
	
	function __construct(){
		global $ENGINE_PATH;
		include_once $ENGINE_PATH."Utility/Debugger.php";
		parent::SQLData();
		$this->debug = new Debugger();
	}
	
	
	function is_first_login($user_id){
	$sql = "INSERT IGNORE ".RedRushDB.".tbl_first_login_activity (user_id,date_time,date_time_ts ) VALUES ({$user_id},NOW(),'".time()."')";
	$this->query($sql);
	$sql = "SELECT login_count,mobile_type FROM ".RedRushDB.".kana_member WHERE id=".$user_id." LIMIT 1";
	$data = $this->fetch($sql);
	return $data;
	
	}
	function add_point_first_login($user_id,$mobile_type){
	
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
	
	function get_player_data($user_id){
	$sql = "SELECT name,nickname,small_img,email FROM ".RedRushDB.".kana_member WHERE id=".$user_id." LIMIT 1";
	$data = $this->fetch($sql);
	$name = $data['name'];
	if($data['nickname']!='') $name = $data['nickname'];
	if($user_id==99999) $name = 'Ultimate Car Level 3';
	if($user_id==999999) $name = 'Ultimate Car Level 5';
	return array('name' => $name, 'small_img'=>$data['small_img'],'email'=>$data['email']);
	}
	
	function get_circuit_race($circuit_id){
	$sql = "SELECT name FROM ".GameDB.".racing_circuit WHERE id=".$circuit_id." LIMIT 1";
		$data = $this->fetch($sql);
	return $data['name'];
	}
	function get_merchandise($merchandise_id){
		$sql = "SELECT item_name FROM ".RedRushDB.".rr_merchandise WHERE id=".$merchandise_id." LIMIT 1";
		$data = $this->fetch($sql);
	return $data['item_name'];
	}
	
	function get_user_race_notification($notification){
	
	$player = $this->get_player_data($notification['user_id']);
	$opponent = $this->get_player_data($notification['action_value']);
	$arrInfo = explode('_',$notification['info']);
	$circuit = $this->get_circuit_race($arrInfo[0]);
	$winner =  $this->get_player_data($arrInfo[1]);
	//token
	$csrf_token = sha1(date("YmdHis").rand(0,999));
	$csrf_token_sessid = sha1($csrf_token.$notification['user_id']);
	$_SESSION[$csrf_token_sessid] = 1;
	$players_token = array("player1"=>$notification['user_id'],"player2"=>$notification['action_value'],'ctoken'=>$csrf_token);
	$racing_token = urlencode64(serialize($players_token));
	//$notification=null;
	if($notification['user_id']==$arrInfo[1]) $txt =  $this->note_winner($player['name'],$opponent['name'],$winner['name'],$racing_token);
	if($notification['user_id']!=$arrInfo[1]) $txt = $this->note_loser($player['name'],$opponent['name'],$winner['name'],$racing_token);
	$arrInfo =null;
	return $txt;
	//return "".$player." racing with ".$opponent." at ".$circuit.", ".$winner." is the winner and got +5 Game Points";
	}
	
	function get_user_race_activity($notification){
	
	$player = $this->get_player_data($notification['user_id']);
	$opponent = $this->get_player_data($notification['action_value']);
	$arrInfo = explode('_',$notification['info']);
	$circuit = $this->get_circuit_race($arrInfo[0]);
	$winner =  $this->get_player_data($arrInfo[1]);
	
	//token
	$csrf_token = sha1(date("YmdHis").rand(0,999));
	$csrf_token_sessid = sha1($csrf_token.$notification['user_id']);
	$_SESSION[$csrf_token_sessid] = 1;
	$players_token = array("player1"=>$notification['user_id'],"player2"=>$notification['action_value'],'ctoken'=>$csrf_token);
	$racing_token_winner = urlencode64(serialize($players_token));
	$players_token = array("player1"=>$notification['action_value'],"player2"=>$notification['user_id'],'ctoken'=>$csrf_token);
	$racing_token_loser = urlencode64(serialize($players_token));
	//$notification=null;
	if($notification['user_id']==$arrInfo[1]) $txt =  $this->note_activity($player['name'],$opponent['name'],$racing_token_loser,$racing_token_winner,$player['small_img'],$opponent['small_img']);
	if($notification['user_id']!=$arrInfo[1]) $txt = $this->note_activity($opponent['name'],$player['name'],$racing_token_winner,$racing_token_loser,$opponent['small_img'],$player['small_img']);
	$arrInfo =null;
	return $txt;
	//return "".$player." racing with ".$opponent." at ".$circuit.", ".$winner." is the winner and got +5 Game Points";
	}
	
	function note_activity($winner,$loser,$racing_token_winner,$racing_token_loser,$winner_small_img,$loser_small_img){
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
	
	
	function note_winner($player,$opponent,$winner,$racing_token){
		include "../config/notification_events.php";	
		$queue = rand(0,sizeof($challenged_player_win)-1);
			if(sizeof($challenged_player_win) == sizeof($this->win_note) ) $this->win_note = null;
			if(! in_array($queue,$this->win_note)) array_push($this->win_note,$queue);
			else {
			while(in_array($queue,$this->win_note)){
			$queue = rand(0,sizeof($challenged_player_win)-1);
			}
			array_push($this->win_note,$queue);
			}
		$txt = $challenged_player_win[$queue];
		return $txt;
	}
	
	function note_loser($player,$opponent,$winner,$racing_token){
		include "../config/notification_events.php";	
		$queue = rand(0,sizeof($challenged_player_lose)-1);
			if(sizeof($challenged_player_lose) == sizeof($this->lose_note) ) $this->lose_note = null;
			if(! in_array($queue,$this->lose_note)) array_push($this->lose_note,$queue);
			else {
			while(in_array($queue,$this->lose_note)){
			$queue = rand(0,sizeof($challenged_player_lose)-1);
			}
			array_push($this->lose_note,$queue);
			}
		$txt = $challenged_player_lose[$queue];
		return $txt;
	}
	
	function note_challenge($player){
		include "../config/notification_events.php";	
		$queue = rand(0,sizeof($challenged_player)-1);
		if(sizeof($challenged_player) == sizeof($this->challenge_note) ) $this->challenge_note = null;
		if(! in_array($queue,$this->challenge_note)) array_push($this->challenge_note,$queue);
		else {
		while(in_array($queue,$this->challenge_note)){
		$queue = rand(0,sizeof($challenged_player)-1);
		}
		array_push($this->challenge_note,$queue);
		}
		$txt = $challenged_player[$queue];
		return $txt;
	}
	
	function note_first_login($user_id,$racing_token_player,$player_small_img){
		$dataPlayer = $this->get_player_data($user_id);
		$csrf_token = sha1(date("YmdHis").rand(0,999));
		$csrf_token_sessid = sha1($csrf_token.$user_id);
		$_SESSION[$csrf_token_sessid] = 1;
		$players_token = array("player1"=>null,"player2"=>$user_id,'ctoken'=>$csrf_token);
		$racing_token_player = urlencode64(serialize($players_token));
		$player = '<a href="?page=garage&rtoken='.$racing_token_player.'">'.$dataPlayer['name'].'</a>';
		include "../config/activity_events.php";	
		$queue = rand(0,sizeof($first_login_activity)-1);
			if(sizeof($first_login_activity) == sizeof($this->first_login_note) ) $this->first_login_note = null;
			if(! in_array($queue,$this->first_login_note)) array_push($this->first_login_note,$queue);
			else {
			while(in_array($queue,$this->first_login_note)){
			$queue = rand(0,sizeof($first_login_activity)-1);
			}
			array_push($this->first_login_note,$queue);
			}
		$txt = $first_login_activity[$queue];
		return $txt;
	}
	
	function note_play_mini_game($user_id,$game_id){
		$dataPlayer = $this->get_player_data($user_id);
		$csrf_token = sha1(date("YmdHis").rand(0,999));
		$csrf_token_sessid = sha1($csrf_token.$user_id);
		$_SESSION[$csrf_token_sessid] = 1;
		$players_token = array("player1"=>null,"player2"=>$user_id,'ctoken'=>$csrf_token);
		$racing_token_player = urlencode64(serialize($players_token));
		$player = '<a href="?page=garage&rtoken='.$racing_token_player.'">'.$dataPlayer['name'].'</a>';
		if($game_id==1) $note = $this->after_puzzle_game($player);
		if($game_id==2) $note = $this->after_cooking_game($player);
		if($game_id==4) $note = $this->after_findobject_game($player);
		if($game_id==3) $note = $this->after_segway_game($player);
		return $note ;
	}
	
	function after_cooking_game($player){
	include "../config/activity_events.php";	
		$queue = rand(0,sizeof($after_cooking_game)-1);
			if(sizeof($after_cooking_game) == sizeof($this->after_cooking_game_note) ) $this->after_cooking_game_note = null;
			if(! in_array($queue,$this->after_cooking_game_note)) array_push($this->after_cooking_game_note,$queue);
			else {
			while(in_array($queue,$this->after_cooking_game_note)){
			$queue = rand(0,sizeof($after_cooking_game)-1);
			}
			array_push($this->after_cooking_game_note,$queue);
			}
		$txt = $after_cooking_game[$queue];
		return $txt;
	
	}
	
	function after_segway_game($player){
	include "../config/activity_events.php";	
		$queue = rand(0,sizeof($after_segway_game)-1);
			if(sizeof($after_segway_game) == sizeof($this->after_segway_game_note) ) $this->after_segway_game_note = null;
			if(! in_array($queue,$this->after_segway_game_note)) array_push($this->after_segway_game_note,$queue);
			else {
			while(in_array($queue,$this->after_segway_game_note)){
			$queue = rand(0,sizeof($after_segway_game)-1);
			}
			array_push($this->after_segway_game_note,$queue);
			}
		$txt = $after_segway_game[$queue];
		return $txt;
	
	}
	
	function after_findobject_game($player){
	include "../config/activity_events.php";	
		$queue = rand(0,sizeof($after_findobject_game)-1);
			if(sizeof($after_findobject_game) == sizeof($this->after_findobject_game_note) ) $this->after_findobject_game_note = null;
			if(! in_array($queue,$this->after_findobject_game_note)) array_push($this->after_findobject_game_note,$queue);
			else {
			while(in_array($queue,$this->after_findobject_game_note)){
			$queue = rand(0,sizeof($after_findobject_game)-1);
			}
			array_push($this->after_findobject_game_note,$queue);
			}
		$txt = $after_findobject_game[$queue];
		return $txt;
	
	}
	
	function after_puzzle_game($player){
	include "../config/activity_events.php";	
		$queue = rand(0,sizeof($after_puzzle_game)-1);
			if(sizeof($after_puzzle_game) == sizeof($this->after_puzzle_game_note) ) $this->after_puzzle_game_note = null;
			if(! in_array($queue,$this->after_puzzle_game_note)) array_push($this->after_puzzle_game_note,$queue);
			else {
			while(in_array($queue,$this->after_puzzle_game_note)){
			$queue = rand(0,sizeof($after_puzzle_game)-1);
			}
			array_push($this->after_puzzle_game_note,$queue);
			}
		$txt = $after_puzzle_game[$queue];
		return $txt;
	
	}
	
	function get_user_merchandise_notification($notification){
	$player = $this->get_player_data($notification['user_id']);
	$merchandise = $this->get_merchandise($notification['action_value']);
	
	return "".$player['name']." has bought merchandise ".$merchandise."";
	}
	
	function get_user_notification($user_id,$total){
		require_once APP_PATH.APPLICATION."/helper/dateHelper.php";
		$date = new dateHelper();
		$note =  array();
		$sql = "(SELECT  a.user2_id as user_id, a.user1_id as action_value, concat(a.circuit_id,'_',a.winner) as info, a.date_time as date_time, 4 as action_id, a.report_id as report_id, a.has_read as has_read FROM ".GameDB.".racing_history a WHERE user2_id=".$user_id.")
				UNION
				(SELECT b.user_id as user_id, b.merchandise_id as action_value, b.prize as info , b.purchase_date as date_time, 6 as action_id, b.id as report_id, b.has_read as has_read FROM ".RedRushDB.".rr_purchase_merchandise b WHERE b.user_id=".$user_id.")
				ORDER BY date_time DESC LIMIT ".$total."";
		$data = $this->fetch($sql,1);
		foreach($data as $notification){
		if($notification['action_id'] == 4 )  $small_img = $this->get_player_data($notification['action_value']);
		if($notification['action_id'] == 6 ) $small_img = $this->get_player_data($notification['user_id']);
		if($notification['action_id'] == 4 ) $note[] =  array('small_img'=>$small_img['small_img'],'message'=>$this->get_user_race_notification($notification),'date_time'=>$date->datediff($notification['date_time']),'report_id'=>$notification['report_id'] ,'has_read'=>$notification['has_read'],'type'=>'part'); //racing
		if($notification['action_id'] == 6 ) $note[] =  array('small_img'=>$small_img['small_img'],'message'=>$this->get_user_merchandise_notification($notification),'date_time'=>$date->datediff($notification['date_time']),'report_id'=>$notification['report_id'] ,'has_read'=>$notification['has_read'],'type'=>'merch');  //purchase merchandise
		
		}
		return $note;
	}
	
	function get_all_user_notification($total){
		require_once APP_PATH.APPLICATION."/helper/dateHelper.php";
		$date = new dateHelper();
		$note =  array();
		$sql = "(SELECT a.user1_id as user_id, a.user2_id as action_value, concat(a.circuit_id,'_',a.winner) as info, a.date_time as date_time, 4 as action_id FROM ".GameDB.".racing_history a )
				UNION
				(SELECT b.user_id as user_id, b.merchandise_id as action_value, b.prize as info , b.purchase_date as date_time, 6 as action_id FROM ".RedRushDB.".rr_purchase_merchandise b )
				UNION
				(SELECT c.user_id as user_id, NULL as action_value, NULL as info , c.date_time as date_time, 1 as action_id FROM ".RedRushDB.".tbl_first_login_activity c )
				UNION
				(SELECT d.user_id as user_id, d.game_id as action_value, NULL as info , d.submit_time as date_time, 2 as action_id FROM ".RedRushDB.".game_score d WHERE game_id IN (1,2,3,4) )
				ORDER BY date_time DESC LIMIT ".$total."";
		$data = $this->fetch($sql,1);
		foreach($data as $notification){
		if($notification['action_id'] == 1 )  $small_img = $this->get_player_data($notification['user_id']);
		if($notification['action_id'] == 2 )  $small_img = $this->get_player_data($notification['user_id']);
		if($notification['action_id'] == 4 )  $small_img = $this->get_player_data($notification['action_value']);
		if($notification['action_id'] == 6 ) $small_img = $this->get_player_data($notification['user_id']);
			if($notification['action_id'] == 2 ) $note[] =  array('small_img'=>$small_img['small_img'],'message'=>$this->note_play_mini_game($notification['user_id'],$notification['action_value']),'date_time'=>$date->datediff($notification['date_time'])); //mini game
			if($notification['action_id'] == 1 ) $note[] =  array('small_img'=>$small_img['small_img'],'message'=>$this->note_first_login($notification['user_id']),'date_time'=>$date->datediff($notification['date_time'])); //first login
			if($notification['action_id'] == 4 ) $note[] =  array('small_img'=>$small_img['small_img'],'message'=>$this->get_user_race_activity($notification),'date_time'=>$date->datediff($notification['date_time'])); //racing
			if($notification['action_id'] == 6 ) $note[] =  array('small_img'=>$small_img['small_img'],'message'=>$this->get_user_merchandise_notification($notification),'date_time'=>$date->datediff($notification['date_time']));  //purchase merchandise
		
		}
		return $note;
	}
	
	function get_user_race_notification_email($notification){
	
	$player =  $this->get_player_data($notification['opponent_id']);
	$opponent = $this->get_player_data($notification['user_id']);
	$arrInfo = explode('_',$notification['info']);

	$winner =  $this->get_player_data($notification['winner']);
	//$notification=null;
	if($notification['user_id']!=$notification['winner']) $txt =  $this->note_winner($player['name'],$opponent['name'],$winner['name']);
	if($notification['user_id']==$notification['winner']) $txt = $this->note_loser($player['name'],$opponent['name'],$winner['name']);
	$arrInfo =null;
	return $txt;

	}
	
	function send_user_notification($user_id,$opponent_id,$winner,$report,$circuit){
		// return $email_notification_report;
		GLOBAL $GAME_API;
		$sql_not_need_notif = "SELECT count(user_id) as total FROM ".RedRushDB.".tbl_deny_notification WHERE user_id=".$opponent_id." LIMIT 1";
		$not_need_notif = $this->fetch($sql_not_need_notif);
		// print_r( $not_need_notif['total']);exit;
		if($not_need_notif['total']>0) return array('message'=>'this user dont want notification');
		
		require_once APP_PATH.APPLICATION."/helper/dateHelper.php";
		$date = new dateHelper();
		$note =  array();
		$notification['user_id'] = $user_id;
		$notification['opponent_id'] = $opponent_id;
		$notification['winner'] = $winner;
		
		$sender = $this->get_player_data($user_id);
		$email_to_player = $this->get_player_data($opponent_id);
		$token = sha1($opponent_id.sha1(date('Y')));
		$report = str_replace('|','</p><p style="color:#fff">',strip_tags($report));
		$msg ='';
		$msg .='
<html>
<head>
<title>email</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#222" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table id="Table_01" width="600" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#000000">
        <tr>
                <td height="115">
                        <img src="'.BASEURL.'img/email_template/email_01.jpg" width="600" height="115" alt=""></td>
        </tr>
        <tr>
                <td height="522" style="background:url('.BASEURL.'img/email_template/email_02.jpg) top no-repeat;" valign="top">
                        <div style="font-family: Lucida Sans Unicode, Lucida Grande, sans-serif; font-size:13px; color:#fff; padding:20px 40px;">
          <h1 style=" color:#fff; ">HI '.$email_to_player['name'].', </h1>
                    <p style=" color:#fff; ">'.$this->note_challenge($sender['name']).'</p>
          <p>&nbsp;</p>
           <p style=" color:#fff; ">Race Report</p>
          <p style="color:#fff">Circuit Name : '.$circuit.'</p>
          <p style="color:#fff">'.stripslashes($report).'</p>
                    <p style=" color:#fff; ">'.stripslashes($this->get_user_race_notification_email($notification)).'</p>
                    <p style=" color:#fff; ">'.date('l').', '.date('F Y ').date('H:i:s').'</p>
          <p>&nbsp;</p>
          <p style=" color:#fff; ">You have received this email because you have either signed-up to receive promotional emails or a member . Should you<br>wish to not receive this notification, you can click on the unsubscribe link but you would be missing out on the latest<br>information and promotions from Philip Morris Indonesia.<br>
Please <a href="'.$GAME_API.'index.php?service=user_service&method=un_notification_mail&user_id='.$opponent_id.'&token='.$token.'">click here</a> to unsubscribe from any future mailings. We will respect all unsubscribe requests.</p>
      </div>
        </td>
        </tr>
        <tr>
                <td>
                        <img src="'.BASEURL.'img/email_template/email_03.jpg" width="600" height="131" alt=""></td>
        </tr>
</table>
<!-- End Save for Web Slices -->
</body>
</html>
		
		';
		
		
		
		GLOBAL $ENGINE_PATH;
		require_once $ENGINE_PATH."Utility/Mailer.php";
		// print_r($ENGINE_PATH."Utility/Mailer.php");exit;
		// if(file_exists($ENGINE_PATH."Utility/Mailer.php"))echo 'ada';exit;
		$mail = new Mailer();
		// $mail->setDefaultHeaders();
		// $mail->setSender($player);
		$mail->setRecipient($email_to_player['email']);
		// $mail->setRecipient('bummi@kana.co.id');
		$mail->setSubject('REDRUSH RACING Notification');
		$mail->setMessage($msg);
		$result = 	$mail->send();
		if($result) return array('message'=>'success send mail');
		else return array('message'=>'error mail setting');
		
		
		// return $note;
	}
	
	function find_player($search){
	$sql = "
	SELECT b.*,a.level,d.title_name FROM ".RedRushDB.".kana_member b 
		LEFT JOIN ".GameDB.".racing_level a ON b.id = a.user_id
		LEFT JOIN ".RedRushDB.".rr_user_title c ON c.user_id=b.id
		LEFT JOIN ".RedRushDB.".tbl_ref_title d  ON c.title_id = d.id
	WHERE (b.nickname like '%".$search."%' OR b.name like '%".$search."%' ) AND b.n_status=1 ";
	$data = $this->fetch($sql,1);
	return $data;
	}	
	
	function un_mail_notification($user_id,$token){
	// $token_web = sha1($user_id.sha1(date('YmdH')));
	// if($token_web!=$token) return false;
	$sql = "INSERT INTO ".RedRushDB.".tbl_deny_notification
	(user_id) 
	VALUES 
	(".$user_id.")";
	$result = $this->query($sql);
	if($result) return true;
	return false;
	}
	
	
	function change_profile($user_id,$nickname,$image=null,$small_img=null){
	$sql = "SELECT count(nickname) as found FROM ".RedRushDB.".kana_member WHERE nickname='".$nickname."'";
	$nameSame =  $this->fetch($sql,1);
	if($nameSame['found']>0) $nickname = $nickname.' '.$nameSame['found'];
	$sql = "UPDATE ".RedRushDB.".kana_member SET nickname='".$nickname."'";
	if($image!=null && $small_img!=null) $sql .= ",img='".$image."',small_img='".$small_img."'";
	else $sql.="";
	$sql.= " WHERE id=".$user_id." LIMIT 1";
	$data = $this->query($sql);
	
	$sql = "INSERT IGNORE INTO ".RedRushDB.".tbl_temp_image_processing
	(user_id,file) 
	VALUES 
	(".$user_id.",'".$image."')";
	$result = $this->query($sql);
	
	if($data) return array('message','your name:'.$nickname);
	else return array('message','unable to change profile');
	
	}
	
	
	function get_race_report_notification($report_id){
	$sql = "SELECT details FROM ".GameDB.".racing_report WHERE id=".$report_id." LIMIT 1";
	$data = $this->fetch($sql);

	if($data){
	$raw = unserialize($data['details']);
	return array('message'=>$raw['txt']);
	}else return array('message'=>NULL);
	
	}
	
	function get_message_inbox_from_admin($user_id){
	require_once APP_PATH.APPLICATION."/helper/dateHelper.php";
	$date = new dateHelper();
	$sql = "SELECT 	* FROM ".RedRushDB.".rr_message WHERE message_to=".$user_id." AND message_history='0'";
	$qData = $this->fetch($sql,1);
	foreach($qData as $key => $message){
	$qData[$key]['message_date'] = $date->datediff($message['message_date']);
	
	}
	// return $sql;
	// print_r($qData);exit;
	return $qData;
	
	
	}
	
	
}
?>
