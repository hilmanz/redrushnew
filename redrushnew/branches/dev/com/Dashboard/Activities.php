<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class Activities extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	function admin(){
		$act = $this->Request->getParam('act');
		
		return $this->main();
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
		//PVD
		$data = $dashboard->pageViewDistribution($startDate,$endDate);
		//var_dump($data);
		$this->View->assign("tgl", $data['tgl']);
		$this->View->assign("pvd", $data['data']);
		
		//Activity Distribution
		$data = $dashboard->getAD($startDate,$endDate);
		$this->View->assign("AD", $data);
		
		//Average Time on Activity Distribution
		$data = $dashboard->averageTimeonActDistribution($startDate,$endDate);
		$this->View->assign("ATOAD", $data);
		
		//Racing
		$data = $dashboard->getRacingModifMiniGame($startDate,$endDate);
		$this->View->assign("racing", $data['race']['count']);
		$this->View->assign("raceArrow",  $data['race']['raceArrow']);
		$this->View->assign("racePercent",  $data['race']['racePercentage']);
		//Car Modification
		$this->View->assign("carmodif", $data['car']['count']);
		$this->View->assign("carArrow", $data['car']['carArrow']);
		$this->View->assign("carPercent", $data['car']['carPercentage']);
		
		//Total Mini Game Played
		
		//var_dump($miniPercentage);exit();
		$this->View->assign("mini", $data['minigame']['count']);
		$this->View->assign("miniArrow",  $data['car']['miniArrow']);
		$this->View->assign("miniPercent", $data['car']['miniPercent']);
		// print_r($rs);exit;
		
		//Racing: Time on each level
		$data = $dashboard->racingLevelOnTime($startDate,$endDate);
		$this->View->assign("TOEL", $data);
		
		//Play by Game and Average Time on Game
		$data = $dashboard->gamePlayAndTime($startDate,$endDate);
		$this->View->assign("PBG", $data);
		
		
		//Merchandise Redeem
		$data = $dashboard->getMerchandiseRedeem($startDate,$endDate);
		$this->View->assign("total_merchandise", $data['redeem_count']);
		// print_r($data['redeem_count']);exit;
		$this->View->assign("MR", $data['data']);
		
		$this->View->assign("menu","activities");
		return $this->View->toString("RedRushWeb/dashboard/activities.html");
	}
}