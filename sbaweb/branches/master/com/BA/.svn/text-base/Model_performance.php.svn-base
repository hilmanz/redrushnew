<?php
class Model_performance extends SQLData{
	var $parent;
	var $Request;
	function __construct($req,$parent){
		$this->Request = $req;
		$this->parent = $parent;
		$this->database=$this->parent->database;
	}
	
	function get_list_performance($user_id){
		$sql="SELECT * FROM events WHERE user_id='$user_id'";
		$this->open(0);
		$rs=$this->fetch($sql,1);
		$this->close;
		return $rs;	
	}
	function get_filter_overall($fromoverall,$untiloverall,$user_id){
		$sql="SELECT * FROM events WHERE user_id='$user_id' AND tanggal_event
		BETWEEN '$fromoverall' AND '$untiloverall'";
		$this->open(0);
		$rs=$this->fetch($sql,1);
		$this->close(0);
		return $rs;
	}
	function fulllist($user_id){
		$sql="SELECT * FROM events WHERE user_id='$user_id'";
		$this->open(0);
		$rs=$this->fetch($sql,1);
		$this->close(0);
		return $rs;
	}
	function getSummary(){
		$sql = "SELECT * FROM ".$this->parent->database.".tbl_ba_performance a
				INNER JOIN ".$this->parent->database.".social_member b
				ON a.user_id = b.id
				ORDER BY b.name";
		
		$rs = $this->fetch($sql,1);
		$n = sizeof($rs);
		for($i=0;$i<$n;$i++){
			$rs[$i]['no'] = $i+1;
		}
		return $rs;
		
		
	}
	
}
?>
