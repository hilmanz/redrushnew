<?php
class BAHelper extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
	}
	function getPerformance($user_id){
		global $APP_PATH;
		include_once $APP_PATH."BA/Performance.php";
		
		$caps = 1050;
		
		$performance = new Performance($this->Request);
		$this->open(0);
		
		$rs['registrants'] = $performance->getTotalRegistrations($user_id);
		$rs['progress'] = round(intval($rs['registrants'])/$caps*100);
		$ba_level =  array("newbie","amateur","JourneyMan","Pro","Master","Guru","WorldClass","Legendary","DemiGod","God-Like","GOD");
		
		//$sql = "SELECT * FROM sba.tbl_ba_performance WHERE user_id=".$user_id." LIMIT 1";
		
		//$rs = $this->fetch($sql);
		
		
		$rs['level'] = $ba_level[ceil(floor($rs['progress']/10))];
		$this->close();
		return $rs;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param $start
	 * @param $total
	 */
	function getTopPerformanceList($user_id,$start,$total=10){
		$caps = 1050;
		$user_id = intval($user_id);
		$start = intval($start);
		$total = intval($total);
		$ba_level =  array("newbie","amateur","JourneyMan","Pro","Master","Guru","WorldClass","Legendary","DemiGod","God-Like","GOD");
		/*
		$sql = "SELECT * FROM social_member a 
				INNER JOIN tbl_ba_performance b
				ON a.id = b.user_id
				INNER JOIN social_network c
				ON a.id = c.friend_id
				WHERE a.id <> ".$user_id." AND c.user_id = ".$user_id."
				ORDER BY b.progress DESC LIMIT ".$start.",".$total;
		*/
		//echo $sql;
		//exit;
		/*
		$sql = "SELECT 
						a.*,
						b.*,
						IF(c.friend>0, 1, 0) friend,
						IF(a.id=$user_id, 1, 0) ba
					FROM 
						social_member a 
						INNER JOIN tbl_ba_performance b 
						ON a.id = b.user_id 
						LEFT JOIN (SELECT friend_id AS friend FROM social_network WHERE user_id=$user_id) c 
						ON a.id = c.friend 
					WHERE 
						1
					ORDER BY 
						b.progress DESC 
					LIMIT $start,$total;";
		
		$sql1 = "SELECT 
						COUNT(id) as total
					FROM 
						social_member a 
						INNER JOIN tbl_ba_performance b 
						ON a.id = b.user_id 
						LEFT JOIN (SELECT friend_id AS friend FROM social_network WHERE user_id=$user_id) c 
						ON a.id = c.friend 
					LIMIT 1";
		*/
		$sql = "SELECT *,IF(friend>0, 1, 0) friend,
					IF(user_id=".intval($user_id).", 1, 0) ba
				FROM (SELECT a.user_id,b.*,c.*,SUM(amount) as registrants FROM tbl_daily_registration a 
				INNER JOIN social_member b
				ON a.user_id = b.id 
				LEFT JOIN (SELECT friend_id AS friend FROM social_network WHERE user_id=".intval($user_id).") c 
				ON a.user_id = c.friend
				GROUP BY a.user_id) d
				ORDER BY registrants DESC LIMIT ".intval($start).",".$total;
		
		$sql1 = "SELECT COUNT(*) as total
				FROM (SELECT a.user_id,b.*,c.*,SUM(amount) as registrants FROM tbl_daily_registration a 
				INNER JOIN social_member b
				ON a.user_id = b.id 
				LEFT JOIN (SELECT friend_id AS friend FROM social_network WHERE user_id=".intval($user_id).") c 
				ON a.user_id = c.friend
				GROUP BY a.user_id) d
				ORDER BY registrants DESC LIMIT 1";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		//print mysql_error();
		$rows = $this->fetch($sql1);
		$this->close();
		for($i=0;$i<sizeof($rs);$i++){
			$rs[$i]['progress'] = round($rs[$i]['registrants'] / $caps * 100);
		
			//if($rs[$i]['progress']==NULL){
				//$rs[$i]['progress']=0;
			//}
			$rs[$i]['level'] = $ba_level[ceil(floor($rs[$i]['progress']/10))];
		}
		
		
		return array($rs,$rows['total']);
	}
	
	function getTopBAList($user_id,$start,$total=10){
		$caps = 1050;
		$user_id = intval($user_id);
		$start = intval($start);
		$total = intval($total);
		$ba_level =  array("newbie","amateur","JourneyMan","Pro","Master","Guru","WorldClass","Legendary","DemiGod","God-Like","GOD");
		
		$sql = "SELECT *,IF(friend>0, 1, 0) friend,
					IF(user_id=".intval($user_id).", 1, 0) ba
				FROM (SELECT a.user_id,b.*,c.*,SUM(amount) as registrants FROM tbl_daily_registration a 
				INNER JOIN social_member b
				ON a.user_id = b.id 
				LEFT JOIN (SELECT friend_id AS friend FROM social_network WHERE user_id=".intval($user_id).") c 
				ON a.user_id = c.friend
				WHERE b.type=1 
				GROUP BY a.user_id) d
				ORDER BY registrants DESC LIMIT ".intval($start).",".$total;
		
		$sql1 = "SELECT COUNT(*) as total
				FROM (SELECT a.user_id,b.*,c.*,SUM(amount) as registrants FROM tbl_daily_registration a 
				INNER JOIN social_member b
				ON a.user_id = b.id 
				LEFT JOIN (SELECT friend_id AS friend FROM social_network WHERE user_id=".intval($user_id).") c 
				ON a.user_id = c.friend
				WHERE b.type=1 
				GROUP BY a.user_id) d
				ORDER BY registrants DESC LIMIT 1";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		//print mysql_error();
		$rows = $this->fetch($sql1);
		$this->close();
		for($i=0;$i<sizeof($rs);$i++){
			$rs[$i]['progress'] = round($rs[$i]['registrants'] / $caps * 100);
		
			//if($rs[$i]['progress']==NULL){
				//$rs[$i]['progress']=0;
			//}
			$rs[$i]['level'] = $ba_level[ceil(floor($rs[$i]['progress']/10))];
		}
		
		
		return array($rs,$rows['total']);
	}
	
	function getTopPLList($user_id,$start,$total=10){
		$caps = 1050;
		$user_id = intval($user_id);
		$start = intval($start);
		$total = intval($total);
		$ba_level =  array("newbie","amateur","JourneyMan","Pro","Master","Guru","WorldClass","Legendary","DemiGod","God-Like","GOD");
		
		$sql = "SELECT *,IF(friend>0, 1, 0) friend,
					IF(id=".intval($user_id).", 1, 0) ba
				FROM (SELECT b.*,c.* FROM social_member b
				LEFT JOIN (SELECT friend_id AS friend FROM social_network WHERE user_id=".intval($user_id).") c 
				ON b.id = c.friend
				WHERE b.id IN ( SELECT DISTINCT(leader_id) FROM leader_ba_lookup ) 
				GROUP BY b.id) d
				ORDER BY name DESC LIMIT ".intval($start).",".$total;
		
		//echo $sql;exit;
		
		$sql1 = "SELECT COUNT(*) as total
				FROM (SELECT b.*,c.* FROM social_member b
				LEFT JOIN (SELECT friend_id AS friend FROM social_network WHERE user_id=".intval($user_id).") c 
				ON b.id = c.friend
				WHERE b.id IN ( SELECT DISTINCT(leader_id) FROM leader_ba_lookup ) 
				GROUP BY b.id) d
				ORDER BY name DESC LIMIT 1";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		//print mysql_error();
		$rows = $this->fetch($sql1);
		$this->close();
		for($i=0;$i<sizeof($rs);$i++){
			$rs[$i]['progress'] = round($rs[$i]['registrants'] / $caps * 100);
		
			//if($rs[$i]['progress']==NULL){
				//$rs[$i]['progress']=0;
			//}
			$rs[$i]['level'] = $ba_level[ceil(floor($rs[$i]['progress']/10))];
		}
		
		
		return array($rs,$rows['total']);
	}
	
	function checkIsLeader($user_id){
		$this->open(0);
		$rs = $this->fetch('SELECT COUNT(leader_id) as leader FROM leader_ba_lookup WHERE leader_id='. $user_id);
		$this->close();
		if( $rs['leader'] == 1 ){
			return true;
		}else{
			return false;
		}
	}
	function leaderGetBa($user_id){
		$this->open(0);
		$rs = $this->fetch('SELECT DISTINCT(l.ba_id) id, m.name  FROM leader_ba_lookup l JOIN social_member m ON m.id=l.ba_id WHERE leader_id='. $user_id . ' ORDER BY m.name ASC', 1);
		$this->close();
		return $rs;
	}
	function getBaEvents($user_id){
		$this->open(0);
		$rs = $this->fetch('SELECT id, nama_event, attendants FROM events WHERE confirmed=0 && user_id=' . $user_id, 1);
		$this->close();
		return $rs;
	}
	function confirmedEvents($ba,$event,$att,$confirm){
		$this->open(0);
		$qry = "INSERT INTO konfirmasi_ba
					(ba_id, event_id, attendants, confirmed_attendants, tanggal)
					VALUES
					($ba,$event,$att,$confirm,NOW());";
		$rs = $this->query($qry);
		$rd = $this->query("UPDATE events SET confirmed=1 WHERE id=$event;");
		$this->close();
		if( $rs && $rd ){
			return true;
		}else{
			return false;
		}
	}
}
?>