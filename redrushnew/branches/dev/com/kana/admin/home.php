<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class home extends SQLData{
	
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
	}
	
	function admin(){
		$act = $this->Request->getParam('act');
		if( $act == 'edit' ){
			return $this->edit();
		}elseif( $act == 'save' ){
			return $this->save();
		}else{
			return $this->show();
		}
	}
	
	function show(){
	
		$rs = $this->fetch('SELECT * FROM kana_home');
		
		$this->View->assign('text',$rs['text']);
		
		return $this->View->toString(APPLICATION . "/admin/home.html");
		
	}
	
	function edit(){
	
		$rs = $this->fetch('SELECT * FROM kana_home');
		
		$this->View->assign('text',$rs['text']);
		
		return $this->View->toString(APPLICATION . "/admin/home-edit.html");
	
	}
	
	function save(){
	
		$text = mysql_escape_string($this->Request->getPost('txt'));
		
		$qry = "UPDATE kana_home SET text='".$text."'";
		
		if($this->query($qry)){
			return $this->View->showMessage("Berhasil","index.php?s=beranda");
		}else{
			return $this->View->showMessage("Gagal","index.php?s=beranda&act=edit");
		}
	
	}
	
}