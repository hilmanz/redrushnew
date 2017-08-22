<?php
global $ENGINE_PATH;
include_once "../config/config.inc.php";
include_once $ENGINE_PATH."Utility/gapi/gapi.class.php";

class weeklyReport extends SQLData{
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
		
		//TOP USER
		$rs = $dashboard->get20topPlayer(50);	
		$this->View->assign('TopUser', $rs);
		
		//WEEKLY REPORT
		$rs = $dashboard->weeklyReport($startDate,$endDate);	
		// print_r('<pre>');print_r($rs);exit;
		$this->View->assign('weeklyreport', $rs);
		
		//SUMMARY REPORT
		$startProject = '2012-04-02';
		$rs = $dashboard->weeklyReport($startProject,$endDate,true);	
		// print_r('<pre>');print_r($rs);exit;
		$this->View->assign('summaryReport', $rs);
		$this->View->assign('startProjectReport', $startProject);
		if($startDate == null) $startDate2 =  date('Y-m-d',strtotime('-8 days'));
		else $startDate2 = $startDate ;
		if($endDate == null) $endDate = date('Y-m-d',strtotime('-1 days'));
		$this->View->assign('startDateReport', $startDate2 );
		$this->View->assign('endDateReport', $endDate);
		
		return $this->View->toString("RedRushWeb/dashboard/weeklyReport.html");
	}
	
}