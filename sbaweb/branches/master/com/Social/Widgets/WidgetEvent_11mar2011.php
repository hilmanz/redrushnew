<?php
class WidgetEvent extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $_template = '';
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->run();
	}
	
	function run(){
		$list = $this->getLatestEvent();
		$this->View->assign("list",$list);
	}
	
	function getLatestEvent($total=3){
		$sql = "some sql here";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		$this->_template = "latest_event";
		return $rs;
	}
	
	function getListEvent($total=3){
		$sql = "some sql here";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		$this->_template = "event_list";
		return $rs;
	}
	
	function getEvent($id){
		$sql = "some sql here";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		$this->_template = "event_detail";
		return $rs;
	}
	
	function __toString(){
		return $this->View->toString("Social/widgets/".$this->_template.".html");
	}
	
}
?>