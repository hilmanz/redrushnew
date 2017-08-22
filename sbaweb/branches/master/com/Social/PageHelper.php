<?php
class PageHelper extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
	}
	function getPageInfo($page_id){
		$sql = "SELECT * FROM social_pages WHERE id=".$page_id." LIMIT 1";
		$this->open(0);
		$rs = $this->fetch($sql);
		$this->close();
		return $rs;
	}
	function create_page($name,$about,$alias,$descriptions,$page_info){
		$sql = "INSERT INTO `sba`.`social_pages`
            (
             `page_name`,
             `page_alias`,
             `descriptions`,
             `created_date`,
             `n_status`,
             `img`,
             `page_info`)
			VALUES (
			        '".$name."',
			        '".$alias."',
			        '".$descriptions."',
			        NOW(),
			        1,
			        '',
			        '".mysql_escape_string($page_info)."')";
		
		$this->open(0);
		$rs = $this->query($sql,true);
		
		$this->close();
	
		return $rs;
	}
	
	
}
?>