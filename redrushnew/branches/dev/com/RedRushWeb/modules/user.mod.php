<?php
class user extends App{
	
	var $Request;
	
	var $View;
		
	var $API;
	
	var $newsModel;
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		require_once APP_PATH.APPLICATION."/helper/apiHelper.php";
		$this->API = new apiHelper();
		require_once APP_PATH.APPLICATION."/models/newsModel.php";
		$this->newsModel = new newsModel();
		
	}
	function home(){
	$act = $this->Request->getParam('act');
	if($act) $this->$act;
	else return $this->contentString("/inbox.html",true);
	}
	
	
	function notification(){
	
	$notification = json_decode($this->API->get_user_notification($this->user->id,26));
	
	$this->assign('notification',$notification);
	
	$message = json_decode($this->API->get_message_inbox_from_admin($this->user->id,10));
	$featured = $this->newsModel->getFeatured(1); // get latest featured news
	$this->assign('featured',$featured);
	$this->assign('message',$message);
	$this->log('page','notification');
	if($this->user->verified!='1') return $this->contentString("/not_verified_inbox.html",true);
	return $this->contentString("/inbox.html",true);

	}
	
	
	function detail_notification(){
	$type = $this->Request->getParam('type');
	if($type=='merch') {
	require_once APP_PATH.APPLICATION."/models/merchandiseModel.php";
	$merch = new merchandiseModel();
	$merch_id = $this->Request->getParam('report');
	$merch->NotificationStatus($merch_id);	
	$txt = $merch->getDetailMerchandisebyID($this->user->id,$merch_id);
	$this->assign('type',$type);
	$this->assign('notification',$txt);
	}else{
		$reportid = $this->Request->getParam('report');
		include_once APP_PATH.APPLICATION."/models/inboxModel.php";
		// echo APP_PATH.APPLICATION."/models/inboxModel.php";exit;
		$model = new inboxModel();
		$model->NotificationStatus($reportid);	
		$notification = json_decode($this->API->get_race_report_notification($reportid));
		$this->assign('notification',$notification->message);
	}
	// print_r($notification->message);exit;
	$featured = $this->newsModel->getFeatured(1); // get latest featured news
	$this->assign('featured',$featured);
	return $this->contentString("/detail_notification.html",true);
	}
	
	function detail_message(){
		include_once APP_PATH.APPLICATION."/models/inboxModel.php";
		// echo APP_PATH.APPLICATION."/models/inboxModel.php";exit;
		$model = new inboxModel();
		$reportid = $this->Request->getParam('report');
		$message = $model->ReadMessage($this->user->id,$reportid);
		// print_r($message);exit;
		$this->assign('subject',$message['message_subject']);
		$this->assign('message',$message['message_text']);
		$featured = $this->newsModel->getFeatured(1); // get latest featured news
		$this->assign('featured',$featured);
		$model->MessageStatus($reportid); // update status message jadi 1
		return $this->contentString("/detail_message.html",true);
	}
	
	function un_notification(){
	$token = $this->Request->getParam('token');
	$un_notification = json_decode($this->API->un_notification($token));
	
	}
	
	function find_player(){
		// print_r($this->Request->getPost('search'));exit;
		if($this->user->verified!='1') return $this->contentString("/not_verified_finduser.html",true);
		if(! $this->Request->getPost('search')) return false;
		$search = $this->Request->getPost('search');
		$userData = json_decode($this->API->getPlayerData($this->user->id));
		foreach($userData as $key => $value){
		$this->assign($key,$value);
		}
		$userData=NULL;
		$findPlayer = json_decode($this->API->find_player($search));
		foreach($findPlayer as $n=>$v){
			$csrf_token = sha1(date("YmdHis").rand(0,999));
			$csrf_token_sessid = sha1($csrf_token.$this->user->id);
			$_SESSION[$csrf_token_sessid] = 1;
			$players = array("player1"=>$this->user->id,"player2"=>$v->id,'ctoken'=>$csrf_token);
			$racing_token = urlencode64(serialize($players));
			//pastiin untuk mengosongkan array lagi.. hemat memory.
			$players = null;
			if($findPlayer[$n]->nickname) $findPlayer[$n]->name = $findPlayer[$n]->nickname;
			$findPlayer[$n]->racing_token = NULL;
			if($v->id!=$this->user->id) $findPlayer[$n]->racing_token = $racing_token;
			
		}
		// Print_r('<pre>');Print_r($findPlayer);exit;
		$this->assign('player',$findPlayer);
		$findPlayer=NULL;
		$this->log('page','find_player');
		
		return $this->contentString("/findplayer.html",true);
	
	
	}
	
	
	function change_profile(){

	$user_id = $this->user->id;
	$nickname = $this->Request->getPost('nickname');
	if($this->Request->getPost('profile_avatar') && $this->Request->getPost('profile_avatar_ext')){
	$ext = strtolower($this->Request->getPost('profile_avatar_ext'));
	if($ext == 'jpeg') $ext = '.jpg';
	$image = (sha1($this->Request->getPost('profile_avatar').date('Ymd').$user_id)).'.'.str_replace('.','',$ext);
	$small_image = $image;
	}else {
	$image =null;
	$small_image =null;
	}
		// print_r($this->API->change_profile($user_id,$nickname));exit;
	$result = json_decode($this->API->change_profile($user_id,$nickname,$image,$small_image));
	$this->log('update_profile',$user_id);
	$this->assign('message','Your profile has been changed');
	$this->assign('go_url','garage');
	sendRedirect('?page=garage');
	return $this->contentString("/message_redrush.html",true);
	
	}
	
	function cek_nickname(){
		$nickname = $this->Request->getParam('nickname');
		if(!empty($nickname)){
			$qr = "SELECT COUNT(*) total FROM tbl_forbidden_name WHERE name='".$nickname."'";
			$this->open(0);
			$rs = $this->fetch($qr);
			$this->close();
			if($rs['total']==0){
				$q = "SELECT COUNT(*) total FROM kana_member WHERE nickname='".$nickname."' AND id <> '".$this->user->id."' LIMIT 1;";
				$this->open(0);
				$r = $this->fetch($q);
				$this->close();
				if($r['total']==1){
					$data = 1; // nickname sudah dipakai
				}
				else{
					$data = 2; // nickname available
				}
			}else{
				$data = 3;
			}
		}
		else{
			$data = 0; // nickname kosong
		}
		echo $data;
		exit;
	}
	
	function get_profile_pict(){
	$ava = $this->Request->getPost('ori_avatar');
	$ext = strtolower($this->Request->getPost('ori_avatar_ext'));
	if($ext==NULL || $ext == '') $ext = 'jpg';
	if($ext=='jpeg') $ext = '.jpg';
	$avatar = (sha1($ava.date('Ymd').$this->user->id)).'.'.str_replace('.','',$ext);
	
	echo $avatar;
	exit;
	
	}
	
	function promo_ref(){
	$user_id = $this->user->id;
	$session_id =  $this->Request->getParam('id');
	$type =  $this->Request->getParam('type');
	$this->log($type,$user_id);
	$this->promo($session_id,$type);	
	sendRedirect('https://login.marlboro.co.id/Templates/Referfriends.aspx?id='.$session_id.'&PromoRef='.$type.'');
	exit;
	}

}
