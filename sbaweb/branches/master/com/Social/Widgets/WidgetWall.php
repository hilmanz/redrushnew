<?php

if(!class_exists("PostHelper")){
	include_once $APP_PATH."Social/PostHelper.php";
}
class WidgetWall extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $_template;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->run();
	}
	function run(){
		
	}
	function my_wall($user_id){
		$helper = new PostHelper(null);
		$feeds = $helper->getFeed($user_id,0,5);
		$this->View->assign("feeds",$feeds);
		$this->_template = "my_wall";
	}
	function __toString(){
		return $this->View->toString("Social/widgets/".$this->_template.".html");
	}
	
}
?>