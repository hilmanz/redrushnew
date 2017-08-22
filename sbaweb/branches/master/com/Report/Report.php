<?php

include_once $ENGINE_PATH."Utility/Paginate.php";
class Report extends SQLData{

	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	
	function BA_Login(){
		
	}
	
	/** backend stuffs **/
	function admin(){
		$req = $this->Request;
		if($req->getParam('act') == 'csv' ){
			
		}else if($req->getParam('t')!=NULL){
		    $this->getCSVReport($req);
		}else{
			if( $req->getParam('act') != NULL ){
				return $this->activity($req);
			}else{
				return $this->Overall($req);
			}
		}
	}
	function getCSVReport($req){
		$filename = "report_".$req->getParam("t")."_".date("YmdHis").".csv";
		 header("Content-type: application/force-download");
  		header("Content-Disposition: attachment; filename=\"".$filename."\"");
  		
		switch($req->getParam("t")){
			case "1":
				$str = $this->total_comments_on_article();
			break;
			case "2":
				$str = $this->total_posting_events();
			break;
			case "3":
				$str = $this->total_comments_on_events();
			break;
			case "4":
				$str = $this->wall_posts();
			break;
			case "5":
				$str = $this->wall_reply();
			break;
			case "6":
				$str = $this->forum_post();
			break;
			case "7":
				$str = $this->forum_reply();
			break;
			case "8":
				$str = $this->logins();
			break;
			default :
				$str = "";
			break;
		}
		header("Content-Length: ".strlen($str));
		print $str;
		die();
	}
	function wall_reply(){
		$sql = "SELECT A.user_id,B.name,COUNT(A.id) as wall_post_reply FROM `social_posts_reply` A
					INNER JOIN social_member B
					ON A.user_id = B.id
					GROUP BY A.user_id
					LIMIT 1000";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		//var_dump($rs);
		$str = "\"id\";\"name\";\"total\"\r\n";
		foreach($rs as $d){
			$str.="\"".$d['user_id']."\";\"".$d['name']."\";\"".$d['wall_post_reply']."\"\r\n";
		}
		return $str;
	}
	function forum_post(){
		$sql = "SELECT A.user_id,B.name,COUNT(A.id) as forum_topics FROM `tbl_forum_topic` A
				INNER JOIN social_member B
				ON A.user_id = B.id
				WHERE A.n_status=1 OR A.n_status=2
				GROUP BY A.user_id
				LIMIT 1000";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		//var_dump($rs);
		$str = "\"id\";\"name\";\"total\"\r\n";
		foreach($rs as $d){
			$str.="\"".$d['user_id']."\";\"".$d['name']."\";\"".$d['forum_topics']."\"\r\n";
		}
		return $str;
	}
	function forum_reply(){
		$sql = "SELECT A.user_id,B.name,COUNT(A.id) as forum_reply FROM `tbl_forum_reply` A
				INNER JOIN social_member B
				ON A.user_id = B.id
				WHERE A.n_status=1
				GROUP BY A.user_id
				LIMIT 1000";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		//var_dump($rs);
		$str = "\"id\";\"name\";\"total\"\r\n";
		foreach($rs as $d){
			$str.="\"".$d['user_id']."\";\"".$d['name']."\";\"".$d['forum_reply']."\"\r\n";
		}
		return $str;
	}
	function logins(){
		$sql = "SELECT A.user_id,B.name,COUNT(A.id) as logins FROM `tbl_interaction_daily` A
				INNER JOIN social_member B
				ON A.user_id = B.id
				WHERE A.source_id = 'LOGIN'
				GROUP BY A.user_id
				LIMIT 1000";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		//var_dump($rs);
		$str = "\"id\";\"name\";\"total\"\r\n";
		foreach($rs as $d){
			$str.="\"".$d['user_id']."\";\"".$d['name']."\";\"".$d['logins']."\"\r\n";
		}
		return $str;
	}
	function wall_posts(){
		$sql = "SELECT A.user_id,B.name,COUNT(A.id) as wall_post FROM `social_posts` A
				INNER JOIN social_member B
				ON A.user_id = B.id
				GROUP BY A.user_id
				LIMIT 1000";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		//var_dump($rs);
		$str = "\"id\";\"name\";\"total\"\r\n";
		foreach($rs as $d){
			$str.="\"".$d['user_id']."\";\"".$d['name']."\";\"".$d['wall_post']."\"\r\n";
		}
		return $str;
	}
	function total_comments_on_article(){
		$sql = "SELECT A.user_id,B.name,COUNT(*) as comments FROM gm_article_comments A
				INNER JOIN social_member B
				ON A.user_id = B.id
				WHERE A.n_status=1
				GROUP BY A.user_id LIMIT 1000";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		//var_dump($rs);
		$str = "\"id\";\"name\";\"comments\"\r\n";
		foreach($rs as $d){
			$str.="\"".$d['user_id']."\";\"".$d['name']."\";\"".$d['comments']."\"\r\n";
		}
		return $str;
	}
	function total_posting_events(){
		$sql = "SELECT A.user_id,B.name,COUNT(A.id) as event_posted FROM `events` A
				INNER JOIN social_member B
				ON A.user_id = B.id
				WHERE A.n_status=1
				GROUP BY A.user_id LIMIT 1000";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		//var_dump($rs);
		$str = "\"id\";\"name\";\"total\"\r\n";
		foreach($rs as $d){
			$str.="\"".$d['user_id']."\";\"".$d['name']."\";\"".$d['event_posted']."\"\r\n";
		}
		return $str;
	}
	function total_comments_on_events(){
		$sql = "SELECT A.user_id,B.name,COUNT(A.id) as event_comment FROM `events_comments` A
				INNER JOIN social_member B
				ON A.user_id = B.id
				WHERE A.n_status=1
				GROUP BY A.user_id LIMIT 1000
						";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		//var_dump($rs);
		$str = "\"id\";\"name\";\"total\"\r\n";
		foreach($rs as $d){
			$str.="\"".$d['user_id']."\";\"".$d['name']."\";\"".$d['event_comment']."\"\r\n";
		}
		return $str;
	}
	function Overall(){
		return $this->View->toString("Report/admin/overall.html");
	}

