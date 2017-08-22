<?php
class statics extends App{
	
	var $Request;
	
	var $View;
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
	}
	
	function home(){
		return $this->about;
	}
	
	function about(){
		$this->assign('current','about');
		return $this->contentString("/about.html",true);
	}
	
	function how(){
		$this->assign('current','how');
		return $this->contentString("/how-to-play.html",true);
	}
	
	function prizes(){
		$this->assign('current','prizes');
		return $this->contentString("/prizes.html",true);
	}
	
	function tos(){
		$this->assign('current','tos');
		return $this->contentString("/tos.html",true);
	}

}