<?php
class NewsHelper extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
	}
	function send($user_id,$msg){
		$misc = serialize($options);
		$msg = mysql_escape_string($msg);
		settype($user_id,'integer');
		if(is_int($user_id)){
			$sql = "INSERT INTO social_news(message,user_id)
				VALUES('".$msg."',$user_id)";
			//print $sql."<br/>";
			$this->open();
			$q = $this->query($sql);
			$this->close();
		}
		return $q;
	}
	
	
	function getFeeds($user_id,$start=0,$total=3){
		if($start==NULL){
			$start=0;
		}
		settype($start,"integer");
		$user_id = intval($user_id);
		$sql = "SELECT * FROM social_news a
				INNER JOIN social_member b
				ON a.user_id = b.id
				WHERE a.user_id IN (SELECT user_id FROM social_network WHERE friend_id=".$user_id.")
				OR a.user_id = ".$user_id."
					&& a.n_status=1
				ORDER BY a.id DESC";
		//print $sql;
		//paging
		//$paging = new Paginate();
		$sql1 = $sql." LIMIT ".$start.",".$total;
		//$sql2 = eregi_replace("SELECT (.*) FROM","SELECT COUNT(*) as total FROM",$sql);
		//$sql2 = eregi_replace("ORDER BY(.*)","",$sql2);
		//print $sql1;
		$this->open();
		$list = $this->fetch($sql1,1);
		
		//print mysql_error();
		//$rs = $this->fetch($sql2);
		$this->close();
		
		//$n = sizeof($list);

		//$this->assign("list",$list);
		//$this->assign("pages",$paging->generate($start, $total, $rs['total'],"index.php?notification=1"));
		//$this->mainLayout("Social/widget_news_feeds.html");
		return $list;
	}
	
}
?>