	function activity($req=null){
		$act = intval($req->getParam('act'));
		
		switch($act){
			case 1:
				$str = $this->activityArticleComment($req);
			break;
			case 2:
				$str = $this->activityPostingEvent($req);
			break;
			case 3:
				$str = $this->activityEventComments($req);
			break;
			case 4:
				$str = $this->activityWallPost($req);
			break;
			case 5:
				$str = $this->activityWallReply($req);
			break;
			case 6:
				$str = $this->activityCreateTopic($req);
			break;
			case 7:
				$str = $this->activityReplyTopic($req);
			break;
			case 8:
				$str = $this->activityLogin($req);
			break;
			default:
				$str = "";
			break;
		}
		
		return $str;
	}
	function activityLogin($req=null){
		$qry = "SELECT DATE(log_time) as tgl, B.name,B.email FROM `tbl_interaction_daily` A
INNER JOIN social_member B
ON A.user_id = B.id
WHERE A.source_id = 'LOGIN'";
		
		$list = $this->fetch($qry,1);
		$total = count($list);
		
		if( $req->getParam('export') == 1 ){
			global $ENGINE_PATH;
		    include_once $ENGINE_PATH."Utility/PHPExcelWrapper.php";
		    
		    $excel = new PHPExcelWrapper();
		    $excel->setGlobalBorder(true, 'allborders', '00000000');
		    $excel->setHeader(array('Tanggal','Nama','Email'));
		    $excel->getExcel($list, "login".date('dmY'));
		    exit;
		}
		
		$this->View->assign("list",$list);
		$this->View->assign("total",$total);
		$this->View->assign("title","Login");
		$this->View->assign("act",8);
		
		return $this->View->toString("Report/admin/article-comment.html");
	}
	function activityReplyTopic($req=null){
		$qry = "SELECT DATE(posted_date) as tgl, B.name,B.email FROM `tbl_forum_reply` A
INNER JOIN social_member B
ON A.user_id = B.id
WHERE A.n_status=1";
		
		$list = $this->fetch($qry,1);
		$total = count($list);
		
		if( $req->getParam('export') == 1 ){
			global $ENGINE_PATH;
		    include_once $ENGINE_PATH."Utility/PHPExcelWrapper.php";
		    
		    $excel = new PHPExcelWrapper();
		    $excel->setGlobalBorder(true, 'allborders', '00000000');
		    $excel->setHeader(array('Tanggal','Nama','Email'));
		    $excel->getExcel($list, "replytopic".date('dmY'));
		    exit;
		}
		
		$this->View->assign("list",$list);
		$this->View->assign("total",$total);
		$this->View->assign("title","Reply Topic");
		$this->View->assign("act",7);
		
		return $this->View->toString("Report/admin/article-comment.html");
	}
	function activityCreateTopic($req=null){
		$qry = "SELECT DATE(posted_date) as tgl, B.name,B.email FROM `tbl_forum_topic` A
INNER JOIN social_member B
ON A.user_id = B.id
WHERE A.n_status=1 OR A.n_status=2";
		
		$list = $this->fetch($qry,1);
		$total = count($list);
		
		if( $req->getParam('export') == 1 ){
			global $ENGINE_PATH;
		    include_once $ENGINE_PATH."Utility/PHPExcelWrapper.php";
		    
		    $excel = new PHPExcelWrapper();
		    $excel->setGlobalBorder(true, 'allborders', '00000000');
		    $excel->setHeader(array('Tanggal','Nama','Email'));
		    $excel->getExcel($list, "createtopic".date('dmY'));
		    exit;
		}
		
		$this->View->assign("list",$list);
		$this->View->assign("total",$total);
		$this->View->assign("title","Create Topic");
		$this->View->assign("act",6);
		
		return $this->View->toString("Report/admin/article-comment.html");
	}
	function activityWallReply($req=null){
		$qry = "SELECT DATE(post_time) as tgl,A.user_id,B.name,B.email FROM `social_posts_reply` A
INNER JOIN social_member B
ON A.user_id = B.id";
		
		$list = $this->fetch($qry,1);
		$total = count($list);
		
		if( $req->getParam('export') == 1 ){
			global $ENGINE_PATH;
		    include_once $ENGINE_PATH."Utility/PHPExcelWrapper.php";
		    
		    $excel = new PHPExcelWrapper();
		    $excel->setGlobalBorder(true, 'allborders', '00000000');
		    $excel->setHeader(array('Tanggal','User ID','Name','Email'));
		    $excel->getExcel($list, "wallreply".date('dmY'));
		    exit;
		}
		
		$this->View->assign("list",$list);
		$this->View->assign("total",$total);
		$this->View->assign("title","Wall Reply");
		$this->View->assign("act",5);
		
		return $this->View->toString("Report/admin/article-comment.html");
	}
	function activityWallPost($req=null){
		$qry = "SELECT DATE(post_time) as tgl,A.user_id,B.name,B.email FROM `social_posts` A
INNER JOIN social_member B
ON A.user_id = B.id";
		
		$list = $this->fetch($qry,1);
		$total = count($list);
		
		if( $req->getParam('export') == 1 ){
			global $ENGINE_PATH;
		    include_once $ENGINE_PATH."Utility/PHPExcelWrapper.php";
		    
		    $excel = new PHPExcelWrapper();
		    $excel->setGlobalBorder(true, 'allborders', '00000000');
		    $excel->setHeader(array('Tanggal','user id','name','email'));
		    $excel->getExcel($list, "wallpost".date('dmY'));
		    exit;
		}
		
		$this->View->assign("list",$list);
		$this->View->assign("total",$total);
		$this->View->assign("title","Wall Post");
		$this->View->assign("act",4);
		
		return $this->View->toString("Report/admin/article-comment.html");
	}
	function activityEventComments($req=null){
		$qry = "SELECT DATE(posted_date) as tgl, B.name,B.email FROM events_comments A
INNER JOIN social_member B
ON A.user_id = B.id
WHERE A.n_status=1 ";
		
		$list = $this->fetch($qry,1);
		$total = count($list);
		
		if( $req->getParam('export') == 1 ){
			global $ENGINE_PATH;
		    include_once $ENGINE_PATH."Utility/PHPExcelWrapper.php";
		    
		    $excel = new PHPExcelWrapper();
		    $excel->setGlobalBorder(true, 'allborders', '00000000');
		    $excel->setHeader(array('Tanggal','Nama','Email'));
		    $excel->getExcel($list, "eventcomment".date('dmY'));
		    exit;
		}
		
		$this->View->assign("list",$list);
		$this->View->assign("total",$total);
		$this->View->assign("title","Event Comment");
		$this->View->assign("act",3);
		
		return $this->View->toString("Report/admin/article-comment.html");
	}
	function activityArticleComment($req=null){
		$qry = "SELECT DATE(posted_date) as tgl, B.name,B.email FROM gm_article_comments A
INNER JOIN social_member B
ON A.user_id = B.id
WHERE A.n_status=1";
		
		$list = $this->fetch($qry,1);
		$total = count($list);
		
		if( $req->getParam('export') == 1 ){
			global $ENGINE_PATH;
		    include_once $ENGINE_PATH."Utility/PHPExcelWrapper.php";
		    
		    $excel = new PHPExcelWrapper();
		    $excel->setGlobalBorder(true, 'allborders', '00000000');
		    $excel->setHeader(array('Tanggal','Nama','Email'));
		    $excel->getExcel($list, "articlecomment".date('dmY'));
		    exit;
		}
		
		$this->View->assign("list",$list);
		$this->View->assign("total",$total);
		$this->View->assign("title","Article Comment");
		$this->View->assign("act",1);
		
		return $this->View->toString("Report/admin/article-comment.html");
	}
	function activityPostingEvent($req=null){
		$qry = "SELECT DATE(posted_date) AS tgl, B.name,B.email FROM baspace_db.events A
INNER JOIN baspace_db.social_member B
ON A.user_id = B.id
WHERE A.n_status=1";
		
		$list = $this->fetch($qry,1);
		$total = count($list);
		
		if( $req->getParam('export') == 1 ){
			global $ENGINE_PATH;
		    include_once $ENGINE_PATH."Utility/PHPExcelWrapper.php";
		    
		    $excel = new PHPExcelWrapper();
		    $excel->setGlobalBorder(true, 'allborders', '00000000');
		    $excel->setHeader(array('Tanggal','Nama','Email'));
		    $excel->getExcel($list, "postingevent".date('dmY'));
		    exit;
		}
		
		$this->View->assign("list",$list);
		$this->View->assign("total",$total);
		$this->View->assign("title","Posting Event");
		$this->View->assign("act",2);
		
		return $this->View->toString("Report/admin/article-comment.html");
	}
	
}