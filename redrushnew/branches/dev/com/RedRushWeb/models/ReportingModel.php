<?php
	/* @author : Babar
3/5/2012 */
	class ReportingModel extends SQLData{
		function __construct(){ parent::SQLData();	}
		
		function insertTemp($email,$week){
			$q = "INSERT IGNORE INTO marlboro_redrush_report_2012.tbl_temp_bb_non_dstsba (email,week) 
					VALUES ('".$email."','".$week."')";
			$this->open(0);
			$input = $this->query($q);
			$this->close();
			return $input;
		}
		
		function getWeekList(){
			$q = "SELECT week FROM marlboro_redrush_report_2012.tbl_temp_bb_non_dstsba GROUP BY week";
			$this->open(0);
			$r = $this->fetch($q,1);
			$this->close();
			return $r;
		}
		
		function QRtoEmail($code=null){
			if($code==null){return false;}
			$q = "SELECT qr.code AS QRCode, km.email AS Email
							FROM marlboro_redrush_2012.tbl_qr_code_user qr
							LEFT JOIN marlboro_redrush_2012.kana_member km ON km.id = qr.user_id
							WHERE qr.code = '".$code."'
							GROUP BY qr.user_id";
			$this->open(0);
			$r = $this->fetch($q,1);
			$this->close();
			return $r;
		}
		
		function generate($week=null){
			if($week==null){return false;}
			// date mktime
			$start = '2012-04-08';
			$date = explode('-',$start);
			$dateEnd = date("Y-m-d",mktime(0,0,0,$date[1],$date[2]+($week*7),$date[0]));
			$q = "SELECT bbraw.email as email,partlevel.level as level1,partlevel2.level as level2 ,partlevel3.level as level3 , nu.email as newEmail, old.email as OldEmail  FROM marlboro_redrush_report_2012.tbl_temp_bb_non_dstsba as bbraw
					LEFT JOIN 
					(SELECT bbnew.email as email FROM marlboro_redrush_report_2012.tbl_temp_bb_non_dstsba as bbnew WHERE 
					exists 
					(select  email FROM marlboro_redrush_report_2012.tbl_new_user_mop km where km.email=bbnew.email 
					AND week = {$week}
					)
					AND week={$week}
					group by bbnew.email) as nu ON nu.email= bbraw.email
					LEFT JOIN 
					(SELECT bb.email as email FROM marlboro_redrush_report_2012.tbl_temp_bb_non_dstsba as bb WHERE 
					exists 
					(select email FROM marlboro_redrush_2012.kana_member  km where km.email=bb.email 
					AND
					Not exists (SELECT mop.email from marlboro_redrush_report_2012.tbl_new_user_mop mop WHERE mop.email=km.email AND 
					mop.week={$week}
					)
					AND exists (select level.user_id from  marlboro_redrush_game_2012.racing_level level WHERE km.id=level.user_id)
					 group by email
					  ) AND 
					week={$week}
					group by bb.email) as old ON old.email= bbraw.email 
					LEFT JOIN (
					select email, 1 as level from (
					SELECT count(parts_id) as tpart , user_id, km.email as email FROM marlboro_redrush_game_2012.racing_user_inventory inven
					LEFT JOIN marlboro_redrush_2012.kana_member km ON km.id= inven.user_id
					where `purchase_date` <='{$dateEnd}' group by user_id ) as part
					WHERE part.tpart < 2) as partlevel
					ON partlevel.email= bbraw.email
					LEFT JOIN (
					select email, 2 as level from (
					SELECT count(parts_id) as tpart , user_id, km.email as email FROM marlboro_redrush_game_2012.racing_user_inventory inven
					LEFT JOIN marlboro_redrush_2012.kana_member km ON km.id= inven.user_id
					where `purchase_date` <='{$dateEnd}' group by user_id ) as part
					WHERE part.tpart >= 2 and part.tpart <= 4) as partlevel2
					ON partlevel2.email= bbraw.email
					LEFT JOIN (
					select email, 3 as level from (
					SELECT count(parts_id) as tpart , user_id, km.email as email FROM marlboro_redrush_game_2012.racing_user_inventory inven
					LEFT JOIN marlboro_redrush_2012.kana_member km ON km.id= inven.user_id
					where `purchase_date` <='{$dateEnd}' group by user_id ) as part
					WHERE part.tpart >= 5 and  part.tpart <= 7) as partlevel3
					ON partlevel3.email= bbraw.email

					WHERE 
					bbraw.week ={$week}
					Group by bbraw.email
					";
			// echo $q;exit;
			$this->open(0);
			$r = $this->fetch($q,1);
			$this->close();
			return $r;
		}
		
		function listChartData($week=1){
			
			$sql ="
			SELECT * FROM marlboro_redrush_report_2012.tbl_chart_uploader
			WHERE n_status = 1 AND week={$week}
			";
			// print_r($sql);exit;
			$this->open(0);
			$input = $this->fetch($sql,1);
			$this->close();
			if($input) return $input;
			else return false;
		
		}
		
		function insertChartData($data){
			
			$sql ="
			REPLACE INTO marlboro_redrush_report_2012.tbl_chart_uploader
			(img,week,type,n_status)
			VALUES
			('{$data['img']}',{$data['week']},'{$data['type']}',1)
			";
			// print_r($sql);exit;
			$this->open(0);
			$input = $this->query($sql);
			$this->close();
			if($input) return true;
			else return false;
		
		}
		
		
				
	}
?>