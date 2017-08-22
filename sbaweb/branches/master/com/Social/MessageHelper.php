<?php
class MessageHelper extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
	}
	function inbox($to_id,$start,$total=5){
		$start = intval($start);
		$total = intval($total);
		$sql = "SELECT a.*,b.name as sender_name,DATE_FORMAT(a.posted_date,'%d/%m/%Y') as tgl 
				FROM social_message a
				INNER JOIN
				social_member b
				on 
				a.from_id = b.id 
				WHERE a.to_id=".$to_id." 
				ORDER BY a.id DESC 
				LIMIT ".$start.",".$total;
		
		$query="SELECT COUNT(*) as total
				FROM social_message a
				INNER JOIN
				social_member b
				ON 
				a.from_id = b.id 
				WHERE a.to_id=".$to_id." 
				ORDER BY a.id DESC";
		
		$this->open();
		$rs = $this->fetch($sql,1);
		$result=$this->fetch($query);
		$totalisi = $result["total"];
		
		$this->close();
		$this->Paging = new Paginate();	
		$this->View->assign("list",$rs);
		$this->View->assign("paging",$this->Paging->generate($start, $total, $totalisi, "?inbox=1"));
		return $this->View->toString("Social/inbox.html");
	}
	function read_message($to_id,$id){
		$to_id = intval($to_id);
		$id = intval($id);
		$sql = "SELECT a.*,b.name as sender_name,DATE_FORMAT(a.posted_date,'%d/%m/%Y') as tgl 
				FROM social_message a
				INNER JOIN
				social_member b
				on 
				a.from_id = b.id 
				WHERE a.to_id=".$to_id." AND a.id=".$id."
				LIMIT 1";
		
		$this->open();
		$rs = $this->fetch($sql);
		$this->close();
		$this->View->assign("rs",$rs);
		
		return $this->View->toString("Social/read_message.html");
		
	}
	
	function reply_message($sender,$id){
		$to_id = intval($to_id);
		$id = intval($id);
		$sql = "SELECT a.*,b.name as sender_name,DATE_FORMAT(a.posted_date,'%d/%m/%Y') as tgl 
				FROM social_message a
				INNER JOIN
				social_member b
				on 
				a.from_id = b.id 
				WHERE a.from_id=".$sender." AND a.id=".$id."
				LIMIT 1";
		
		$this->open();
		$rs = $this->fetch($sql);
		$this->close();
		$this->View->assign("rs",$rs);
		return $this->View->toString("Social/reply_message.html");
		
	}
	
	function send_form(){
		
		$sql = "SELECT a.*,b.nama FROM social_member a
				INNER JOIN dm_member b
				ON a.register_id = b.id
				WHERE b.n_status=1 
				ORDER BY nama ASC
				LIMIT 1000";
		$this->open();
		$rs = $this->fetch($sql,1);
		$this->close();
		$n = sizeof($rs);
		for($i=0;$i<$n;$i++){
			$rs[$i]['val'] = $rs[$i]['id'].",".$rs[$i]['nama'];
		}
		$this->View->assign("ba",$rs);
		return $this->View->toString("Social/send_message.html");
	}
	function send($from_id,$to_id,$subject,$message){
		$subject = mysql_escape_string($subject);
		$message = mysql_escape_string($message);
		$from_id = intval($from_id);
		$to_id = intval($to_id);
		$sql = "INSERT INTO social_message(from_id,to_id,subject,message,posted_date)
				VALUES(".$from_id.",".$to_id.",'".$subject."','".$message."',NOW())";
		
		$this->open();
		$q = $this->query($sql);
		$this->close();
		return $q;
	}
}
?>