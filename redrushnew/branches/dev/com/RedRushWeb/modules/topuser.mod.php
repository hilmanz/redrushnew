<?php
class topuser extends App{
	
	var $Request;
	
	var $View;
	
	var $API;
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();	
		require_once APP_PATH.APPLICATION."/helper/apiHelper.php";
		$this->API = new apiHelper();
	}
	
	function home(){
		
		//if($this->user->verified!='1') return $this->contentString("/not_verified_topuser.html",true);
		$userData = json_decode($this->API->getPlayerData($this->user->id));
		foreach($userData as $key => $value){
		$this->assign($key,$value);
		}
		$userData=NULL;
		$topUser = json_decode($this->API->getTopUser(50));
		foreach($topUser as $n=>$v){
			$csrf_token = sha1(date("YmdHis").rand(0,999));
			$csrf_token_sessid = sha1($csrf_token.$this->user->id);
			$_SESSION[$csrf_token_sessid] = 1;
			$players = array("player1"=>$this->user->id,"player2"=>$v->id,'ctoken'=>$csrf_token);
			$racing_token = urlencode64(serialize($players));
			//pastiin untuk mengosongkan array lagi.. hemat memory.
			$players = null;
			$topUser[$n]->racing_token = NULL;
			if($topUser[$n]->nickname!='')$topUser[$n]->name = $topUser[$n]->nickname;
			
			if($v->id!=$this->user->id) $topUser[$n]->racing_token = $racing_token;
			
		}
		$this->assign('top_user',$topUser);
		$topUser=NULL;
		$this->log('page','top_user');

		return $this->contentString("/topuser.html",true);
	
	}

}