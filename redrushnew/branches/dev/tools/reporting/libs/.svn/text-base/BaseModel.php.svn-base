<?php
class BaseModel{
	var $conn;
	var $log;
	function __construct($conn){
		$this->conn = $conn;
		
	}
	function setConnection($conn){
		$this->conn = $conn;
	}
	function logger($log){
		$this->logger = $log;
	}
	function fetch_many($sql){
		return fetch_many($sql,$this->conn);
	}
	function fetch($sql){
		return fetch($sql,$this->conn);
	}
	function query($sql){
		return mysql_query($sql,$this->conn);
	}
	function batch_query($stmts){
		$rs = array();
		foreach($stmts as $sql){
			$q = $this->query($sql);
			array_push($rs,$q);
		}
		return $rs;
	}
	function serialized_string($arr){
		$str = "";
		$n=0;
		var_dump($arr);
		foreach($arr as $a){
			if($n==1){
				$str.=",";
			}
			$str.="'".mysql_escape_string(trim($a['keyword_id']))."'";
			$n=1;
		}
		return $str;
	}
	function serialized_number($arr){
		$str = "";
		$n=0;
		
		foreach($arr as $a){
			if($n==1){
				$str.=",";
			}
			$str.="'".mysql_escape_string(trim($a['keyword_id']))."'";
			$n=1;
		}
		return $str;
	}
}
?>