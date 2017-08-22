<?php
class ModelCoba extends SQLData{
	function __construct($req){
		
	}
	function getLatestNews($total=10){
		$sql = "SELECT * FROM coba_news ORDER BY id DESC LIMIT 10";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	function getNews($id){
		$id = mysql_escape_string($id);
		$id = intval($id);
		$sql = "SELECT * FROM coba_news WHERE id=".$id." LIMIT 1";
		$this->open(0);
		$rs = $this->fetch($sql);
		$this->close();
		return $rs;
	}
}
?>