<?php
/*
 *	Irvan Fanani
 *	24 Mei 2011
 */
global $ENGINE_PATH;
include_once $ENGINE_PATH."Admin/UserManager.php";
include_once "forumModel.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
class forum extends SQLData{
	var $model;
	var $user;
	function __construct($req=null,$user=null){
		parent::SQLData();
		$this->Request = $req;
		$this->user = $user;
		$this->View = new BasicView();
		$this->model = new forumModel();
	}
	
	function main(){
		$req=$this->Request;
		$act = $req->getParam('act');
		if($act=='thread'){
			return $this->thread($req);
		}elseif($act=='reply'){
			return $this->addReply($req);
		}elseif($act=='add'){
			return $this->addThread($req);
		}else{
			return $this->threadList($req);
		}
	}
	
	function admin(){
		$req=$this->Request;
		$act = $req->getParam('act');
		if($act=='change-status'){
			return $this->adminChangeThreadStatus($req);
		}elseif($act=='delete-thread'){
			return $this->adminDeleteThread($req);
		}elseif($act=='delete-reply'){
			return $this->adminDeleteReply($req);
		}elseif($act=='reply'){
			return $this->adminReplyList($req);
		}elseif($act=='change-reply'){
			return $this->adminChangeReplyStatus($req);
		}else{
			return $this->adminThreadList($req);
		}
	}
	
