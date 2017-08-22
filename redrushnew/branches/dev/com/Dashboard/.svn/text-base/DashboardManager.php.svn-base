<?php 
global $ENGINE_PATH;
include_once "../config/config.inc.php";
include_once $ENGINE_PATH."Utility/gapi/gapi.class.php";

class DashboardManager extends SQLData{
	var $View;
	var $Request;
	
	function DashboardManager($req){
		$this->SQLData();
		$this->View = new BasicView();
		$this->Request = $req;
	}
	function getGapi($startDate=null,$endDate=null){
		// $this->open(0);
		// $rs = $this->fetch("SELECT COUNT(id) AS total FROM ".RedRushDB.".kana_member WHERE n_status = 1 AND verified = '1' AND login_count >= 0");		
		// $this->close();
		if($startDate!=null && $endDate!=null){
		$rs = $this->getRegistrantCount($startDate,$endDate);
		//init Date
		$startProjectDate = $startDate;
		$sevenDaysago = $startDate;
		
		$yesterday =$endDate;		
		$twodaysago =$endDate;
		// $yesterdays = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
		// $threedaysago = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-3,date("Y")));
		}else{
		$rs = $this->getRegistrantCount();
		//init Date
		$startProjectDate = '2012-04-02';
		$sevenDaysago = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
		
		$yesterday = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		$twodaysago = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-2,date("Y")));

