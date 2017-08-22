<?php
class WidgetNetworkUpdates extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $user_id;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		
	}
	
	
	function getNetworkUpdates($user_id){
		
		$this->View->assign("user_id",$user_id);
	}
	function __toString(){
		return $this->View->toString("Social/widgets/network_updates.html");
	}
	
}
?>
