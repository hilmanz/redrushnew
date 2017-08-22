<?php
include_once $ENGINE_PATH."Security/Authentication.php";
include_once $ENGINE_PATH."Security/Permission.php";
include_once $ENGINE_PATH."Admin/UserManager.php";
include_once $ENGINE_PATH."Admin/PluginManager.php";
include_once $APP_PATH."RedRushWeb/helper/milisecHelper.php";

class Admin extends SQLData{
	var $auth ;
	var $perm;
	var $user;
	var $strHTML;
	var $milisecHelper;
	var $View;
	var $DEBUG=false;
	var $plugin;
	function Admin(){
		
		parent::SQLData();
		$this->auth = new Authentication();
		$this->perm = new Permission();
		$this->user = new UserManager();
		$this->View = new BasicView();	
		$this->plugin = new PluginManager();
		$this->milisecHelper = new milisecHelper();
	}
	
	function show(){
		if(!$this->auth->isLogin()){
			$this->strHTML = $this->showLoginPage();
		}else{
			$this->strHTML = $this->showAdminPage();
		}
		if($this->DEBUG){
			print $this->getMessage();
			print $this->perm->getMessage();
			print $this->user->getMessage();
		}
	}
	function loadPlugin($request,$reqID){
		global $APP_PATH,$ENGINE_PATH;
		
		$rs = $this->plugin->getPluginByRequestID($reqID);
		
		if(file_exists($APP_PATH.$rs['plugin_path'].$rs['className'].".php")){
			include_once $APP_PATH.$rs['plugin_path'].$rs['className'].".php";
			$className =  $rs['className'];
			$instance = new $className($request);
			return $instance;
		}else{
			//print $APP_PATH.$rs['plugin_path'].$rs['className'].".php";
			return false;
		}
	}
	function showDashboard($req){
		include_once "DashboardManager.php";
		$dashboard = new DashboardManager(null);
		$output = $dashboard->load();
		
		if($req->getParam('rangeDate')==1)  
		{
			// print_r($_SESSION['gapi']);exit;
		$startDate = $req->getParam('from');
		$endDate = $req->getParam('to');
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
		$this->View->assign("from", $_SESSION['gapi']['startDate']);
		$this->View->assign("to", $_SESSION['gapi']['endDate']);
		
	
		//7daysVisits
		$sevenDaysVisits = $_SESSION['gapi']['mainDashboard']["visits7day"];
		$this->View->assign("sevenDvisit", $sevenDaysVisits);
		
		//VISITs
		$yesterday = $_SESSION['gapi']['mainDashboard']["yesterday"]["visits"];
		$twodaysago = $_SESSION['gapi']['mainDashboard']["twodaysago"]["visits"];
		$this->View->assign("visitor",$yesterday);
		$visitorTemp = (($yesterday-$twodaysago)/$yesterday)*100;
		if (abs($visitorTemp) <1){
			$visitorArrow = round($visitorTemp,2);
		}else{
			$visitorArrow = round($visitorTemp);
		}
		$visitorPercentage = abs($visitorArrow);
		$this->View->assign("visitorArrow", $visitorArrow);
		$this->View->assign("visitorPercentage", $visitorPercentage);
		
		//Average Page Views
		$avgpageview = $dashboard->getAVGpageViews($startDate,$endDate);
		$yesterday = intVal($avgpageview[0]["page_view_count"])/intVal($avgpageview[0]["login_count"]);
		$twodaysago = intVal($avgpageview[1]["page_view_count"])/intVal($avgpageview[1]["login_count"]);
		$this->View->assign("apv", $yesterday);
		$apvTemp = (($yesterday-$twodaysago)/$yesterday)*100;
		if (abs($apvTemp) <1){
			$apvArrow = round($apvTemp,2);
		}else{
			$apvArrow = round($apvTemp);
		}
		$apvPercentage = abs($apvArrow);
		$this->View->assign('apvPercentage', $apvPercentage);
		$this->View->assign('apvArrow', $apvArrow);
		
		//Participant
		$participant = $dashboard->getParticipants($startDate,$endDate);
		// $yesterday = intVal($participant[0]["participant_count"]);
		// $twodaysago = intVal($participant[1]["participant_count"]);
		$this->View->assign("participants", $participant['participant_count']);
		// $parTemp = (($yesterday-$twodaysago)/$yesterday)*100;
		// if (abs($parTemp) <1){
			// $parArrow = round($parTemp,2);
		// }else{
			// $parArrow = round($parTemp);
		// }
		
		// $parPercentage = abs($parArrow);
		// $this->View->assign('parPercentage', $parPercentage);
		// $this->View->assign('parArrow', $parArrow);
		
		//Conversation Rate
		
		//unique user / total registration
		// $conversionRate = $participant['participant_count'] / $user['users'];
		// $yesterday = $_SESSION['gapi']['mainDashboard']["yesterday"]["conversion_rate"];
		// $twodaysago = $_SESSION['gapi']['mainDashboard']["twodaysago"]["conversion_rate"];
		// $this->View->assign("conversation", $conversionRate);
		// $crTemp = (($yesterday-$twodaysago)/$yesterday)*100;
		// if (abs($crTemp) <1){
			// $crArrow = round($crTemp,2);
		// }else{
			// $crArrow = round($crTemp);
		// }
		// $crPercentage = abs($crArrow);
		// $this->View->assign('crPercentage', $crPercentage);
		// $this->View->assign('crArrow', $crArrow);
		
		//Loyalty
		$yesterday = $_SESSION['gapi']['mainDashboard']["yesterday"]["loyalty"];
		$twodaysago = $_SESSION['gapi']['mainDashboard']["twodaysago"]["loyalty"];
		$this->View->assign("loyalty", $yesterday);
		$loyTemp = (($yesterday-$twodaysago)/$yesterday)*100;
		//var_dump($yesterday);
		//var_dump($twodaysago);
		if (abs($loyTemp) <1){
			$loyArrow = round($loyTemp,2);
		}else{
			$loyArrow = round($loyTemp);
		}
		$loyPercentage = abs($loyArrow);
		$this->View->assign('loyPercentage', $loyPercentage);
		$this->View->assign('loyArrow', $loyArrow);
		
		//Time on site
		$yesterday = $_SESSION['gapi']['mainDashboard']["yesterday"]["time_onSite"];
		$twodaysago = $_SESSION['gapi']['mainDashboard']["twodaysago"]["time_onSite"];
		//var_dump($yesterday);
		//var_dump($twodaysago);
		$this->View->assign("tos", $this->milisecHelper->formatSeconds($yesterday));
		$tosTemp = (($yesterday-$twodaysago)/$yesterday)*100;
		if (abs($tosTemp) <1){
			$tosArrow = round($tosTemp,2);
		}else{
			$tosArrow = round($tosTemp);
		}
		$tosPercentage = abs($tosArrow);
		$this->View->assign('tosPercentage', $tosPercentage);
		$this->View->assign('tosArrow', $tosArrow);
		
		//User Count
		$user = $dashboard->getUserCount($startDate,$endDate);	
// print_r($user);	exit;	
		$userTemp = $dashboard->getUserPercentage($startDate,$endDate);
		if (abs($userTemp) <1){
			$userPercentage = round($userTemp,2);
		}else{
			$userPercentage = round($userTemp);
		}
		$intUser=abs($userPercentage);
		$this->View->assign("userArrow",$userPercentage);
		$this->View->assign("userPercentage",$intUser);
		$this->View->assign("users",$user['users']);
		
		//conversion rate
		$conversionRate = ($participant['participant_count'] / $user['users'])*100;
		$this->View->assign("conversation", $conversionRate);
			
		//Visit,Users,Participant
		$vNp = $dashboard->getVisitdanParticipant($startDate,$endDate);
		$this->View->assign("vNp",$vNp);
		// print_r($vNp);exit;
		//Page View Distribution
		$pvd = $dashboard->getPVD($startDate,$endDate);
		$this->View->assign("pvd",$pvd);
		
		//Activity Distribution
		$ad = $dashboard->getAD($startDate,$endDate);
		$this->View->assign("ad", $ad);
		
		//Geographical Distribution
		$gd = $dashboard->getGD($startDate,$endDate);
		$this->View->assign("gd", $gd);
		
		//AVG Visit
		//$avgVisit = $dashboard->getVisits7day();
		//$this->View->assign("avgVisit", $avgVisit);
		
		//url
		// print_r('<pre>');print_r($this);exit;
		$chartType = "'registration','registrationProgress','RegistrationProgressSBA','RegistrationProgressDST','ProgramWeekProgress','DSTPerformance','RedrushTruckPerformance','RedrushFlashMOPPerformance'";
		$chartImage = $dashboard->getChartimage($chartType);
		
		foreach($chartImage as $key => $val){
			$chart[$val['type']] = $val['img'];		
		}
		// print_r($chartImage);exit;
		$this->View->assign("chart",$chart);
		$this->View->assign("DASHBOARD_CONTENT",$output);
		$this->View->assign("user",array("username"=>$this->auth->Session->getVariable("username")));
		$this->View->assign("content",$this->View->toString("RedRushWeb/dashboard/mainDashboard.html"));
	}
	function showAdminPage(){
        $this->View->assign("user",array("username"=>$this->auth->Session->getVariable("username")));
		return $this->View->toString("RedRushWeb/dashboard/admin.html");
	}
	function toString(){
		return $this->strHTML;
	}
	function showLoginPage(){
		if($_GET['f']==1){
			$this->View->assign("msg","Access Denied !");
		}
		return $this->View->toString("RedRushWeb/dashboard/login.html");
	}
	function execute($obj,$reqID){
		if($this->perm->isAllowed($reqID)){
			$this->View->assign("page",$reqID);
			// print_r($_SESSION['gapi']);exit;
			$this->View->assign("from", $_SESSION['gapi']['startDate']);
			$this->View->assign("to", $_SESSION['gapi']['endDate']);
			if($obj->autoconnect){
				$obj->open();
				$this->View->assign("content",$obj->admin());
				$obj->close();
			}else{
				$this->View->assign("content",$obj->admin());
			}
		}else{
			$this->View->assign("content","Access Denied !");
		}
		if($this->DEBUG){
			print $obj->getMessage();
		}
	}
	function attach($obj,$reqID,$arr,$adminMode=true){
		if($adminMode){
			if($this->perm->isAllowed($reqID)){
				$obj->open();
				
				for($i=0;$i<sizeof($arr);$i++){
					$this->View->assign("addon_".$arr[$i],$obj->addon($arr[$i]));	
				}
				$obj->close();
				
			}
		}else{
			for($i=0;$i<sizeof($arr);$i++){
				$this->View->assign("addon_".$arr[$i],$obj->addon($arr[$i]));	
			}
		}
		
		
		if($this->DEBUG){
			print $obj->getMessage();
		}
		
	
	}
}
?>