		// $yesterdays = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
		// $threedaysago = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-3,date("Y")));
		}
		

		// print_r($startDate.$endDate);exit;
		// $startDate,$endDate
		// if(file_exists('../config/config.inc.php')) echo 'asda';exit;
		// print_r($rs);exit;
		$total_user = intval($rs['total']);
		//var_dump($total_user);
		
		//print_r(date("Y-m-d",$yesterdays));exit;
		
		//var_dump(date("Y-m-d", $twodaysago));
		$ga = new gapi(ga_email,ga_password);
		$ga->requestReportData(ga_profile_id,array('country'),array('timeOnSite','pageviews','visits','visitors','newVisits'), null,null,
				$startProjectDate, // Start Date
				$yesterday, // End Date
				1,  // Start Index
				500 // Max results
		);
		$gaResult  = array('page_views'=>$ga->getPageviews(),
				'visits'=>$ga->getVisits(),
				'time_onSite'=>round($ga->getTimeOnSite()/$ga->getVisits()),
				'unique_visitors'=>$ga->getVisitors(),
				'new_visits'=>$ga->getNewVisits(),
				'conversion_rate'=>round($total_user/$ga->getVisitors()*100,2),
				'loyalty'=>round(($ga->getVisits()-$ga->getNewVisits())/$ga->getVisits()*100,2));
		
		$ga2 = new gapi(ga_email,ga_password);
		$ga2->requestReportData(ga_profile_id,array('country'),array('timeOnSite','pageviews','visits','visitors','newVisits'), null,null,
				$startProjectDate, // Start Date
				$twodaysago, // End Date
				1,  // Start Index
				500 // Max results
		);
		$gaResult2  = array('page_views'=>$ga2->getPageviews(),
				'visits'=>$ga2->getVisits(),
				'time_onSite'=>round($ga2->getTimeOnSite()/$ga2->getVisits()),
				'unique_visitors'=>$ga2->getVisitors(),
				'new_visits'=>$ga->getNewVisits(),
				'conversion_rate'=>round($total_user/$ga2->getVisitors()*100,2),
				'loyalty'=>round(($ga2->getVisits()-$ga2->getNewVisits())/$ga2->getVisits()*100,2));
		
		
		$ga3 = new gapi(ga_email,ga_password);
		$ga3->requestReportData(ga_profile_id,array('date'),array('visits','timeOnSite'), array('date'),null,
				$sevenDaysago, // Start Date
				$yesterday, // End Date
				1,  // Start Index
				500 // Max results
		);
		
		$gaTemp3 = array();
		$i=0;
		foreach ($ga3->getResults() as $result){
			$gaTemp3[$i] = array('datee' => $result->getDate(),'visits' => $result->getVisits(), 'timeOnSite' => round($result->getTimeOnSite()/$result->getVisits()));
		$i++;
		}
		$gaResult3 = json_encode($gaTemp3);
		
		// print_r($gaResult3);exit;
		$listGa['mainDashboard'] = array('yesterday' => $gaResult, 'twodaysago' => $gaResult2, 'visits7day' => $gaResult3);
		
		
		//useroverview
		$ga->requestReportData(ga_profile_id,array('country'),array('avgTimeOnSite','visitBounceRate','visits','visitors','newVisits'), null,null,
				$startProjectDate, // Start Date
				$yesterday, // End Date
				1,  // Start Index
				500 // Max results
		);
		$gaResult  = array(
				'bounce_rate'=>$ga->getVisitBounceRate(),
				'avg_time'=>$ga->getAVGTimeOnSite(),
				'loyalty'=>round(($ga->getVisits()-$ga->getNewVisits())/$ga->getVisits()*100,2));
		
		$ga2 = new gapi(ga_email,ga_password);
		$ga2->requestReportData(ga_profile_id,array('country'),array('avgTimeOnSite','visitBounceRate','visits','visitors','newVisits'), null,null,
				$startProjectDate, // Start Date
				$twodaysago, // End Date
				1,  // Start Index
				500 // Max results
		);
		$gaResult2  = array(
				'bounce_rate'=>$ga2->getVisitBounceRate(),
				'avg_time'=>$ga2->getAVGTimeOnSite(),
				'loyalty'=>round(($ga2->getVisits()-$ga2->getNewVisits())/$ga2->getVisits()*100,2));
		
	
		$listGa['UserOverview'] = array('yesterday' => $gaResult, 'twodaysago' => $gaResult2);
		
		//var_dump($listGa);//exit;
		return $listGa;
	}
	
	
	
	function getAVGpageViews($startDate=null,$endDate=null){
	
		if($startDate!=null && $endDate!=null) $date = " date_d >= '{$startDate}' AND date_d <= '{$endDate}' ";
		else $date = " date_d >= DATE_SUB(CURRENT_DATE, INTERVAL 2 DAY) ";
		
		$sql = "SELECT page_view_count, login_count 
		FROM ".ReportDB.".rp_overall_daily 
		WHERE {$date}
		ORDER BY date_d DESC";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		// var_dump($sql);exit;
		return $rs;
	}
	function getParticipants($startDate=null,$endDate=null){
		if($startDate!=null && $endDate!=null) {
			$date = " date >= '{$startDate}' AND date <= '{$endDate}' ";
			$date_time = "  date_time  >= '{$startDate}' AND date_time <= '{$endDate}' ";
			$start_date_time = "  start_date_time  >= '{$startDate}' AND start_date_time <= '{$endDate}' ";
		}
		else {
		$date = "  date >= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ";
		$date_time = "  date_time <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ";
		$start_date_time = "  start_date_time <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ";
		}
		
		$sql ="
		SELECT user_id
		FROM ".ReportDB.".rp_daily_level_user_per_part partlevel
		WHERE  {$date}
		AND level = 1		
		AND
		EXISTS (
			SELECT user_id
			FROM marlboro_redrush_game_2012.racing_level rlvl
			WHERE partlevel.user_id = rlvl.user_id
			AND rlvl.level =1
		)		
		GROUP BY user_id
		ORDER BY date DESC
		";
		
		$this->open(0);
		$rs = $this->fetch($sql,1);
	
		foreach($rs as $val)
		{
			//cek racing 1.. date_time
			$sql1 ="SELECT count(id) as total FROM ".RedRushDB.".tbl_activity_log WHERE {$date_time} AND action_id = 4 AND user_id= {$val['user_id']} LIMIT 1";
			$a = $this->fetch($sql1);
			//cek minigame ..start_date_time
			$sql2 ="SELECT count(id) as total FROM ".RedRushDB.".tbl_activity_mini_game WHERE {$start_date_time} AND user_id= {$val['user_id']} LIMIT 1";
			$b = $this->fetch($sql2);
			//cek buy part ..date_time
			$sql3 ="SELECT count(id) as total FROM ".RedRushDB.".tbl_purchase_part WHERE {$date_time} AND user_id= {$val['user_id']} LIMIT 1 ";
			$c = $this->fetch($sql3);
			//cek modif.. date_time
			$sql4 ="SELECT count(id) as total FROM ".RedRushDB.".tbl_activity_log WHERE {$date_time} AND action_id = 16 AND user_id= {$val['user_id']} LIMIT 1";
			$d = $this->fetch($sql4);
			
			if($a['total']>0) $arrParticipant[$val['user_id']][]=true;
			else $arrParticipant[$val['user_id']][]=false;
			if($b['total']>0) $arrParticipant[$val['user_id']][]=true;
			else $arrParticipant[$val['user_id']][]=false;
			if($c['total']>0) $arrParticipant[$val['user_id']][]=true;
			else $arrParticipant[$val['user_id']][]=false;
			if($d['total']>0) $arrParticipant[$val['user_id']][]=true;
			else $arrParticipant[$val['user_id']][]=false;
		}
		
		$this->close();
			// print_r($sql1);
		// print_r($arrParticipant);
		$rs=null;
		$total = count($arrParticipant);
		foreach($arrParticipant as $key => $val){
			if(in_array(false,$arrParticipant[$key])) $total--;
		}
		$result['participant_count']=$total;
		
		return $result;
	}
	function getUserCount($startDate=null,$endDate=null){
		if($startDate!=null && $endDate!=null) $date = " date_time >= '{$startDate}' AND date_time <= '{$endDate}' ";
		else $date = "  date_time <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)  ";
	
		$sql = "SELECT count(tUsers) users FROM 
		(
		SELECT count(user_id) as tUsers 
		FROM ".RedRushDB.".tbl_activity_log 
		WHERE action_id = 1 AND 
		{$date}
		GROUP BY user_id 
		) as user";
	
		$this->open(0);
		$data = $this->fetch($sql);
		// $data = $this->fetch("SELECT user_count AS users FROM ".ReportDB.".rp_overall_daily WHERE date_d >= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)");
		$this->close();
		// print_r($sql);exit;
		return $data;
	}
	
	function getRegistrantCount($startDate=null,$endDate=null){
	
		if($startDate!=null && $endDate!=null) $date = " register_date >= '{$startDate}' AND register_date <= '{$endDate}' ";
		else $date = "  register_date <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)  ";
	
		$sql ="
		SELECT count(email) total 
		FROM ".RedRushDB.".kana_member km
		WHERE 
		{$date}
		AND exists (SELECT email FROM ".ReportDB.".tbl_new_user_mop WHERE types = 3 AND km.email = email)
		";
		
		$this->open(0);
		$data = $this->fetch($sql);
		//$data = $this->fetch("SELECT count(email) total FROM ".ReportDB.".tbl_new_user_mop WHERE types = 3");
		// $data = $this->fetch("SELECT user_count AS users FROM ".ReportDB.".rp_overall_daily WHERE date_d >= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)");
		$this->close();
				// print_r($sql);exit;
		return $data;
	}
	function getUserPercentage($startDate=null,$endDate=null){
		
		
		if($startDate!=null && $endDate!=null) $date = " date_d >= '{$startDate}' AND date_d <= '{$endDate}' ";
		else $date = "  date_d >= DATE_SUB(CURRENT_DATE, INTERVAL 2 DAY)   ";
		
		$sql ="
		SELECT user_count 
		FROM ".ReportDB.".rp_overall_daily 
		WHERE {$date}
		ORDER BY date_d DESC";
		
		$this->open(0);
		$data = $this->fetch($sql,1);
		$this->close();
		$day1 = intVal($data[0]["user_count"]);
		$day2 = intVal($data[1]["user_count"]);
		//var_dump($day1);
		$visitorPercent = abs(round((($day1-$day2)/$day1)*100));
		//var_dump($visitorPercent);
		return $visitorPercent;
	}
	function getVisitdanParticipant($startDate=null,$endDate=null){
		
		if($startDate!=null && $endDate!=null) $date = " date_d >= '{$startDate}' AND date_d <= '{$endDate}' ";
		else $date = "  date_d >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)   ";
	
		$sql ="
		SELECT date_d, user_count, participant_count 
		FROM ".ReportDB.".rp_overall_daily 
		WHERE {$date} 
		ORDER BY date_d ASC";
		
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		//$sample = array(array("date_time" => '2012-02-20 00:00:00', visit => 432), array("date_time" => '2012-02-21 00:00:00', visit => 789),array("date_time" => '2012-02-22 00:00:00', visit => 654),array("date_time" => '2012-02-23 00:00:00', visit => 567),array("date_time" => '2012-02-24 00:00:00', visit => 942),array("date_time" => '2012-02-25 00:00:00', visit => 398));
		$data = json_encode($rs);
		// print_r($sql);exit;
		return $data;
	}
	function getPVD($startDate=null,$endDate=null){
		
		if($startDate!=null && $endDate!=null) 
		{
		$date = " date_d >= '{$startDate}' AND date_d <= '{$endDate}' ";
		$select = " date_d, action_id, title, SUM(pageview_count) as pageview_count ";
		$group = "GROUP BY action_id,title ORDER BY date_d DESC";
		}else {
		$date = "  date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)   ";
		$select = " date_d, action_id, title, pageview_count  ";
		$group = "";
		}
	
		$sql ="
		SELECT {$select} 
		FROM ".ReportDB.".rp_overall_pageview_daily 
		WHERE {$date}
		AND action_id = 7 
		AND title not in ('merchandiseList','home','merchandiseDetail')
		{$group}
		";
		
		$this->open(0);
		$rs = $this->fetch($sql,1);
		//sample
		// $rs = $this->fetch("SELECT date_d, action_id, title, pageview_count FROM ".ReportDB.".rp_overall_pageview_daily WHERE action_id = 7 AND date_d = '2012-03-30'",1);
		// $minigame = $this->fetch("SELECT date_time,count(action_id) pageview_count,action_id, action_id as title  FROM ".RedRushDB.".tbl_activity_log WHERE date_time <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) AND action_id in (11,12,13,14) group by action_id",1);
		//date range : SELECT date_format(date_time,'%Y-%m-%d') as date_times,count(action_id) pageview_count,action_id, action_id as title  FROM tbl_activity_log WHERE date_time <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) AND action_id in (11,12,13,14) group by action_id,date_times
	
		$this->close();
		$n=0;
		require "copytext_dashboard.php";
		
		foreach($rs as $val){
		if($page_view[$val['title']]) $name = $page_view[$val['title']];
		else $name = $val['title'];
		$rs[$n]['title'] =  $name;
		$n++;
		}
		// $rs2 = array_merge($rs,$minigame);
		// print_r($rs2);exit;
		$data = json_encode($rs);
		// print_r($sql);exit;
		return $data;
	}
	function getAD($startDate=null,$endDate=null){
		
			
		if($startDate!=null && $endDate!=null) 
		{
		$date = " date_time >= '{$startDate}' AND date_time <= '{$endDate}' ";
		$selectLog = " date_time as date_d,activity_id,SUM(num) as num   ";
		$groupLog = " GROUP BY activity_id ";
		$selectEv = " date_time as date_d,game_id as activity_id,SUM(num) as num, game_id as  activity_name   ";
		$groupEv = " GROUP BY activity_id ";
		}
		else {
		$date = "  date_time = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)  ";
		$selectLog = " date_time as date_d,activity_id,num     ";
		$groupLog = "";
		$selectEv = " date_time as date_d,game_id as activity_id,num, game_id as  activity_name   ";
		$groupEv = "";
		}
			
		$this->open(0);
		//$rs = $this->fetch("SELECT date_d, activity_id, activity_name, percentage, num, total FROM ".ReportDB.".rp_activity_dist_daily WHERE date_d = '2012-03-30'",1);
		//$rs = $this->fetch("SELECT date_d, activity_id, activity_name, percentage, num, total FROM ".ReportDB.".rp_activity_dist_daily WHERE date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)",1);
		$logAct = "
		SELECT {$selectLog}
		FROM ".ReportDB.".rp_activity_log_daily_data 
		WHERE  activity_id in (1,4,6,11,12,13,14,16) 
		AND {$date}
		{$groupLog}
		";
		
		$eventAct= "
		SELECT {$selectEv}
		FROM ".ReportDB.".rp_ipad_daily_data 
		WHERE {$date}
		{$groupEv}
		";
		$logActArr = $this->fetch($logAct,1);
		$eventActArr = $this->fetch($eventAct,1);
		$this->close();
		// print_r('<pre>');print_r($eventActArr);exit;
		
		require "copytext_dashboard.php";
		// if(file_exists("copytext_dashboard.php")) echo  'ada';
		
		$n=0;
		foreach($logActArr as $valLog){
		$rs[$n]['date_d'] = $valLog['date_d'];
		$rs[$n]['activity_id'] = $valLog['activity_id'];
		$rs[$n]['num'] = $valLog['num'];
		if($activity_distribution[$valLog['activity_id']]) $name = $activity_distribution[$valLog['activity_id']];
		else $name = $valLog['activity_id'];
		$rs[$n]['activity_name'] = $name;
		$n++;
		}
		foreach($eventActArr as $valEve){
		$rs[$n]['date_d'] = $valEve['date_d'];
		$rs[$n]['activity_id'] = $valEve['activity_id'];
		$rs[$n]['num'] = $valEve['num'];
		if($activity_distribution[$valEve['activity_name']]) $name = $activity_distribution[$valEve['activity_name']];
		else $name = $valEve['activity_name'];
		$rs[$n]['activity_name'] = $name;
		$n++;
		}
		
		// print_r('<pre>');print_r($activity_distribution);exit;
		
		$data = json_encode($rs);
		// print_r($logAct);exit;
		return $data;
	}
	function getGD($startDate=null,$endDate=null){
	
				
		if($startDate!=null && $endDate!=null) $date = " register_date >= '{$startDate}' AND register_date <= '{$endDate}' ";
		else $date = "  register_date <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)  ";
		
	
		$this->open(0);
		//$rs = $this->fetch("SELECT date_d, location, percentage ,num, total FROM ".ReportDB.".rp_geo_dist_daily WHERE date_d = '2012-03-30'",1);
		$sql ="	SELECT register_date as date_d,lookup.city  location, count(km.city) num
			FROM ".RedRushDB.".kana_member km 
			LEFT JOIN ".RedRushDB.".mop_city_lookup lookup ON lookup.id=km.city
			WHERE 
			exists (select user_id from ".GameDB.".racing_level lvl WHERE lvl.user_id=km.id)
			AND {$date}
			GROUP BY km.city order by num desc
			limit 10";
		// $rs = $this->fetch("SELECT date_d, location, percentage ,num, total FROM ".ReportDB.".rp_geo_dist_daily WHERE location not like '%other%' AND date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)",1);
		$rs = $this->fetch($sql,1);
		$this->close();
		
		$data = json_encode($rs);
		// print_r($sql);exit;
		return $data;
	}
	
	
	function getGender($startDate=null,$endDate=null){
	
		if($startDate!=null && $endDate!=null) {
		$date = " date_d >= '{$startDate}' AND date_d <= '{$endDate}' ";
		$select =" date_d, age_range, sex, sum( people_count ) people_count ";
		$group = "  GROUP by age_range ";
		
		}
		else {
		$date = "  date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ";
		$select =" date_d, age_range, sex, people_count ";
		$group = "";
		}
		
		$qMale	 ="
		SELECT {$select}
		FROM ".ReportDB.".rp_overall_gender_daily 
		WHERE 
		sex = 'M' 
		AND {$date} 
		{$group}
		";
		
		$qFemale ="
		SELECT {$select} 
		FROM ".ReportDB.".rp_overall_gender_daily 
		WHERE 
		sex = 'F' 
		AND {$date} 
		{$group}
		";
		
		$this->open(0);
		$male = $this->fetch($qMale,1);
		$female = $this->fetch($qFemale,1);
		//sample
		// $male = $this->fetch("SELECT date_d, age_range, sex, people_count FROM ".ReportDB.".rp_overall_gender_daily WHERE sex = 'M' AND date_d = '2012-03-30'",1);
		// $female = $this->fetch("SELECT date_d, age_range, sex, people_count FROM ".ReportDB.".rp_overall_gender_daily WHERE sex = 'F' AND date_d = '2012-03-30'",1);
		$this->close();
		$age['18-21'] = '18-24';
		$age['22-28'] = '25-29';
		$age['>= 29'] = '30+';
		foreach($male as $key => $val){
		$male[$key]['age_range'] = $age[$val['age_range']];
		}
		foreach($female as $key => $val){
		$female[$key]['age_range'] = $age[$val['age_range']];
		}
		$rs = array('male' => $male, 'female' => $female);
		// print_r($qMale);exit;
		$data = json_encode($rs);
	return $data;
	}
	
	
	function getDeviceUse($startDate=null,$endDate=null){
	
		$this->open(0);
		$q = "SELECT count(id) num,device_name FROM ".ReportDB.".rp_user_device WHERE device_name <> 'unknown' GROUP BY device_name";
		// $rs = $this->fetch("SELECT COUNT(device_name) AS num, device_name FROM ".ReportDB.".rp_user_device WHERE date_time = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) GROUP BY device_name",1);
		$rs = $this->fetch($q,1);
		$this->close();
		$n=0;

		$device_group_pc = array('Apple','Linux','Windows');
		$rs2 = array();
		//$device_group_mobile = array('Android','BlackBerry','iPad','iPhone','iPod','Nokia')	;
		$rs2[0]['device_name'] = 'PC';
		$rs2[1]['device_name'] = 'Mobile';
		foreach($rs as $val){
		
		if(in_array($val['device_name'],$device_group_pc)) $valuePC = $val['num'];
		else $valueMobile = $val['num'];
	
		$rs2[0]['num']+= $valuePC;
		$rs2[1]['num']+= $valueMobile;
		$valuePC=0;
		$valueMobile=0;
		$n++;
		}
		// print '<pre>';print_r($rs2);exit;
		$data = json_encode($rs2);
		return $data;
	}
	
	function getBrandPref($startDate=null,$endDate=null){
	
		$this->open(0);
		$rs = $this->fetch("SELECT  sum(Brand1_ID)  Brand1_ID,  sum(Brand2_ID) Brand2_ID, sum(Brand3_ID) Brand3_ID FROM ".ReportDB.".rp_overall_brand_preference ORDER BY age ASC LIMIT 3");
		$this->close();
		$data = json_encode($rs);
		return $data;
	
	}
	
	function pageViewDistribution($startDate=null,$endDate=null){
		require "copytext_dashboard.php";
		$pvd = array();
		
		if($startDate!=null && $endDate!=null)$date = " date_d >= '{$startDate}' AND date_d <= '{$endDate}' ";
		else $date = " date_d >= DATE_SUB(CURRENT_DATE, INTERVAL 6 DAY) ";
		
		
		$this->open(0);
		$tgl = $this->fetch("
		SELECT date_d 
		FROM ".ReportDB.".rp_overall_pageview_daily 
		WHERE {$date} 
		AND action_id = 7 
		AND title not in ('merchandiseList','home','merchandiseDetail') 
		GROUP BY date_d ASC",1);
		
		//var_dump($tgl);
		$title = $this->fetch("
		SELECT title 
		FROM ".ReportDB.".rp_overall_pageview_daily 
		WHERE {$date}
		AND action_id = 7 
		AND title not in ('merchandiseList','home','merchandiseDetail') 
		GROUP BY title ASC",1);
		
		for ($i=0;$i<sizeof($title);$i++){
			$pvdItem = array();
		
			if($page_view[$title[$i]["title"]]) $pageName = $page_view[$title[$i]["title"]];
			else $pageName = $title[$i]["title"];
			
			$page = $title[$i]["title"];
			$rs = $this->fetch("
			SELECT date_d, SUM(pageview_count) AS pageview 
			FROM ".ReportDB.".rp_overall_pageview_daily 
			WHERE {$date} 
			AND action_id = 7 
			AND title='".$page."' 
			GROUP BY date_d ASC",1);
			$k = 0;
			for ($j=0;$j<sizeof($tgl);$j++){
				
				if ($tgl[$j]["date_d"] == $rs[$k]["date_d"]){
					$listData = array(
							'num'=> $rs[$k]["pageview"],
							'datee' => $rs[$k]["date_d"]
					);
					$k++;
				}else{
					$listData = array(
							'num'=> 0,
							'datee' => 0
					);
				}
				
				
				array_push($pvdItem, $listData);
			}
			
			$listTitle = array('pageName'=>$pageName, 'page' => $page, 'data' => $pvdItem);
			array_push($pvd, $listTitle);
			$pvdItem = null;
		}
		
		//var_dump($pvd);
		$this->close();
		$tgl = json_encode($tgl);
		$data = json_encode($pvd);
		$arrData = array('tgl' => $tgl ,'data' =>$data);
		return $arrData;
	
	}
	
	
	function averageTimeonActDistribution($startDate=null,$endDate=null){
		require "copytext_dashboard.php";
		if($startDate!=null && $endDate!=null){
		$date = " date_d >= '{$startDate}' AND date_d <= '{$endDate}' ";
		$select =" 
		date_d, activity_id, activity_name, ROUND(AVG(avg_total)) avg_total, ROUND(AVG(time_total)) time_total 
		FROM ".ReportDB.".rp_activity_dist_daily  ";
		$group = " GROUP BY activity_id, activity_name";
		}
		else {
		$date = " date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ";
		$select =" date_d, activity_id, activity_name, avg_total, time_total FROM ".ReportDB.".rp_activity_dist_daily  ";
		$group = "";
		}
		$sql= "
		SELECT {$select}
		WHERE
		activity_name in ('login','race','customize_car','purchase_parts','minigame1','minigame2','minigame3','minigame4','page:find_player','page:garage','page:getpoints','page:home','page:merchandiseList','page:notification','page:paralax','page:race','page:top_user','garage')
		AND
		{$date}
		{$group}
		";
		$this->open(0);
		
		$rs = $this->fetch($sql,1);
		//sample
		//$rs = $this->fetch("SELECT date_d, activity_id, activity_name, avg_total, time_total FROM ".ReportDB.".rp_activity_dist_daily WHERE date_d = '2012-03-30'",1);
		$this->close();
		$n=0;
		// print_r($activity_distribution);exit;
		foreach($rs as $val){
		if($activity_distribution[$val['activity_name']]) $name = $activity_distribution[$val['activity_name']];
		else $name = $val['activity_name'];
		
		$rs[$n]['activity_name'] = $name;
		$n++;
		}
				// print_r($sql);exit;
		$data = json_encode($rs);
		return $data;
	}
	
	
	function getRacingModifMiniGame($startDate=null,$endDate=null){
		
		
		if($startDate!=null && $endDate!=null){
		$date = " date_d >= '{$startDate}' AND date_d <= '{$endDate}' ";
		$select =" date_d, ROUND(AVG(race_played_num)) AS race,  ROUND(AVG(car_modif_num)) AS car,  ROUND(AVG(mini_game_played_num)) AS mini ";
		}
		else {
		$date = " date_d >= DATE_SUB(CURRENT_DATE, INTERVAL 2 DAY) ";
		$select =" date_d, race_played_num AS race, car_modif_num AS car, mini_game_played_num AS mini ";

		}
	
		
		$sql ="
		SELECT {$select}
		FROM ".ReportDB.".rp_overall_daily 
		WHERE  {$date}
		ORDER BY date_d DESC";
		
		$this->open(0);
		$rs = $this->fetch($sql,1);
		//sample
		//$rs = $this->fetch("SELECT date_d, race_played_num AS race, car_modif_num AS car, mini_game_played_num AS mini FROM ".ReportDB.".rp_overall_daily WHERE date_d = '2012-03-30' OR date_d = '2012-03-29'",1);
		$this->close();
		
		//Races Played
		$race1 = intval($rs[1]["race"]);
		$race2 = intval($rs[0]["race"]);
		if ($race1 != 0 || $race1 != null){
			$raceArrow = round((($race2-$race1)/$race1)*100);
			$racePercentage = abs($raceArrow);
		}else{
			$raceArrow = 100;
			$racePercentage = 100;
		}
	$arrData['race'] = array('count'=>$rs[0]["race"],'raceArrow'=>$raceArrow,'racePercentage'=>$racePercentage);
		
		$car1 = intval($rs[1]["car"]);
		$car2 = intval($rs[0]["car"]);
		if ($car1 != 0 || $car1 != null){
			$carArrow = round((($car2-$car1)/$car1)*100);
			$carPercentage = abs($carArrow);
		}else{
			$carArrow = 100;
			$carPercentage = 100;
		}
	$arrData['car'] = array('count'=>$rs[0]["car"],'carArrow'=>$carArrow,'carPercentage'=>$carPercentage);	
		
		$mini1 = intval($rs[1]["mini"]);
		$mini2 = intval($rs[0]["mini"]);
		if ($mini1 != 0 || $mini1 != null){
			$miniArrow = round((($mini2-$mini1)/$mini1)*100);
			$miniPercentage = abs($miniArrow);
		}else{
			$miniArrow = 100;
			$miniPercentage = 100;
		}
		
	$arrData['minigame'] = array('count'=>$rs[0]["mini"],'carArrow'=>$miniArrow,'carPercentage'=>$miniPercentage);		
			// print_r($sql);exit;

	return $arrData;
	
	}
	
	
	function racingLevelOnTime($startDate=null,$endDate=null){
		
		if($startDate!=null && $endDate!=null)	$date = " WHERE date_d >= '{$startDate}' AND date_d <= '{$endDate}' ";
		else $date = "";
	
		$sql ="
		SELECT LEVEL, SUM(total_time) total_time, COUNT(user_num), SUM(total_time)/(86400*COUNT(user_num)) total_days 
		FROM 
		(
		SELECT user_id, LEVEL, MAX(total_time) total_time, 1 AS user_num 
		FROM ".ReportDB.".rp_user_race_level_data 
		{$date}
		GROUP BY user_id, LEVEL 
		) AS A 
		GROUP BY LEVEL";
		$this->open(0);
		$rs = $this->fetch($sql,1);		
		$this->close();
		$data = json_encode($rs);
			// print_r($sql);exit;
		return $data;
	
	}
	
	function gamePlayAndTime($startDate=null,$endDate=null){
	
		if($startDate!=null && $endDate!=null)	{
		$date = " date_d >= '{$startDate}' AND date_d <= '{$endDate}' ";
		$select = "date_d, mini_game_id, mini_game_name, SUM(num) num, ROUND(AVG(total)) total,  ROUND(AVG(percentage)) percentage, ROUND(AVG(avg_time)) avg_time, MAX(jam) jam, MAX(menit) menit, MAX(detik) detik";
		$group = "GROUP BY mini_game_id";
		}
		else {
		$date = " date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)  ";
		$select = "date_d, mini_game_id, mini_game_name, num, total, percentage, avg_time, jam, menit, detik ";
		$group = "";
		}
	
	
		$sql ="
		SELECT {$select}
		FROM ".ReportDB.".rp_overall_minigame_daily 
		WHERE 
		{$date}
		{$group}
		ORDER BY mini_game_name ASC";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		$avg = array();
		// var_dump($avg);exit;
		$data = json_encode($rs);
			// print_r($sql);exit;
		return $data;
	}
	
	
	function getMerchandiseRedeem($startDate=null,$endDate=null){
	
		if($startDate!=null && $endDate!=null)	$date = " date_format(purchase_date,'%Y-%m-%d') >= '{$startDate}' AND date_format(purchase_date,'%Y-%m-%d') <= '{$endDate}' ";
		else 	$date = " date_format(purchase_date,'%Y-%m-%d') <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ";
	
	
	
		$this->open(0);
		
		$sql = "SELECT  date_format(purchase_date,'%Y-%m-%d') as pdate,merchandise_id,merch.item_name,count(merchandise_id) as redeem_count,redeem.variant as rvariant
				FROM ".RedRushDB.".rr_purchase_merchandise redeem
				LEFT JOIN ".RedRushDB.".rr_merchandise merch  ON merch.id = redeem.merchandise_id
				WHERE
				{$date}
				group by  merchandise_id";
		$rs = $this->fetch($sql,1);
		$this->close();
		
		foreach($rs as $val){
			$redeem_count+= $val['redeem_count'];
		}
		
		
		$data = json_encode($rs);
	// print_r($sql);exit;
	$arrData = array('redeem_count'=>$redeem_count,'data'=>$data);
	return $arrData;
	
	}
	
	
	function get20topPlayer($limit=20){

	
	$sql = "
		SELECT b.id,b.name,b.nickname,b.email,b.last_name,b.username,a.level,e.score
		FROM	".RedRushDB.".kana_member b 
		LEFT JOIN ".GameDB.".racing_level a ON b.id = a.user_id
		LEFT JOIN ".RedRushDB.".tbl_rank_user e ON e.user_id=b.id
		ORDER BY a.level DESC,e.score DESC limit {$limit} ";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();	
		// print_r($rs);exit;
		return $rs;
	
	}
	
	
	
	function weeklyReport($startDate=null,$endDate=null,$summary=false){
	

	$startproject = '2012-04-02';
	$n=1;
	$arrWeek= array();
	while($n <=12){
	$count = 1;
		while($count <= 7){
		$arrWeek[$startproject] = $n;
		$expl = explode('-',$startproject);
		$startproject = date('Y-m-d', mktime(0, 0, 0,$expl[1] , $expl[2]+1, $expl[0]));
		$count++;
		// echo $startproject;
		}
		// echo $n;
	$n++;
	}
	
	
	if($startDate!=null && $endDate!=null)	{
		if($summary==true)  $date = " date >= '{$startDate}' AND date <= '{$endDate}'";
		else   $date ="  date >= '{$startDate}' AND date <= '{$endDate}' ";
		$dateRegistrant = " AND date >= '{$startDate}' AND date <= '{$endDate}' ";
		$dateRegistrantDstSba = " AND survey_date >= '{$startDate}' AND survey_date <= '{$endDate}' ";
		
		$dateExists = "AND survey_date <= '{$endDate}'";
		$weekWeb = " 	 AND week >= {$arrWeek[$startDate]} AND week <= {$arrWeek[$endDate]}";	
		$arrWeekkExisting = $arrWeek[$endDate] - 1 ;
		$weekExisting = " 	 AND week <= {$arrWeekkExisting} ";	


	}else 	{
		if($summary==true)  $date = " date <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ";
		else  $date = " date >= DATE_SUB(CURRENT_DATE, INTERVAL 8 DAY) AND date <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ";
		if($summary==true) $dateRegistrant = " AND date <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ";
		else $dateRegistrant = " AND date >= DATE_SUB(CURRENT_DATE, INTERVAL 8 DAY) AND  date <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ";
		if($summary==true) $dateRegistrantDstSba = " AND survey_date <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ";
		else $dateRegistrantDstSba = " AND survey_date >= DATE_SUB(CURRENT_DATE, INTERVAL 8 DAY) AND survey_date <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ";
		
		$endDate =  date('Y-m-d',strtotime('-1 days'));
		$arrWeekEndDate = $arrWeek[$endDate] ;
		$weekWeb = " 	 AND week = {$arrWeekEndDate} ";		
	
		
		$dateExists = "AND survey_date <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)";
		$arrWeekkExisting = $arrWeek[$endDate] - 1 ;
		$weekExisting = " 	 AND week <= {$arrWeekkExisting} ";	
		
	}
	
	// $endDate = '2012-05-20';
	
	
	// print_r($arrWeek[$endDate]);
	
	// exit;
	
	$webSql = "
		SELECT count(email) as num , level 
		FROM (
		SELECT user_id,email , level 
		FROM ".ReportDB.".rp_daily_level_user_per_part lv 
		WHERE 
		{$date}
		 AND 
		(		 
		not exists (SELECT email FROM ".ReportDB.".rep_channel WHERE email=lv.email AND channel in ('DST','DSTC','SBA','SBAC','MM') {$dateExists} )
		OR exists (SELECT email FROM ".ReportDB.".tbl_new_user_mop WHERE email=lv.email {$weekWeb} AND types=3 )
		)
		GROUP BY user_id,level
		) as webWeekly 
		GROUP BY level
		";
	// print_r('<pre>');print_r($webSql);
	$sbaSql = "
		SELECT count(email) as num , level 
		FROM (
		SELECT user_id,email  , level 
		FROM ".ReportDB.".rp_daily_level_user_per_part lv 
		WHERE 
		{$date}
		AND  exists (SELECT email FROM ".ReportDB.".rep_channel WHERE email=lv.email AND channel in ('SBA','SBAC','MM') {$dateExists} ) 
		GROUP BY user_id,level
		) as sbaWeekly 
		GROUP BY level
		";
	
	$dstSql = "
		SELECT count(email) as num , level 
		FROM (
		SELECT user_id,email  , level 
		FROM ".ReportDB.".rp_daily_level_user_per_part lv 
		WHERE 
		{$date}
		AND  exists (SELECT email FROM ".ReportDB.".rep_channel WHERE email=lv.email AND channel in ('DST') {$dateExists} ) 
		GROUP BY user_id,level
		) as dstWeekly
		GROUP BY level
		";
		
	$existingSql = "
		SELECT count(email) as num , level 
		FROM (
		SELECT user_id,email  , level 
		FROM ".ReportDB.".rp_daily_level_user_per_part lv 
		WHERE 
		{$date}
		
		AND (
		exists (SELECT email FROM ".ReportDB.".rep_channel WHERE email=lv.email AND channel in ('DSTC') {$dateExists} )
		OR exists (SELECT email FROM ".ReportDB.".tbl_new_user_mop WHERE email=lv.email {$weekExisting} AND types=3 ))
		
		GROUP BY user_id,level
		) as extWeekly
		GROUP BY level
		";	
	
	// AND exists (SELECT email FROM ".ReportDB.".rep_channel WHERE email=lv.email AND channel in ('DST','DSTC','SBA','SBAC','MM') {$dateExists} )
		// AND exists (SELECT email FROM ".ReportDB.".tbl_new_user_mop WHERE email=lv.email {$weekExisting} AND types=3 )
	
	//register
	// print_r('<pre>');print_r($existingSql);
	$regWeb = " 
	SELECT sum(num) as num
	FROM ".ReportDB.".rp_daily_registrant
	WHERE mobile_type = 1 
	{$dateRegistrant} ";
	
	$regSba = " 
		SELECT count(id) as num
		FROM ".ReportDB.".rep_channel
		WHERE 
		channel in ('SBA','SBAC','MM')
		{$dateRegistrantDstSba} 
		";
		
	$regDst = " 
		SELECT count(id) as num
		FROM ".ReportDB.".rep_channel
		WHERE 
		channel in ('DST','DSTC')
		{$dateRegistrantDstSba} 
		";
		
		$this->open(0);
		$result['SBA']= $this->fetch($sbaSql,1);
		$result['DST']= $this->fetch($dstSql,1);
		$result['WEBSITE'] = $this->fetch($webSql,1);
		$result['EXISTING'] = $this->fetch($existingSql,1);
		$registrant['WEBSITE'] = $this->fetch($regWeb);
		$registrant['SBA'] = $this->fetch($regSba);
		$registrant['DST'] = $this->fetch($regDst);
		$this->close();
	
	
	// print_r($registrant);
	
	//login
	foreach($result as $key => $val){
		foreach($val as $keyVal => $num){
			
			$result2[$key]['registrant'] = $registrant[$key]['num'];
			$result2[$key]['login'] += $num['num'];
			$result2[$key]['level'.$num['level']] = $num['num'];
			
		}
	}
	
	

	
	// print_r('<pre>');print_r($result2);exit;
	return $result2;
	
	}
	
	//---s:uda nggak di pake--------
	function getVisits7day(){		
		$yesterday = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
		$sevenDaysago = mktime(0,0,0,date("m"),date("d")-8,date("Y"));
		$gvd = new gapi(ga_email,ga_password);
		$gvd->requestReportData(ga_profile_id,array('date'),array('visits'), null,null,
				date("Y-m-d", $sevenDaysago), // Start Date
				date("Y-m-d", $yesterday), // End Date
				1,  // Start Index
				7 // Max results
		);
		$gvdResult  = array('date'=>$gvd->getDate(),'visits'=>$gvd->getVisits());
		
		$data = json_encode($gvdResult);
		//var_dump($gvdResult);exit();
		return $data;
	}
	//--- e:uda nggak di pake --------
	
	function getConfiguration(){
		//$this->open();
		$rs = $this->fetch("SELECT * FROM gm_dashboard LIMIT 20",1);
		///$this->close();
		return $rs;
	}
	function getPath($uri){
		$str = explode(".",trim($uri));
		$f = "";
		for($i=0;$i<sizeof($str);$i++){
			$f.=$str[$i];
			if($i<sizeof($str)-1){
			$f.="/";
			}
		}
		if(strlen($f)>5){
			$f.=".php";
			$className = $str[sizeof($str)-1];
		}
		$rs['file'] = $f;
		$rs['className'] = $className;
		return $rs;
	}
	function load(){
		global $APP_PATH,$ENGINE_PATH;
		$this->open(0);
		$items = $this->getConfiguration();
		$this->close();
		$plugins = array();
		for($i=0;$i<sizeof($items);$i++){
			$item = $this->getPath($items[$i]['class']);
			if(file_exists("../../".$item['file'])){
				
				include_once "../../".$item['file'];
				$obj = new $item['className']($this->Request);
				$plugins[$i]['name'] = $items[$i]['name'];
				$plugins[$i]['html'] = $obj->Dashboard();
				$plugins[$i]['slot'] = $items[$i]['slot'];
				
			}else{
				
				//print "class not found-->../".$item['file'];
			}
		}
		$this->View->assign("plugins",$plugins);
		return $this->View->toString("common/admin/dashboard_panel.html");
	}
	function addItem($name,$className,$invoker,$slot,$status){
		return $this->query("INSERT INTO gm_dashboard(name,class,invoker,slot,status) 
					 VALUES('".$name."','".$className."','".$invoker."','".$slot."','".$status."')");
	}
	function removeItem($id){
		return $this->query("DELETE FROM gm_dashboard WHERE id='".$id."'");
	}
	function editItem($id,$name,$className,$invoker,$status){
		return $this->query("UPDATE gm_dashboard 
							SET name='".$name."',class='".$className."',
							invoker='".$invoker."',status='".$status."' 
							WHERE id='".$id."'");
	}
	
	function getChartimage($type=null,$week=1){
	if($type==null) return false;
		$sql ="
		SELECT week,type,img 
		FROM ".ReportDB.".tbl_chart_uploader
		WHERE 
		type IN ({$type}) AND week={$week}
		";
		$this->open(0);		
		$chart= $this->fetch($sql,1);
		$this->close();
	return $chart;
	}
}
?>