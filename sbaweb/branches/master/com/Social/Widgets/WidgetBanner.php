<?php
global $APP_PATH;
class WidgetBanner extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $html;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->run();
	}
	function run(){
		global $APP_PATH;
		include_once $APP_PATH."Banner/Banner.php";
		$banner = new Banner($this->Request);
		$this->html =  $banner->view_banner();
		
	}
	function header_banner(){
		global $APP_PATH;
		include_once $APP_PATH."Banner/Banner.php";
		$banner = new Banner($this->Request);
		return $banner->header_banner(); //html
	}
	function sidebar_banner(){
		global $APP_PATH;
		include_once $APP_PATH."Banner/Banner.php";
		$banner = new Banner($this->Request);
		return $banner->sidebar_banner(); //html
	}
	/*
	function run(){
		global $APP_PATH;
		include_once $APP_PATH."Banner/Banner.php";
		$banner = new Banner($this->Request);
		$this->html =  $banner->view_banner();
		
	}*/
	
	function getBanner(){
		//$sql = "some sql here";
		//$this->open(0);
		//$rs = $this->fetch($sql,1);
		//$this->close();
		//return $rs;
		return "";
	}
	function __toString(){
		return $this->html;
		//return $this->View->toString("Social/widgets/banner.html");
	}
	
}
?>