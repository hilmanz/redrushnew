<?php
global $ENGINE_PATH;
include_once "Model_performance.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
class Performance extends SQLData{
	function Performance($req){
		parent::SQLData();
		$this->content = "";
		$this->Request = $req;
		$this->View = new BasicView();
		$this->Model = new Model_performance($req,$this);
		
	}
	function main($user_id){
		
			return $this->view_list_Performance($user_id);			
	}
	function admin(){
		if($this->Request->getRequest('detail')=="1"){
			return $this->BAPerformance();
		}else{
			return $this->OverallStats();
		}
		//return $this->BAPerformance();
	}
	/**
	 * 
	 * jumlah target KPI per bulan
	 * @param unknown_type $bln bulan
	 */
	function get_target_kpi($bln){
		$target = array(0,0,0,0,86,106,110,113,119,126,133,127,129);
		
		return $target[$bln];
	}
	
	/**
	 * 
	 * jumlah target KPI per bulan
	 * @param unknown_type $bln bulan
	 */
	function get_city_kpi($city,$bln){
			
		$target['JKT'] = array(0,0,0,0,1381,1699,1763,1805,1912,2018,2124,2039,2060);
		$target['BDG'] = array(0,0,0,0,690,850,881,903,956,1009,1062,1019,1030);
		$target['JOG'] = array(0,0,0,0,431,531,551,564,597,631,664,637,644);
		$target['MDN'] = array(0,0,0,0,690,850,881,903,956,1009,1062,1019,1030);
		$target['SBY'] = array(0,0,0,0,690,850,881,903,956,1009,1062,1019,1030);
		return $target[$city][$bln];
	}
	/**
	 * 
	 * KPI SUmmary
	 */
	function getKPISummary(){
		$curr_month = intval(date("m"));
		$curr_target = $this->get_target_kpi($curr_month);
		$last_target = $this->get_target_kpi($curr_month-1);
		
		$sql = "SELECT COUNT(*) as tt FROM (SELECT sba_id, COUNT(sba_id) as total FROM (SELECT sba_id,device_id,kota,date,
			STR_TO_DATE(b.date,'%d/%m/%Y') as tgl
			FROM `lookup_sba_device` a
			INNER JOIN
			tbl_ba_registrations b
			ON a.device_id = b.device) c
			WHERE MONTH(tgl) = '".($curr_month-1)."'
			GROUP BY c.sba_id) d
			WHERE d.total >=".intval($last_target);
		
		$sql2 = "SELECT COUNT(*) as tt FROM (SELECT sba_id, COUNT(sba_id) as total FROM (SELECT sba_id,device_id,kota,date,
			STR_TO_DATE(b.date,'%d/%m/%Y') as tgl
			FROM `lookup_sba_device` a
			INNER JOIN
			tbl_ba_registrations b
			ON a.device_id = b.device) c
			WHERE MONTH(tgl) = '".$curr_month."'
			GROUP BY c.sba_id) d
			WHERE d.total >=".intval($curr_target);
		
		
		$sql3 = "SELECT COUNT(id) as total FROM social_member WHERE type=1";
		
		$rs2 = $this->fetch($sql2);
		$rs3 = $this->fetch($sql3);
		if($last_target>0){
			$rs1 = $this->fetch($sql);
			$last_under = $rs3['total']-$rs1['tt'];
		}else{
			$rs1['tt'] = 0;
			$last_under = 0;
		}
		return array($rs1['tt'],$rs2['tt'],$last_under,$rs3['total']-$rs2['tt']);
	}
	function OverallStats(){
		$user = $this->BaList();
		
		$top = $this->BAListDetailed();
		
		$this->View->assign("user",$user);
		if($this->Request->getParam('city')){
			$top_city = $this->TopCity();
			$this->View->assign("top_city",$top_city);
		}else{
			$this->View->assign("top",$top);
		}
		//KPI SUMMARY
		$kpi = $this->getKPISummary();
		$this->View->assign("kpi",$kpi);
		//CHARTS
		$ba = $_POST['ba'];
		$all_data = array();
		$dates = array();
		if(is_array($ba)){
			//data di filter berdasarkan BA
			$n=0;
			foreach($ba as $user_id){
				$this->View->assign("user".$n,$user_id);
				$rs = $this->getDailyRegistration($user_id,$this->Request->getPost('sd'),$this->Request->getPost('ed'));
				$info = $this->getUserDetail($user_id);
				$data = array();
				if(sizeof($rs)>0){
					foreach($rs as $d){
						
						$dates[strtotime($d['tgl'])] = 1;
						$nd = strtotime($d['tgl']);
						settype($nd,'string');
						array_push($data,array($nd,intval($d['amount'])));
					}
					array_push($all_data,array("name"=>$info['name'],"data"=>$data));
				}
				$n++;
			}
		
		
			if(sizeof($all_data)>0){
				//urutkan datanya
				$the_dates = array();
				foreach($dates as $nn=>$vv){
					array_push($the_dates,$nn);
				}
				$the_dates = array_reverse(array_reverse($the_dates));
				foreach($all_data as $mm=>$val){
					$buff = array();
					for($i=0;$i<sizeof($val['data']);$i++){
						$buff[$val['data'][$i][0]] = 1;
						if($i==0){
							$start = $val['data'][$i][0];
						}else if($i==sizeof($val)-1){
							$end = $val['data'][$i][0];
						}else{
							continue;	
						}
					}
					foreach($the_dates as $td){
						if($buff[$td]!=1){
							array_push($all_data[$mm]['data'],array($td,0));
						}						
					}
					//array_$all_data[$mm]['data']
				}
			}
			$this->View->assign("filtered","1");
		}else{
			//data harian keseluruhan
			$rs = $this->getDailyRegistrations($this->Request->getPost('sd'),$this->Request->getPost('ed'));
			$data = array();
			if(sizeof($rs)>0){
				foreach($rs as $d){
					$dates[strtotime($d['tgl'])] = 1;
					$nd = strtotime($d['tgl']);
					settype($nd,'string');
					array_push($data,array($nd,intval($d['total'])));
				}
				array_push($all_data,array("name"=>"Overall","data"=>$data));
			}
			$this->View->assign("filtered","0");
		}
		//var_dump($all_data);
		$this->View->assign("data",json_encode($all_data));
		
		//profile
		$this->View->assign("profile_data",json_encode($this->getProfileStats()));
		
		//age
		$this->View->assign("age_data",json_encode($this->getAgeSummary()));
		
		//city
		$this->View->assign("city_data",json_encode($this->getCityStats()));
		
		//brand
		$this->View->assign("brand_data",json_encode($this->getBrandSummary()));
		
		return $this->View->toString("Performance/admin/overall_stats.html");
	}
	function BAPerformance(){
		$caps = 1050;
		$user = $this->BAList();
		$this->View->assign("user",$user);
		
		$user_id = intval($this->Request->getRequest('id'));
		if($user_id>0){
			$rs = $this->getDailyRegistration($user_id);
			$data = array();
			if(sizeof($rs)>0){
				foreach($rs as $d){
					$nd = strtotime($d['tgl']);
					settype($nd,'string');
					array_push($data,array($nd,intval($d['amount'])));
				}
			}
			
			$info = $this->getUserDetail($user_id);
			
			//$summary = $this->getSummary($user_id);
			if($rs){
				$total_regs = $this->getTotalRegistrations($user_id);
				//$this->View->assign("summary",$summary);
				$progress = round($total_regs/$caps*100);
				$this->View->assign("overall_rank",$this->getRank($user_id));
				$this->View->assign("city_rank",$this->getCityRank($user_id));
				$this->View->assign("IP_rank",$this->getInteractionPointRank($user_id));
			
			}
			$this->View->assign("progress",$progress);
			$this->View->assign("total_regs",$total_regs);
			
			//profile pie chart
			$this->View->assign("profile_data",json_encode($this->getProfileStatsByUser($user_id)));
			
			//brand
			$this->View->assign("brand_data",json_encode($this->getUserBrandSummary($user_id)));
			
			
			//age
			$this->View->assign("age_data",json_encode($this->getUserAgeSummary($user_id)));
			

			
			$this->View->assign("info",$info);
			$this->View->assign("rs",$rs);
			$this->View->assign("data",json_encode($data));
		}
		return $this->View->toString("Performance/admin/ba_detail.html");
	}
	function getUserDetail($user_id){
		$sql = "SELECT * FROM social_member a INNER JOIN dm_member b
				ON a.register_id = b.id WHERE a.id=".$user_id." LIMIT 1";
		$rs = $this->fetch($sql);
		
		return $rs;
	}
	function getRank($user_id){
		$rs = $this->getAllBARegs();
		foreach($rs as $d){
			
			if($d['id']==$user_id){
				
				return $d['rank'];
			}
		}
	}
	function getInteractionPointRank($user_id){
		$sql = "SELECT user_id,name,SUM(weight) as points
				FROM 
				(SELECT user_id,weight FROM `tbl_interaction_free` a
				UNION ALL
				SELECT user_id,weight FROM `tbl_interaction_daily` b) c 
				INNER JOIN
				social_member d
				ON c.user_id = d.id
				GROUP BY user_id
				ORDER BY points DESC";
		$rs = $this->fetch($sql,1);
		$i=1;
		foreach($rs as $d){
			if($d['user_id']==$user_id){
				return $i;
			}
			$i++;
		}
		return 0;
	}
	function getCityRank($user_id){
		$sql = "SELECT a.*,SUM(amount) as n_regs FROM social_member a INNER JOIN dm_member b
				ON a.register_id = b.id 
				INNER JOIN tbl_daily_registration c
				ON a.id = c.user_id
				WHERE a.type=1 AND a.id IN 
					(SELECT sba_id FROM lookup_sba_device 
					 WHERE kota IN 
					 	(SELECT kota FROM `lookup_sba_device` WHERE sba_id = ".intval($user_id).")
					)
				GROUP BY c.user_id
				ORDER BY n_regs DESC";
		$rs = $this->fetch($sql,1);
		$rank = 1;
		
		foreach($rs as $d){
			if($d['id']==$user_id){
				return $rank;
			}
			$rank++;
		}
	}
	function getSummary($user_id){
		$sql = "SELECT * FROM tbl_ba_performance WHERE user_id=".$user_id." LIMIT 1";
		return $this->fetch($sql);
	}
	function getDailyRegistration($user_id,$start_date=null,$end_date=null){
		$leader_ba_list=$this->fetch("SELECT ba_id FROM leader_ba_lookup WHERE leader_id=$user_id;",1);
		
		if(count($leader_ba_list) > 0){
			$ba_list = '';
			foreach($leader_ba_list as $k => $v){
				$ba_list .= $v['ba_id'].',';
			}
			$ba_list .= 0;
			if($start_date!=NULL && $end_date!=NULL){
				/*
				$sql = "SELECT * FROM tbl_daily_registration WHERE user_id in ($ba_list) 
						AND tgl BETWEEN '".mysql_escape_string($start_date)."' 
						AND '".mysql_escape_string($end_date)."' 
						ORDER BY tgl LIMIT 100";
					*/	
				$sql = "SELECT id,user_id,tgl,SUM(amount) AS amount FROM tbl_daily_registration 
WHERE user_id IN ($ba_list) AND tgl BETWEEN '".mysql_escape_string($start_date)."' 
						AND '".mysql_escape_string($end_date)."' GROUP BY tgl LIMIT 100";
			}else{
				//$sql = "SELECT * FROM tbl_daily_registration WHERE user_id in ($ba_list) ORDER BY tgl LIMIT 100";
				$sql = "SELECT id,user_id,tgl,SUM(amount) AS amount FROM tbl_daily_registration 
WHERE user_id IN ($ba_list) GROUP BY tgl LIMIT 100";
			}
		}else{
			if($start_date!=NULL && $end_date!=NULL){
				$sql = "SELECT * FROM tbl_daily_registration WHERE user_id=".$user_id." 
						AND tgl BETWEEN '".mysql_escape_string($start_date)."' 
						AND '".mysql_escape_string($end_date)."' 
						ORDER BY tgl LIMIT 100";
			}else{
				$sql = "SELECT * FROM tbl_daily_registration WHERE user_id=".$user_id." ORDER BY tgl LIMIT 100";
			}
		}
		return $this->fetch($sql,1);
		
	}
	function getDailyRegistrations($start_date=null,$end_date=null){
		if($start_date!=NULL && $end_date!=NULL){
			$sql = "SELECT tgl,SUM(amount) as total FROM tbl_daily_registration 
					WHERE tgl BETWEEN '".mysql_escape_string($start_date)."' 
					AND '".mysql_escape_string($end_date)."' 
					GROUP BY tgl ORDER BY tgl LIMIT 100";
		}else{
			$sql = "SELECT tgl,SUM(amount) as total FROM tbl_daily_registration GROUP BY tgl ORDER BY tgl LIMIT 100";
		}
		return $this->fetch($sql,1);
	}
	function BAList(){
		$sql = "SELECT a.* FROM social_member a INNER JOIN dm_member b
				ON a.register_id = b.id WHERE a.type=1 ORDER BY b.nama";
		$rs = $this->fetch($sql,1);
		return $rs;
	}
	function getAllBARegs(){
		$caps = 1050; //manual dulu
		
		$sql = "SELECT a.*,SUM(amount) as n_regs FROM social_member a INNER JOIN dm_member b
				ON a.register_id = b.id 
				INNER JOIN tbl_daily_registration c
				ON a.id = c.user_id
				WHERE a.type=1
				GROUP BY c.user_id
				ORDER BY n_regs DESC";
	
		$rs = $this->fetch($sql,1);
		$n_rank=1;
		foreach($rs as $ind=>$val){
			$rs[$ind]['rank'] = $n_rank;
			$rs[$ind]['progress'] = round(($rs[$ind]['n_regs']/$caps)*100); 
			$n_rank++;
		}
		return $rs;
	}
	function BAListDetailed($start=0,$total=10){
		$caps = 1050; //manual dulu
		$start = intval($start);
		$total = intval($total);
		$sql = "SELECT a.*,SUM(amount) as n_regs FROM social_member a INNER JOIN dm_member b
				ON a.register_id = b.id 
				INNER JOIN tbl_daily_registration c
				ON a.id = c.user_id
				WHERE a.type=1
				GROUP BY c.user_id
				ORDER BY n_regs DESC
				LIMIT ".$start.",".$total;
		
		//print $sql;
		$rs = $this->fetch($sql,1);
		//print mysql_error();
		foreach($rs as $ind=>$val){
			//$rs[$ind]['n_regs'] = $this->getTotalRegistrations($rs[$ind]['id']);
			$rs[$ind]['progress'] = round(($rs[$ind]['n_regs']/$caps)*100); 
		}
		return $rs;
	}
	function Summary(){
		$summary = $this->Model->getSummary();
		$this->View->assign("list",$summary);
		return $this->View->toString("Performance/admin/summary.html");
	}
	/**
	 * 
	 * Total registrasi yang dihasilkan oleh BA ini.
	 * @param unknown_type $user_id
	 */
	function getTotalRegistrations($user_id){
		$sql = "SELECT SUM(amount) as total FROM tbl_daily_registration WHERE user_id=".$user_id." LIMIT 1";
		
		$rs = $this->fetch($sql);
		return $rs['total'];
	}
	function view_list_Performance($user_id=1){
		$caps = 1050;
		$this->open(0);
		
		
		
		$user_id = intval($user_id);
		if($user_id>0){
			$rs = $this->getDailyRegistration($user_id);
			$data = array();
			if(sizeof($rs)>0){
				foreach($rs as $d){
					$nd = strtotime($d['tgl']);
					settype($nd,'string');
					array_push($data,array($nd,intval($d['amount'])));
				}
			}
			
			$info = $this->getUserDetail($user_id);
			//$summary = $this->getSummary($user_id);
			if($rs){
				$total_regs = $this->getTotalRegistrations($user_id);
				//$this->View->assign("summary",$summary);
				$progress = round($total_regs/$caps*100);
				$this->View->assign("overall_rank",$this->getRank($user_id));
				$this->View->assign("city_rank",$this->getCityRank($user_id));
				$this->View->assign("IP_rank",$this->getInteractionPointRank($user_id));
			
			}
			$this->View->assign("progress",$progress);
			$this->View->assign("total_regs",$total_regs);
			
			//profile pie chart
			$this->View->assign("profile_data",json_encode($this->getProfileStatsByUser($user_id)));
			
			$this->View->assign("info",$info);
			$this->View->assign("rs",$rs);
			$this->View->assign("data",json_encode($data));
		}
		$this->close();
		return $this->View->toString("Performance/ba_performance.html");
	}
	function getProfileStats(){
		$sql = "SELECT profile,count(*) as total FROM `tbl_ba_registrations` GROUP BY profile";
		$rs = $this->fetch($sql,1);
		
		$sql = "SELECT COUNT(*) as total FROM tbl_ba_registrations";
		$rows = $this->fetch($sql);
		$data = array();
		foreach($rs as $d=>$v){
			$percent = round(($rs[$d]['total'] / $rows['total'])*100);
			array_push($data,array($rs[$d]['profile'],$percent));
		}
		return ($data);
	}
	function getProfileStatsByUser($user_id){
		$sql = "SELECT a.user_id, c.profile,COUNT(*) as total 
				FROM `tbl_daily_registration` a
				INNER JOIN lookup_sba_device b
				ON a.user_id = b.sba_id
				INNER JOIN tbl_ba_registrations c
				ON b.device_id = c.device
				WHERE a.user_id=".intval($user_id)."
				GROUP BY c.profile";
		
		$rs = $this->fetch($sql,1);
		
		$sql = "SELECT COUNT(*) as total 
				FROM `tbl_daily_registration` a
				INNER JOIN lookup_sba_device b
				ON a.user_id = b.sba_id
				INNER JOIN tbl_ba_registrations c
				ON b.device_id = c.device
				WHERE a.user_id=".intval($user_id);
		$rows = $this->fetch($sql);
		$data = array();
		if(is_array($rs)){
			foreach($rs as $d=>$v){
				$percent = round(($rs[$d]['total'] / $rows['total'])*100);
				array_push($data,array($rs[$d]['profile'],$percent));
			}
		}
		return ($data);
	}
	function getCityStats(){
		$sql = "SELECT kota,COUNT(*) as total
				FROM `tbl_ba_registrations` a
				INNER JOIN lookup_sba_device b
				ON a.device = b.device_id
				GROUP BY kota ORDER BY total DESC";
		$rs = $this->fetch($sql,1);
		
		$sql = "SELECT COUNT(*) as total FROM tbl_ba_registrations";
		$rows = $this->fetch($sql);
		$data = array();
		foreach($rs as $d=>$v){
			$percent = round(($rs[$d]['total'] / $rows['total'])*100);
			array_push($data,array($rs[$d]['kota'],$percent));
		}
		return ($data);
	}
	function TopCity($total=9999){
		$month = intval(date("m"));
		//$month = 4;
		/*$sql = "SELECT kota,COUNT(*) as total
				FROM `tbl_ba_registrations` a
				INNER JOIN lookup_sba_device b
				ON a.device = b.device_id
				GROUP BY kota ORDER BY total DESC LIMIT $total";*/
		$sql = "SELECT kota,bulan,COUNT(kota) as total FROM (SELECT sba_id,device_id,kota,date,
				MONTH(STR_TO_DATE(b.date,'%d/%m/%Y')) as bulan
				FROM `lookup_sba_device` a
				INNER JOIN
				tbl_ba_registrations b
				ON a.device_id = b.device) c
				WHERE bulan=".$month."
				GROUP BY kota
				";
		$rs = $this->fetch($sql,1);
		
		$sql = "SELECT COUNT(*) as total FROM tbl_ba_registrations";
		$rows = $this->fetch($sql);
		$data = array();
		foreach($rs as $d=>$v){
			//$rs[$d]['percent'] = round(($rs[$d]['total'] / $rows['total'])*100);
			$rs[$d]['percent'] = $rs[$d]['total'];
			$rs[$d]['total'] = $this->get_city_kpi($rs[$d]['kota'],$month);
			$rs[$d]['percent_txt'] = round($rs[$d]['percent']/$rs[$d]['total']*100);
			
			if( strval($rs[$d]['kota']) == "MDN"){
				$rs[$d]['nama_kota'] = "MEDAN";
			}elseif( strval($rs[$d]['kota']) == "JKT"){
				$rs[$d]['nama_kota'] = "JAKARTA";
			}elseif( strval($rs[$d]['kota']) == "SBY"){
				$rs[$d]['nama_kota'] = "SURABAYA";
			}elseif( strval($rs[$d]['kota']) == "JOG"){
				$rs[$d]['nama_kota'] = "JOGYAKARTA";
			}elseif( strval($rs[$d]['kota']) == "BDG"){
				$rs[$d]['nama_kota'] = "BANDUNG";
			} 
		}
		
		return $rs;
	}
	function getAgeSummary(){
		$sql = "SELECT age,COUNT(age) as amount FROM (SELECT DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( dob ) ) ,  '%Y' ) +0 AS age
				FROM  `tbl_ba_registrations`) aa
				GROUP BY aa.age";
		$rs = $this->fetch($sql,1);
		
		$sql = "SELECT COUNT(age) as total FROM (SELECT DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( dob ) ) ,  '%Y' ) +0 AS age
				FROM  `tbl_ba_registrations`) aa LIMIT 1";
		$rows = $this->fetch($sql);
		$data = array();
		$age_group = array('18-24'=>0,'25-29'=>0,'30+'=>0);
		
		foreach($rs as $nn=>$v){
			if($rs[$nn]['age']!=null){
				$rs[$nn]['age'] = intval($rs[$nn]['age']); 
				if($rs[$nn]['age']>=18&&$rs[$nn]['age']<=24){
					
					$age_group['18-24']+=intval($rs[$nn]['amount']);
				}else if($rs[$nn]['age']>=25&&$rs[$nn]['age']<=29){
					
					$age_group['25-29']+=intval($rs[$nn]['amount']);
				}else if($rs[$nn]['age']>=30){
					$age_group['30+']+=intval($rs[$nn]['amount']);
				}
				//$percent = round(($rs[$nn]['amount'] / $rows['total']) * 100);
				//array_push($data,array($rs[$nn]['age'],$percent));
			}
		}
		
		foreach($age_group as $nn=>$v){
			//print $age_group[$nn]."<br/>";
			$percent = round(($age_group[$nn] / $rows['total']) * 100);
			array_push($data,array($nn,$percent));
		}
		return $data;
	}
	function getUserAgeSummary($user_id){
		$sql = "SELECT age,COUNT(age) as amount FROM (SELECT DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( dob ) ) ,  '%Y' ) +0 AS age
				FROM  `tbl_ba_registrations` a
				INNER JOIN lookup_sba_device b
				ON a.device = b.device_id
				WHERE b.sba_id=".intval($user_id)."
				) aa
				GROUP BY aa.age";
		$rs = $this->fetch($sql,1);
		
		$sql = "SELECT COUNT(age) as total FROM (SELECT DATE_FORMAT( FROM_DAYS( TO_DAYS( NOW( ) ) - TO_DAYS( dob ) ) ,  '%Y' ) +0 AS age
				FROM  `tbl_ba_registrations` a
				INNER JOIN lookup_sba_device b
				ON a.device = b.device_id
				WHERE b.sba_id=".intval($user_id).") aa LIMIT 1";
		$rows = $this->fetch($sql);
		$data = array();
		$age_group = array('18-24'=>0,'25-29'=>0,'30+'=>0);
		
		foreach($rs as $nn=>$v){
			if($rs[$nn]['age']!=null){
				$rs[$nn]['age'] = intval($rs[$nn]['age']); 
				if($rs[$nn]['age']>=18&&$rs[$nn]['age']<=24){
					
					$age_group['18-24']+=intval($rs[$nn]['amount']);
				}else if($rs[$nn]['age']>=25&&$rs[$nn]['age']<=29){
					
					$age_group['25-29']+=intval($rs[$nn]['amount']);
				}else if($rs[$nn]['age']>=30){
					$age_group['30+']+=intval($rs[$nn]['amount']);
				}
				
			}
		}
		
		foreach($age_group as $nn=>$v){
			//print $age_group[$nn]."<br/>";
			$percent = round(($age_group[$nn] / $rows['total']) * 100);
			array_push($data,array($nn,$percent));
		}
		return $data;
	}
	function getBrandSummary(){
		$groups = array("Marlboro Black Menthol"=>"Marlboro",
						"Marlboro Lights"=>"Marlboro",
						"Marlboro Lights Menthol"=>"Marlboro",
						"Marlboro Menthol"=>"Marlboro",
						"Marlboro Red"=>"Marlboro",
						"Dunhill Lights"=>"Dunhill",
						"Dunhill Lights Menthol"=>"Dunhill",
						"Dunhill Menthol"=>"Dunhill",
						"Dunhill Reguler"=>"Dunhill",
						"Djarum Super"=>"SKM FF",
						"Gudang Garam International Filter"=>"SKM FF",
						"Gudang Garam Surya"=>"SKM FF",
						"Gudang Garam Surya Pro"=>"SKM FF",
						"Gudang Garam Surya Signature"=>"SKM FF",
						"Gudang Garam Surya Slim"=>"SKM FF",
						"Clas Mild Menthol"=>"LTLN",
						"Clas Mild Reguler"=>"LTLN",
						"LA Lights Menthol"=>"LTLN",
						"LA Lights Reguler"=>"LTLN",
						"X Mild"=>"LTLN"
						);
		$sql = "SELECT * FROM (SELECT brand,COUNT(brand) as total FROM `tbl_ba_registrations` GROUP BY brand) aa ORDER BY aa.total DESC";
		$rs = $this->fetch($sql,1);
		
		$sql = "SELECT COUNT(brand) as total 
				FROM `tbl_ba_registrations`";
		$rows = $this->fetch($sql);
		$data = array();
		$rsgroup = array();
		foreach($rs as $nn=>$v){
			//$percent = round(($rs[$nn]['total'] / $rows['total']) * 100);
			//array_push($data,array($rs[$nn]['brand'],$percent));
			$groupName = $groups[$rs[$nn]['brand']];
			if($groupName==NULL){
				$groupName = "Others";
			}
			if($rsgroup[$groupName]==NULL){
				$rsgroup[$groupName] = 0;
			}
			$rsgroup[$groupName]+=intval($rs[$nn]['total']);
		}
		foreach($rsgroup as $name=>$val){
			$percent = round(($val / $rows['total']) * 100);
			array_push($data,array($name,$percent));
		}
		return $data;
	}
	
	function getUserBrandSummary($user_id){
		$groups = array("Marlboro Black Menthol"=>"Marlboro",
						"Marlboro Lights"=>"Marlboro",
						"Marlboro Lights Menthol"=>"Marlboro",
						"Marlboro Menthol"=>"Marlboro",
						"Marlboro Red"=>"Marlboro",
						"Dunhill Lights"=>"Dunhill",
						"Dunhill Lights Menthol"=>"Dunhill",
						"Dunhill Menthol"=>"Dunhill",
						"Dunhill Reguler"=>"Dunhill",
						"Djarum Super"=>"SKM FF",
						"Gudang Garam International Filter"=>"SKM FF",
						"Gudang Garam Surya"=>"SKM FF",
						"Gudang Garam Surya Pro"=>"SKM FF",
						"Gudang Garam Surya Signature"=>"SKM FF",
						"Gudang Garam Surya Slim"=>"SKM FF",
						"Clas Mild Menthol"=>"LTLN",
						"Clas Mild Reguler"=>"LTLN",
						"LA Lights Menthol"=>"LTLN",
						"LA Lights Reguler"=>"LTLN",
						"X Mild"=>"LTLN"
						);
						
						
		$sql = "SELECT * FROM (SELECT b.sba_id,brand,COUNT(brand) as total 
				FROM `tbl_ba_registrations` a
				INNER JOIN lookup_sba_device b
				ON a.device = b.device_id
				WHERE b.sba_id=".intval($user_id)."
				GROUP BY brand) aa ORDER BY aa.total DESC";
		$rs = $this->fetch($sql,1);
		//var_dump($rs);
		$sql = "SELECT COUNT(brand) as total 
				FROM `tbl_ba_registrations` a INNER JOIN lookup_sba_device b
				ON a.device = b.device_id
				WHERE b.sba_id=".intval($user_id)."";
		$rows = $this->fetch($sql);
		$data = array();
		$rsgroup = array();
		/*
		foreach($rs as $nn=>$v){
			$percent = round(($rs[$nn]['total'] / $rows['total']) * 100);
			array_push($data,array($rs[$nn]['brand'],$percent));
		}
		*/
		foreach($rs as $nn=>$v){
			//$percent = round(($rs[$nn]['total'] / $rows['total']) * 100);
			//array_push($data,array($rs[$nn]['brand'],$percent));
			$groupName = $groups[$rs[$nn]['brand']];
			if($groupName==NULL){
				$groupName = "Others";
			}
			if($rsgroup[$groupName]==NULL){
				$rsgroup[$groupName] = 0;
			}
			$rsgroup[$groupName]+=intval($rs[$nn]['total']);
		}
		foreach($rsgroup as $name=>$val){
			$percent = round(($val / $rows['total']) * 100);
			array_push($data,array($name,$percent));
		}
		return $data;
	}
}

	

?>
