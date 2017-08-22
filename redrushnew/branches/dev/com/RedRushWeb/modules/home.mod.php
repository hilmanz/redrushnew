<?php
class home extends App{
	
	var $Request;
	
	var $View;
	
	var $newsModel;
	var $popupModel;
	var $model;
	var $API;
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		// print_r($this->loginHelper->checkLogin());
		$this->setVar();
		require_once APP_PATH.APPLICATION."/models/newsModel.php";
		$this->newsModel = new newsModel();
		require_once APP_PATH.APPLICATION."/helper/apiHelper.php";
		$this->API = new apiHelper();
		include_once APP_PATH.APPLICATION.'/models/raceModel.php';
		$this->model = new raceModel();
		require_once APP_PATH.APPLICATION."/models/popupModel.php";
		$this->popupModel = new popupModel();
	}
	
	function paralax(){
	
		
		if( ! $this->loginHelper->checkLogin() ){
				
						sendRedirect('logout.php');
					
					exit;
				
			}
		$userid = $this->user->id;
		if($this->user->nickname ) $user_name = $this->user->nickname;
		else $user_name = $this->user->name;
		$username = $this->user->username;
		if($this->user->verified != '1') $verified = "You're Not Verified";
		else $verified = '';
		
		$this->assign('user_name',$user_name);
		$this->assign('user_id',$userid);
		$this->assign('username',$username);
		$this->assign('verified',$verified);	
		
		echo $this->View->toString('RedRushWeb/landing.html');
		
		exit;
	}
	
	function loading(){
		if( ! $this->loginHelper->checkLogin() ){
				
					sendRedirect('logout.php');
					
					exit;
				
			}
		ob_start();
		$this->assign('loadIt','home');
		$this->assign('targetIt','?page=home&act=main');
		//img
		// $imagesDir = 'img/';
		// $img = glob($imagesDir . '*.{png}', GLOB_BRACE);
		$img[0] = 'img/coundown_panel.png';
		$img[1] = 'img/lampu_merah.png';
		$img[2] = 'img/lampu_hijau.png';
		$img[3] = 'img/bg_profile2.png';
		$img[4] = 'img/bg_profile.png';
		$img[5] = 'img/box_reds.png';
		$img[6] = 'img/frame.png';
		$img[7] = 'img/progress-bar.png';
		$img[8] = 'img/bg_panel_small.png';
		$img[9] = 'img/bg_panel.png';
		$img[10] = 'img/bg_panel2.png';
		$img[11] = 'img/bg_newsfeed.png';
		$this->assign('img', $img);
		
		//img topview
		$imagesDir = 'img/topview/';
		$topview = glob($imagesDir . '*.{png}', GLOB_BRACE);
		$this->assign('topview', $topview);
			
		return $this->contentString("/loading.html",true);
	
	}
	
	
	function main(){
			if( ! $this->loginHelper->checkLogin() ){
				
					sendRedirect('logout.php');
					
					exit;
				
			}
			
			$racer = $this->model->getOpponent($this->user->id,0,2);
			//generat racing token for each opponent
			foreach($racer as $n=>$v){
				$csrf_token = sha1(date("YmdHis").rand(0,999));
				$csrf_token_sessid = sha1($csrf_token.$this->user->id);
				$_SESSION[$csrf_token_sessid] = 1;
				$players = array("player1"=>$this->user->id,"player2"=>$v['id'],'ctoken'=>$csrf_token);
				$racing_token = urlencode64(serialize($players));
				//pastiin untuk mengosongkan array lagi.. hemat memory.
				$players = null;
					if($racer[$n]['nickname']) $racer[$n]['name'] = $racer[$n]['nickname'];
				$racer[$n]['racing_token'] = $racing_token;
			}
			// $racer = shuffle($racer);
			// print_r('<pre>');print_r($racer);exit;
			$this->assign('racer',$racer);
			$racer=NULL;
		
			$topUser = json_decode($this->API->getTopUser(2));
			foreach($topUser as $n=>$v){
				$csrf_token = sha1(date("YmdHis").rand(0,999));
				$csrf_token_sessid = sha1($csrf_token.$this->user->id);
				$_SESSION[$csrf_token_sessid] = 1;
				$players = array("player1"=>$this->user->id,"player2"=>$v->id,'ctoken'=>$csrf_token);
				$racing_token = urlencode64(serialize($players));
				//pastiin untuk mengosongkan array lagi.. hemat memory.
				$players = null;
				if($topUser[$n]->nickname!='')$topUser[$n]->name = $topUser[$n]->nickname;
				$topUser[$n]->racing_token = NULL;
				if($v->id!=$this->user->id) $topUser[$n]->racing_token = $racing_token;
				
			}
			
			$this->assign('top_user',$topUser);
			$topUser=NULL;
		
			$news = $this->newsModel->getLatest(0,4);
			$this->assign('news',$news);
			$news=NULL;
			
			//notification			
			$notification = json_decode($this->API->get_all_user_notification(10));
			$this->assign('notification',$notification);
			
			
			//player
			// print_r('<pre>');print_r($this->user);exit;
			$userData = json_decode($this->API->getPlayerData($this->user->id));
			if( $this->user->nickname) $name = $this->user->nickname;
			else $name =  $this->user->name;
			
			
			
			//ultimate
			// $ultimateCar = json_decode($this->API->get_ultimate_car($this->user->id,$userData->level));
					
			// if($ultimateCar){
				// $csrf_token = sha1(date("YmdHis").rand(0,999));
				// $csrf_token_sessid = sha1($csrf_token.$this->user->id);
				// $_SESSION[$csrf_token_sessid] = 1;
				// $players = array("player1"=>$this->user->id,"player2"=>$ultimateCar->id,'ctoken'=>$csrf_token);
				// $racing_token = urlencode64(serialize($players));
				// $players = null;
				// $ultimateCar->racing_token = NULL;
				// if($v->id!=$this->user->id) $ultimateCar->racing_token = $racing_token;
			
			// }

			// $this->assign('ultimateCar',$ultimateCar);
			
			// $ultimateCar=NULL;
			
			
			$this->assign('user_name',$name);
			// print_r($userData->level);exit;
			$this->assign('level',$userData->level);
			
			$this->log('page','home');
			
			//event
			//info
			if(!isset($_COOKIE["popupinfo".$this->user->id])){
			$popupInfo = $this->popupModel->getPopup();
			if(	$popupInfo ) 
			{
				foreach($popupInfo as $key => $value){
				$this->assign('popupInfo_'.$key,$value);
				}
			
			$this->assign('popup_info','RedRushWeb/popup-event.html');
			setcookie("popupinfo".$this->user->id, true,time()+60*60*24);
			}
			}
			// 
		
			
			
			
			return $this->contentString("/home.html",true);
		
	}

}
