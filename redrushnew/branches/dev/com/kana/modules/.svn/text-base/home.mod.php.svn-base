<?php
class home extends App{
	
	var $Request;
	
	var $View;
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
	}
	
	function main(){
		
		$this->open(0);
		
		//$rs = $this->fetch('SELECT * FROM kana_home');
		
		$this->close();
		
		//$this->View->assign('text',$rs['text']);
		
		return $this->View->toString(APPLICATION.'/home.html');
	
	}

}