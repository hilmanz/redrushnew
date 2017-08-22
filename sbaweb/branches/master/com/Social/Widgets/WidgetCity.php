<?php
class WidgetCity extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->run();
	}
	function run(){
		$list = $this->getTopCity();
		$this->View->assign("list",$list);
	}
	function getTopCity($total=1){
		$sql = "some sql here";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	function __toString(){
		return $this->View->toString("Social/widgets/top_city.html");
	}
	
}
?>