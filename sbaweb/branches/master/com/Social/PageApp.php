<?php
include_once "MOPHelper.php";
include_once "SessionHelper.php";
include_once "NewsHelper.php";
include_once "PageHelper.php";
include_once "SocialApp.php";
class PageApp extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $mop;
	var $session;
	var $news;
	var $helper;
	var $social;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->mop = new MOPHelper(null);
		$this->session = new SessionHelper('SocialNetwork');
		$this->news = new NewsHelper($req);
		$this->helper = new PageHelper($req);
		$this->social = new SocialApp($req);
	}
	
	function run(){
		if($this->social->GetSession()){
			
			$page_id = $this->param("page_id");
			if($this->post('create_page')){
					return $this->save_page();
			}else{
				if($page_id!=0){
					return $this->page_home($page_id);
				}else{
					return $this->create_page();
				}
			}
		}else{
			return "Access Denied";
		}
	}
	function save_page(){
		global $CONFIG;
		$name = $this->post('name');
		$about = $this->post('about');
		$alias = $this->post('alias');
		$descriptions = $this->post('descriptions');
		$role = strip_tags($this->post('role'));
		
		$page_info = array();
		for($i=0;$i<5;$i++){
			if($this->post('add_name'.$i)!=null&&$this->post('add_val'.$i)!=null){
				$page_info = array_merge($page_info,
				array(mysql_escape_string($this->post('add_name'.$i))=>mysql_escape_string($this->post('add_val'.$i))));
				
			}
		}
		
		$page_info['Members'] = "<a href='index.php?profile_id=".$this->social->getProfile()->id."'>".
								$this->social->getProfile()->name."</a> - ".$role;
		
		$page_info = serialize($page_info);
		$ins = $this->helper->create_page($name, $about, $alias, $descriptions, $page_info);
		$page_id = $this->helper->getLastInsertId();
		if($ins){
			//create bookmark for the user who created this page
			$this->social->addBookmark($this->social->getProfile()->id, 
										$name, 
										$CONFIG['page_home']."?page_id=".$page_id);
			$msg = "Selamat, Page anda telah berhasil dibuat.";
		}else{
			$msg = "Maaf, page anda tidak berhasil dibuat. Silahkan coba beberapa saat lagi!";
		}
		$page_id = $this->helper->lastInsertId;
		return $this->View->showMessage($msg, "page.php?page_id=".$page_id);
	}
	function create_page(){
		global $CONFIG;
		$additional = array("Genre","About","Hometown","Telp","Alamat");
		$this->assign("url",$CONFIG['page_path']);
		$this->assign("additional",$additional);
		
		return $this->out('Social/page_create.html');
	}
	function page_home($page_id){
		$page_info = $this->helper->getPageInfo($page_id);
		$this->assign("page_info",$page_info);
			
		$i=0;
		$arr = unserialize($page_info['page_info']);
		if(is_array($arr)){
		foreach($arr as $name=>$val){
			$info[$i]['name']=$name;
			if(is_array($val)){
				$info[$i]['value'] = "";
				foreach($val as $str){
					$info[$i]['value'].= "-".$str."<br/>";
				}
			}else{
				$info[$i]['value'] = $val;
			}
			$i++;
		}
		}
		$this->assign("info",$info);
		return $this->out('Social/page_home.html');	
	}
	
	function admin(){
		include_once "SocialAppAdmin.php";
		$app = new SocialAppAdmin($this->req);
	}
	/*
	function __toString(){
		return $this->out($this->_mainLayout);
	}
	*/
}
?>