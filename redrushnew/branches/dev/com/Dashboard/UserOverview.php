<?php
global $ENGINE_PATH;
include_once "../config/config.inc.php";
include_once $ENGINE_PATH."Utility/gapi/gapi.class.php";

class UserOverview extends SQLData{
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
		
		// print_r($_SESSION['gapi']);exit;
		//var_dump($_SESSION['gapi']['UserOverview']);
		
		//AVG time on site
		$yesterdays = $_SESSION['gapi']['UserOverview']["yesterday"]["avg_time"];
		$twodaysagos = $_SESSION['gapi']['UserOverview']["twodaysago"]["avg_time"];
		//var_dump($yesterdays);
		$this->View->assign("avg", $yesterdays);
		$avgTemp = (($yesterdays-$twodaysagos)/$yesterdays)*100;
		if (abs($avgTemp) <1){
			$avgArrow = round($avgTemp,2);
		}else{
			$avgArrow = round($avgTemp);
		}
		$avgPercentage = abs($avgArrow);
		$this->View->assign('avgPercentage', $avgPercentage);
		$this->View->assign('avgArrow', $avgArrow);
		
		//Loyalty
		$yesterdays = $_SESSION['gapi']['UserOverview']["yesterday"]["loyalty"];
		//var_dump($yesterdays);
		//print_r($yesterdays);
		$twodaysagos = $_SESSION['gapi']['UserOverview']["twodaysago"]["loyalty"];
		$this->View->assign("loyalty", $yesterdays);
		$loyArrow = round((($yesterdays-$twodaysagos)/$yesterdays)*100);
		$loyPercentage = abs($loyArrow);
		$this->View->assign('loyPercentage', $loyPercentage);
		$this->View->assign('loyArrow', $loyArrow);
		
		//Bounce Rate
		$yesterdays = $_SESSION['gapi']['UserOverview']["yesterday"]["bounce_rate"];
		$twodaysagos = $_SESSION['gapi']['UserOverview']["twodaysago"]["bounce_rate"];
		$this->View->assign("bounce", $yesterdays);
		$bArrow = round((($yesterdays-$twodaysagos)/$yesterdays)*100);
		$bPercentage = abs($loyArrow);
		$this->View->assign('bPercentage', $bPercentage);
		$this->View->assign('bArrow', $bArrow);
		
		
		//Geographical Distribution
		$data = $dashboard->getGD($startDate,$endDate);
		$this->View->assign('GD', $data);
		
		//GENDER/AGE
		$data = $dashboard->getGender($startDate,$endDate);
		// var_dump($data);exit;
		$this->View->assign('GENDER', $data);
		
		//Devices Used
		$data = $dashboard->getDeviceUse($startDate,$endDate);
		$this->View->assign('DU', $data);
		
		//Brand Preference
		$data = $dashboard->getBrandPref($startDate,$endDate);
		// print_r($rs);exit;
		$this->View->assign('BP', $data);

	
		
		
		return $this->View->toString("RedRushWeb/dashboard/useroverview.html");
	}
	
}