	function threadList($req){
		$start = intval($req->getParam('st'));
		$limit = 30;
		$rs = $this->model->getThreadList($start,$limit);
		$total = $this->model->getThreadTotal();
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $limit, $total, "?forum=1"));
		
		$this->View->assign("list",$rs);
		return $this->View->toString("Forum/forum.html");
	}
	
	function thread($req){
		$start = intval($req->getParam('st'));
		$thread_id = intval($req->getParam('tid'));
		$limit = 30;
		$rs = $this->model->getThreadPage($thread_id,$start,$limit);
		$total = $this->model->getReplyTotal($thread_id);
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $limit, $total, "?forum=1&act=thread&tid=$thread_id"));
		
		$this->View->assign("list",$rs);
		$this->View->assign("thread_id",$thread_id);
		return $this->View->toString("Forum/forum_detail.html");
	}
	
	function addReply($req){
		//biar bisa upload file gede
		ini_set('memory_limit','64M');
		
		$thread_id = intval($req->getPost('thread_id'));
		$content = $this->bbcodeParser($req->getPost('content'));
		
		if($content != ''){
			if( $_FILES['img']['size'] > 0 ){
				list($width, $height, $type, $attr) = getimagesize($_FILES['img']['tmp_name']);
				if($type == 1 || $type == 2 || $type == 3 || $type == 6){
					if($type == 1){ $ext='.gif'; }
					elseif($type == 2){ $ext='.jpg'; }
					elseif($type == 3){ $ext='.png'; }
					elseif($type == 6){ $ext='.bmp'; }
					$name = "contents/forum/".md5($_FILES['img']['name'].rand(1000,9999)).$ext;
					require_once '../engines/Utility/phpthumb/ThumbLib.inc.php';
					try{$thumb = PhpThumbFactory::create( $_FILES['img']['tmp_name'] );}catch (Exception $e){}
					if( intval($width) > 700 ){
						$thumb->resize(700,0);
					}
					if(!is_dir("contents")){
						@mkdir("contents");
					}
					if(!is_dir("contents/forum")){
						@mkdir("contents/forum");
					}
					$thumb->save($name);
					$content = '<p><img src="'.$name.'" /></p> '.$content;
							
					if($this->model->addReply($thread_id,$content,$this->user['id'])){
						return $this->View->showMessage('Berhasil', "index.php?forum=1&act=thread&tid=$thread_id");
					}else{
						return $this->View->showMessage('Gagal', "index.php?forum=1&act=thread&tid=$thread_id");
					}
				}else{
					return $this->View->showMessage('Gagal', "index.php?forum=1&act=thread&tid=$thread_id");
				}
			}else{
				if($this->model->addReply($thread_id,$content,$this->user['id'])){
					return $this->View->showMessage('Berhasil', "index.php?forum=1&act=thread&tid=$thread_id");
				}else{
					return $this->View->showMessage('Gagal', "index.php?forum=1&act=thread&tid=$thread_id");
				}
			}
		}else{
			return $this->View->showMessage('Gagal', "index.php?forum=1&act=thread&tid=$thread_id");
		}
	}
	
	function addThread($req){
		//biar bisa upload file gede
		ini_set('memory_limit','64M');
		
		if($req->getParam('cmd')=='save'){
			$content = $this->bbcodeParser($req->getPost('content'));
			$title = strip_tags($req->getPost('title'));
			
			if($content != '' && $title != ''){
				if( $_FILES['img']['size'] > 0 ){
					list($width, $height, $type, $attr) = getimagesize($_FILES['img']['tmp_name']);
					if($type == 1 || $type == 2 || $type == 3 || $type == 6){
						
						if($type == 1){ $ext='.gif'; }
						elseif($type == 2){ $ext='.jpg'; }
						elseif($type == 3){ $ext='.png'; }
						elseif($type == 6){ $ext='.bmp'; }
						$name = "contents/forum/".md5($_FILES['img']['name'].rand(1000,9999)).$ext;
						require_once '../engines/Utility/phpthumb/ThumbLib.inc.php';
						try{$thumb = PhpThumbFactory::create( $_FILES['img']['tmp_name'] );}catch (Exception $e){}
						if( intval($width) > 700 ){
							$thumb->resize(700,0);
						}
						if(!is_dir("contents")){
							@mkdir("contents");
						}
						if(!is_dir("contents/forum")){
							@mkdir("contents/forum");
						}
						$thumb->save($name);
						$content = '<p><img src="'.$name.'" /></p> '.$content;
						
						if($this->model->addThread($title,$content,$this->user['id'])){
							return $this->View->showMessage('Berhasil', "index.php?forum=1");
						}else{
							return $this->View->showMessage('Gagal', "index.php?forum=1");
						}
					}else{
						return $this->View->showMessage('Gagal', "index.php?forum=1");
					}
				}else{
					
					if($this->model->addThread($title,$content,$this->user['id'])){
						return $this->View->showMessage('Berhasil', "index.php?forum=1");
					}else{
						return $this->View->showMessage('Gagal', "index.php?forum=1");
					}
				}
			}else{
				return $this->View->showMessage('Gagal, anda belum mengisi judul atau isi forum', "index.php?forum=1");
			}
		}
		return $this->View->toString("Forum/forum_add.html");
	}
	
	function bbcodeParser($content){
		$content = strip_tags($content);
		$content = nl2br($content);
		$content = str_replace("[img]",'<img src="',$content);
		$content = str_replace("[/img]",'" />',$content);
		$content = str_replace("[b]","<b>",$content);
		$content = str_replace("[/b]","</b>",$content);
		$content = str_replace("[i]","<i>",$content);
		$content = str_replace("[/i]","</i>",$content);
		$content = str_replace("[u]","<u>",$content);
		$content = str_replace("[/u]","</u>",$content);
		$content = str_replace("\\r\\n","<br />",$content);
		return $content; 
	}
	
	/*===> ADMIN <===*/
	function adminThreadList($req){
		$start = intval($req->getParam('st'));
		$limit = 30;
		$rs = $this->model->getAdminThreadList($start,$limit);
		$total = $this->model->getAdminThreadTotal();
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $limit, $total, "?s=forum"));
		
		$this->View->assign("list",$rs);
		return $this->View->toString("Forum/admin/list.html");
	}
	function adminReplyList($req){
		$thread_id = intval($req->getParam('topic'));
		$start = intval($req->getParam('st'));
		$limit = 30;
		$rs = $this->model->getAdminReplyList($thread_id,$start,$limit);
		$total = $this->model->getAdminReplyTotal($thread_id);
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $limit, $total, "?s=forum"));
		
		$this->View->assign("list",$rs);
		return $this->View->toString("Forum/admin/reply.html");
	}
	function adminChangeThreadStatus($req){
		$id = intval($req->getParam('id'));
		$status = intval($req->getParam('status'));
		if($this->query("UPDATE tbl_forum_topic SET n_status='$status' WHERE id=$id;")){
			echo json_encode(array('success'=>1));
		}else{
			echo json_encode(array('success'=>0));
		}
		exit;
	}
	function adminChangeReplyStatus($req){
		$id = intval($req->getParam('id'));
		$status = intval($req->getParam('status'));
		if($this->query("UPDATE tbl_forum_reply SET n_status='$status' WHERE id=$id;")){
			echo json_encode(array('success'=>1));
		}else{
			echo json_encode(array('success'=>0));
		}
		exit;
	}
	function adminDeleteThread($req){
		$id = intval($req->getParam('id'));
		if($this->query("UPDATE tbl_forum_topic SET n_status='3' WHERE id=$id;")){
			return $this->View->showMessage('Berhasil menghapus topic', "index.php?s=forum");
		}else{
			return $this->View->showMessage('Gagal menghapus topic, silakan coba lagi!', "index.php?s=forum");
		}
	}
	function adminDeleteReply($req){
		$id = intval($req->getParam('id'));
		$topic = intval($req->getParam('topic'));
		if($this->query("UPDATE tbl_forum_reply SET n_status='3' WHERE id=$id;")){
			return $this->View->showMessage('Berhasil menghapus topic', "index.php?s=forum&act=reply&topic=$topic");
		}else{
			return $this->View->showMessage('Gagal menghapus topic, silakan coba lagi!', "index.php?s=forum&act=reply&topic=$topic");
		}
	}
}
