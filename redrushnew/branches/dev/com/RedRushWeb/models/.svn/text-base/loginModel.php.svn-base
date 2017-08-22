<?php
	class loginModel extends SQLData{
		function __construct(){ parent::SQLData();	}
		
		function cekUser($username=''){
			$q = "SELECT * FROM kana_member WHERE username='".$username."' LIMIT 1;";
			$this->open(0);
			$r = $this->fetch($q);
			$this->close();
			return $r;
		}
	}
?>