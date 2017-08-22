<?php
global $ENGINE_PATH;
include_once "../config/config.inc.php";
include_once $ENGINE_PATH."Utility/gapi/gapi.class.php";

class chart extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	function admin(){
		$act = $this->Request->getParam('act');
		if( $act == 'new' ){
			
		}else{
			return $this->main();
		}
	}

	function main(){
		include_once "DashboardManager.php";
		$dashboard = new DashboardManager(null);
		$this->close();
		$startDate =null;$endDate =null;
		if($this->Request->getParam('rangeDate')==1)  
		{
		$startDate = $this->Request->getParam('from');
		$endDate = $this->Request->getParam('to');
		if($_SESSION['gapi']['rangeDate'] != $startDate.$endDate){
			$_SESSION['gapi'] = null;
			$_SESSION['gapi'] = $dashboard->getGapi($startDate,$endDate);
			$_SESSION['gapi']['rangeDate'] = $startDate.$endDate;
			$_SESSION['gapi']['startDate'] = $startDate;
			$_SESSION['gapi']['endDate'] = $endDate;
			
		}else{
		$startDate = $_SESSION['gapi']['startDate'];
		$endDate = $_SESSION['gapi']['endDate'] ;
		}
		}else	{
			if(isset($_SESSION['gapi']['rangeDate'])) {
			$startDate = $_SESSION['gapi']['startDate'];
			$endDate = $_SESSION['gapi']['endDate'] ;
			}	
		if(!isset($_SESSION['gapi'])) $_SESSION['gapi'] = $dashboard->getGapi();
		}
		
		
		
		return $this->View->toString("RedRushWeb/dashboard/weeklyReport.html");
	}
	
}