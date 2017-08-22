<?php
class getpoints extends App{
	
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
			$userData = json_decode($this->API->getPlayerData($this->user->id));
			foreach($userData as $key => $value){
			$this->assign($key,$value);
			}
			$userData=NULL;
			$this->log('page','getpoints');
			//	if($this->user->verified!='1') return $this->contentString("/not_verified_getpoint.html",true);
			return $this->contentString("/getpoints.html",true);
	
	}
	
	function minigame1(){
			$userData = json_decode($this->API->getPlayerData($this->user->id));
			$ts = time();
			$random = rand(0,900);
			$secret = serialize(array($ts,$this->user->id,$random));
			$access_token = urlencode64($secret);
			$this->assign('access_token',$access_token);
			$secret = null;$access_token=null;
			foreach($userData as $key => $value){
			$this->assign($key,$value);
			}
			$userData=NULL;
			$this->log('minigame1','minigame1');
		$game = $this->Request->getParam('game');
		$this->assign('game',$game);
		$this->assign('title','game 1');
		return $this->contentString("/playminigame.html",true);
	}
	
	function minigame2(){
			$userData = json_decode($this->API->getPlayerData($this->user->id));
			$ts = time();
			$random = rand(0,900);
			$secret = serialize(array($ts,$this->user->id,$random));
			$access_token = urlencode64($secret);
			$this->assign('access_token',$access_token);
			$secret = null;$access_token=null;
			foreach($userData as $key => $value){
			$this->assign($key,$value);
			}
			$userData=NULL;
			$this->log('minigame2','minigame2');
		$game = $this->Request->getParam('game');
		$this->assign('game',$game);
		$this->assign('title','game 2');
		return $this->contentString("/playminigame2.html",true);
	}
	function minigame3(){
		$userData = json_decode($this->API->getPlayerData($this->user->id));
		$ts = time();
		$random = rand(0,900);
		$secret = serialize(array($ts,$this->user->id,$random));
		$access_token = urlencode64($secret);
		$this->assign('access_token',$access_token);
		$secret = null;$access_token=null;
		foreach($userData as $key => $value){
			$this->assign($key,$value);
		}
		$userData=NULL;
		$this->log('minigame3','minigame3');
		$game = $this->Request->getParam('game');
		$this->assign('game',$game);
		$this->assign('title','game 3');
		return $this->contentString("/playminigame3.html",true);
	}
	
	function minigame4(){
		$userData = json_decode($this->API->getPlayerData($this->user->id));
		$ts = time();
		$random = rand(0,900);
		$secret = serialize(array($ts,$this->user->id,$random));
		$access_token = urlencode64($secret);
		$this->assign('access_token',$access_token);
		$secret = null;$access_token=null;
		foreach($userData as $key => $value){
			$this->assign($key,$value);
		}
		$userData=NULL;
		$this->log('minigame4','minigame4');
		$game = $this->Request->getParam('game');
		$this->assign('game',$game);
		$this->assign('title','game 4');
		return $this->contentString("/playminigame4.html",true);
	}

}