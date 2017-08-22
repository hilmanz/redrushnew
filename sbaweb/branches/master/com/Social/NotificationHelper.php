<?php
class NotificationHelper extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
	}
	function send($user_id,$msg,$options){
		$misc = serialize($options);
		$msg = mysql_escape_string(htmlentities(strip_tags(stripslashes($msg))));
		settype($user_id,'integer');
		if(is_int($user_id)){
			$sql = "INSERT INTO social_notification(message,posted_date,misc,user_id)
				VALUES('".$msg."',CURRENT_TIMESTAMP,'".$misc."',$user_id)";
			
			$this->open();
			$q = $this->query($sql);
			$this->close();
		}
		return $q;
	}
	function unread_total($user_id){
		settype($user_id,'integer');
		$sql = "SELECT COUNT(id) as total FROM social_notification
				WHERE user_id = $user_id AND unread=1";
			
		$this->open();
		$q = $this->fetch($sql);
		$this->close();
		return $q['total'];
	}
	
	function getNotifications($user_id,$start,$total=20){
		if($start==NULL){
			$start=0;
		}
		settype($start,"integer");
		
		$sql = "SELECT * FROM social_notification WHERE user_id=".$user_id." ORDER BY id DESC";
		//paging
		$paging = new Paginate();
		$sql1 = $sql." LIMIT ".$start.",".$total;
		$sql2 = eregi_replace("SELECT (.*) FROM","SELECT COUNT(*) as total FROM",$sql);
		$sql2 = eregi_replace("ORDER BY(.*)","",$sql2);
		$this->open();
		$list = $this->fetch($sql,1);
		$rs = $this->fetch($sql2);
		$this->close();
		
		$n = sizeof($list);
		for($i=0;$i<sizeof($list);$i++){
			$list[$i]['tgl'] = date("d/m/Y H:i:s",$list[$i]['posted_date']);
			$list[$i]['options'] = unserialize($list[$i]['misc']);
			
		}
		
		$this->assign("list",$list);
		$this->assign("pages",$paging->generate($start, $total, $rs['total'],"index.php?notification=1"));
		return $this->View->toString("Social/notifications.html");
		//return $this;
	}
	function reset($user_id){
		settype($start,"integer");
		$sql = "DELETE FROM social_notification WHERE user_id = ".$user_id;
		$this->open();
		$q = $this->query($sql);
		$this->close();
		return $q;
	}
}
?>