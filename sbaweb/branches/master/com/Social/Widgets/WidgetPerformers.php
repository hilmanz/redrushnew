<?php
class WidgetPerformers extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->run();
	}
	function run(){
		$list = $this->getTopPerformers();
		$this->View->assign("list",$list);
	}
	function getTopPerformers($total=3){
		/*
		$sql = "some sql here";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		*/
		return '';
	}
	function __toString(){
		return $this->View->toString("Social/widgets/top_performers.html");
	}
	
}
?>