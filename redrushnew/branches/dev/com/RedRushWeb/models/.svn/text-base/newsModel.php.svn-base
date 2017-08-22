<?php
	class newsModel extends App{
		function __construct(){ }
		
		function getNews($id=null){
			$q = "SELECT * FROM ".DB_PREFIX."_news WHERE id='".$id."' AND status='1'  AND category_id=1 LIMIT 1";
			$this->open(0);
			$r = $this->fetch($q);
			$this->close();
			return $r;
		}
		
		function getLatest($start,$limit){
			$q = "SELECT * FROM ".DB_PREFIX."_news WHERE status='1' AND category_id=1 ORDER BY posted_date DESC LIMIT ".$start.",".$limit;
			$this->open(0);
			$r = $this->fetch($q,1);
			$this->close();
			return $r;
		}
		
		function getTotalNews(){
			$q = "SELECT COUNT(*) total FROM ".DB_PREFIX."_news WHERE status='1' AND category_id=1";
			$this->open(0);
			$r = $this->fetch($q);
			$this->close();
			return $r['total'];
		}
		
		function getFeatured($limit=1){
			$q = "SELECT * FROM ".DB_PREFIX."_news WHERE featured='1' AND status='1' ORDER BY posted_date DESC LIMIT ".$limit;
			$this->open(0);
			$r = $this->fetch($q,1);
			$this->close();
			return $r;
		}
	}
?>
