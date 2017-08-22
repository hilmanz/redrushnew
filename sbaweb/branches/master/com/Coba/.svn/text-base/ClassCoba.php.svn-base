<?php
include_once "ModelCoba.php";
class ClassCoba extends SQLData{
	function __construct($req){
		$this->content = "";
		$this->Request = $req;
		$this->View = new BasicView();
		$this->Model = new ModelCoba($req);
	}
	function main(){
		$req = $this->Request;
		if($req->getParam('about')){
			$content = $this->about();
		}else if($req->getParam('contact')){
			$content = $this->contact();
		}else if($req->getParam('read')){
			$content = $this->read_news();
		}else{
			$content = $this->home();
		}
		return $content;
	}
	function admin(){
		return $this->manage_news();
	}
	function manage_news(){
		$this->View->assign("news",$this->Model->getLatestNews(20));
		return $this->View->toString("Coba/admin/manage_news.html");
	}
	function read_news(){
		$id = $this->Request->getParam('id');
		$this->View->assign("news",$this->Model->getNews($id));
		return $this->View->toString("Coba/home_news_detail.html");
	}
	function home(){
		$this->View->assign("new_entry",$this->Model->getLatestNews(20));
		return $this->View->toString("Coba/home_content.html");
		
	}
	function about(){
		$html = $this->View->toString("Coba/about.html");
		return $html;
	}
	function contact(){
		$html = $this->View->toString("Coba/contact.html");
		return $html;
	}
}
?>