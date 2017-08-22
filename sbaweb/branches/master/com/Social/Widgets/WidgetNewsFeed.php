<?php
class WidgetNewsFeed extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $_template="news_feed";
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->run();
	}
	function run(){
		if( $this->param('news') == 1 && $this->param('id') == null){
			$this->_template = 'news_feed';
		}
		$list = $this->getLatestNewsFeed(3);
		//some logic lagi
		//--->
		
		//assign hasilnya ke template.
		$this->View->assign("list",$list);
		
		
		//--> we're done :)
	}
	function getLatestNewsFeed($total){
		/*
		$sql = "some sql here";
		$this->open(0);
		//$rs = $this->fetch($sql,1);
		$this->close();
		*/
		return '';
	}
	function __toString(){
		
		return $this->View->toString("Social/widgets/".$this->_template.".html");
	}
	
}
?>