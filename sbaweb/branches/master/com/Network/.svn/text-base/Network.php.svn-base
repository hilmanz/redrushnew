<?php
/*
 *	Irvan Fanani
 *	29 Maret 2011
 */
include_once $ENGINE_PATH."Admin/UserManager.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
class Network extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	function admin(){
		$req = $this->Request;
		if( $req->getParam('cmd') == 'set'){
			return $this->set($req->getParam('id'),$req->getParam('v'));
		}elseif( $req->getParam('cmd') == 'delete'){
			return $this->delete($req->getParam('id'));
		}elseif( $req->getParam('cmd') == 'newsdelete'){
			return $this->newsdelete($req->getParam('id'));
		}elseif( $req->getParam('cmd') == 'eventsdelete'){
			return $this->eventsdelete($req->getParam('id'));
		}elseif( $req->getParam('cmd') == 'newsset'){
			return $this->newsset($req->getParam('id'),$req->getParam('v'));
		}elseif( $req->getParam('cmd') == 'eventsset'){
			return $this->eventsset($req->getParam('id'),$req->getParam('v'));
		}elseif( $req->getParam('cmd') == 'comment' && $req->getParam('v') == 'news' ){
			return $this->commentNews($req);
		}elseif( $req->getParam('cmd') == 'comment' && $req->getParam('v') == 'events' ){
			return $this->commentEvents($req);
		}else{
			return $this->networkList($req);
		}
	}
	
	function eventsdelete($id){
		$r = $this->query("DELETE FROM events_comments WHERE id=$id");
		if($r){
			return $this->View->showMessage('Berhasil', "index.php?s=network&cmd=comment&v=events");
		}else{
			return $this->View->showMessage('Gagal', "index.php?s=network&cmd=comment&v=events");
		}
	}
	function eventsset($id,$v){
		$r = $this->query("UPDATE events_comments SET n_status=$v WHERE id=$id");
		if($r){
			return $this->View->showMessage('Berhasil', "index.php?s=network&cmd=comment&v=events");
		}else{
			return $this->View->showMessage('Gagal', "index.php?s=network&cmd=comment&v=events");
		}
	}
	function commentEvents($req){
		$total_per_page = 30;
		$start = $req->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT * FROM events_comments", 1);
		$total = count( $r );
		$list = $this->fetch("SELECT m.name,a.nama_event,n.* FROM events_comments n JOIN social_member m ON n.user_id=m.id JOIN events a ON n.event_id=a.id ORDER BY n.posted_date DESC LIMIT $start,$total_per_page", 1);
		$this->View->assign("list",$list);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=network&cmd=comment&v=events"));
		return $this->View->toString("Network/admin/commentEventslist.html");
	}
	
	
	function newsdelete($id){
		$r = $this->query("DELETE FROM gm_article_comments WHERE id=$id");
		if($r){
			return $this->View->showMessage('Berhasil', "index.php?s=network&cmd=comment&v=news");
		}else{
			return $this->View->showMessage('Gagal', "index.php?s=network&cmd=comment&v=news");
		}
	}
	function newsset($id,$v){
		$r = $this->query("UPDATE gm_article_comments SET n_status=$v WHERE id=$id");
		if($r){
			return $this->View->showMessage('Berhasil', "index.php?s=network&cmd=comment&v=news");
		}else{
			return $this->View->showMessage('Gagal', "index.php?s=network&cmd=comment&v=news");
		}
	}
	function commentNews($req){
		$total_per_page = 30;
		$start = $req->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT * FROM gm_article_comments", 1);
		$total = count( $r );
		$list = $this->fetch("SELECT m.name,a.title,n.* FROM gm_article_comments n JOIN social_member m ON n.user_id=m.id JOIN gm_article a ON n.article_id=a.id ORDER BY n.posted_date DESC LIMIT $start,$total_per_page", 1);
		$this->View->assign("list",$list);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=network&cmd=comment&v=news"));
		return $this->View->toString("Network/admin/commentlist.html");
	}
	
	
	
	function delete($id){
		$r = $this->query("DELETE FROM social_news WHERE id=$id");
		if($r){
			return $this->View->showMessage('Berhasil', "index.php?s=network");
		}else{
			return $this->View->showMessage('Gagal', "index.php?s=network");
		}
	}
	function set($id,$v){
		$r = $this->query("UPDATE social_news SET n_status=$v WHERE id=$id");
		if($r){
			return $this->View->showMessage('Berhasil', "index.php?s=network");
		}else{
			return $this->View->showMessage('Gagal', "index.php?s=network");
		}
	}
	function networkList($req){
		$total_per_page = 30;
		$start = $req->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT * FROM social_news", 1);
		$total = count( $r );
		$list = $this->fetch("SELECT m.name,n.* FROM social_news n JOIN social_member m ON n.user_id=m.id ORDER BY n.posted_date DESC LIMIT $start,$total_per_page", 1);
		$this->View->assign("list",$list);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=network"));
		return $this->View->toString("Network/admin/list.html");
	}
}