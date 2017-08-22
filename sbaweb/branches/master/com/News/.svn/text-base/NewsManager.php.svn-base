<?php
/*
 *	Irvan Fanani
 *	2 Maret 2011
 */
include_once $ENGINE_PATH."Admin/UserManager.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
class NewsManager extends SQLData{
	var $fronList = array();
	var $frontPaging;
	var $ftitle;
	var $fdetail;
	var $fdate;

	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	function admin(){
		$req = $this->Request;
		if($req->getParam('act') == 'new' ){
			return $this->addNews($req);
		}else if($req->getParam('act') == 'edit' ){
			return $this->editNews($req);
		}else if($req->getParam('act') == 'delete' ){
			return $this->deleteNews($req);
		}else if($req->getParam('act') == 'comment' ){
			return $this->comment($req);
		}elseif($req->getParam('act') == 'setcom'){
			return $this->setComment($req);
		}elseif($req->getParam('act') == 'delcom'){
			return $this->deleteComment($req);
		}else{
			return $this->newsList($req);
		}
	}
	function newsList($req,$total_per_page=5){
		$this->open(0);
		$start = $req->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT * FROM gm_article", 1);
		$total = count( $r );
		
		$que = 	"SELECT 
					a.*,
					c.komen 
				FROM 
					gm_article a 
					LEFT JOIN (SELECT COUNT(id) komen, article_id FROM gm_article_comments GROUP BY article_id) c 
					ON a.id=c.article_id
				ORDER BY
					a.posted_date DESC
				LIMIT
					$start,$total_per_page
				;";
		
		//$list = $this->fetch("SELECT * FROM gm_article LIMIT $start,$total_per_page", 1);
		$list = $this->fetch($que, 1);
		$this->View->assign("list",$list);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=news"));
		$this->close();
		return $this->View->toString("News/admin/list.html");
	}
	function addNews($req){
		if( $req->getPost('cmd') == 'add' ){
			$title = $req->getPost('title');
			$brief = $req->getPost('brief');
			$detail = $this->fixTinyEditor($req->getPost('detail'));
			
			$with_img = false;
			$img_err = false;
			if( $_FILES['img']['size'] > 0 ){
				$with_img = true;
				list($width, $height, $type, $attr) = getimagesize( $_FILES['img']['tmp_name'] );
				if( $_FILES['img']['error'] > 0 ){
					$img_err = true;
					$this->View->assign("msg","FIle gambar rusak");
				}
				if( $width < 110 || $height < 75 ){
					$img_err = true;
					$this->View->assign("msg","Resolusi gambar terlalu kecil, minimal 110x75.");
				}
				if( $type == 1 || $type == 2 || $type == 3 || $type == 6 ){
					if( $type == 1 ){
						$ext = '.gif';
					}else if( $type == 2 ){
						$ext = '.jpg';
					}else if( $type == 3 ){
						$ext = '.png';
					}else if( $type == 6 ){
						$ext = '.bmp';
					}
				}else{
					$img_err = true;
					$this->View->assign("msg","Hanya file JPG, GIF, PNG dan BMP yang diperbolehkan");
				}				
			}
			
			if( $with_img ){
				if( !$img_err ){
					$post_date = date('Y-m-d H:i:s');
					$this->open(0);
					if( !$this->query("INSERT INTO gm_article (title,posted_date,brief,detail) VALUES ('$title','$post_date','$brief','$detail')")){
						$this->View->assign("msg","Add news failure");
						$this->close();
					}else{
						$this->close();
						require_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
						$sql = "SELECT id FROM gm_article WHERE posted_date='$post_date' && title='$title' LIMIT 1;";
						$this->open(0);
						$r = $this->fetch($sql);
						$this->close();
						try{
							$thumb = PhpThumbFactory::create( $_FILES['img']['tmp_name'] );
						}catch (Exception $e){
							// handle error here however you'd like
						}
						$thumb->resize(110, 110);
						if( ! is_dir( '../contents/news/' ) ){
							mkdir( '../contents/news/' );
						}
						$thumb->save( "../contents/news/". $r['id'] . $ext ); 
						$this->open(0);
						$qry = "UPDATE gm_article SET img='". $r['id'] . $ext ."' WHERE id=". $r['id'];
						if( !$this->query($qry)){
							$this->close();
						}else{
							$this->close();
						}
						return $this->View->showMessage('Berhasil', "index.php?s=news");
					}
				}
			}else{
				$this->open(0);
				if( !$this->query("INSERT INTO gm_article (title,posted_date,brief,detail) VALUES ('$title',NOW(),'$brief','$detail')")){
					$this->View->assign("msg","Add news failure");
					$this->close();
				}else{
					$this->close();
					return $this->View->showMessage('Berhasil', "index.php?s=news");
				}
			}
		}
		return $this->View->toString("News/admin/new.html");
	}
	function editNews($req){
		$id = $req->getParam('id');
		if( $req->getPost('cmd') == 'edit' ){
			$title = $req->getPost('title');
			$brief = $req->getPost('brief');
			$detail = $this->fixTinyEditor($req->getPost('detail'));
			$id = $req->getPost('id');
			
			
			
			$with_img = false;
			$img_err = false;
			if( $_FILES['img']['size'] > 0 ){
				$with_img = true;
				list($width, $height, $type, $attr) = getimagesize( $_FILES['img']['tmp_name'] );
				if( $_FILES['img']['error'] > 0 ){
					$img_err = true;
					$this->View->assign("msg","FIle gambar rusak");
				}
				if( $width < 110 || $height < 75 ){
					$img_err = true;
					$this->View->assign("msg","Resolusi gambar terlalu kecil, minimal 110x75.");
				}
				if( $type == 1 || $type == 2 || $type == 3 || $type == 6 ){
					if( $type == 1 ){
						$ext = '.gif';
					}else if( $type == 2 ){
						$ext = '.jpg';
					}else if( $type == 3 ){
						$ext = '.png';
					}else if( $type == 6 ){
						$ext = '.bmp';
					}
				}else{
					$img_err = true;
					$this->View->assign("msg","Hanya file JPG, GIF, PNG dan BMP yang diperbolehkan");
				}				
			}
			
			if( $with_img ){
				if( !$img_err ){
					$post_date = date('Y-m-d H:i:s');
					$this->open(0);
					if( !$this->query("UPDATE gm_article SET title='$title', brief='$brief', detail='$detail' WHERE id=$id")  ){
						$this->View->assign("msg","Add news failure");
						$this->close();
					}else{
						$this->close();
						require_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
						try{
							$thumb = PhpThumbFactory::create( $_FILES['img']['tmp_name'] );
						}catch (Exception $e){
							// handle error here however you'd like
						}
						$thumb->resize(110, 110);
						if( ! is_dir( '../contents/news/' ) ){
							mkdir( '../contents/news/' );
						}
						$thumb->save( "../contents/news/". $id . $ext ); 
						$this->open(0);
						$qry = "UPDATE gm_article SET img='". $id . $ext ."' WHERE id=". $id;
						if( !$this->query($qry)){
							$this->close();
						}else{
							$this->close();
						}
						return $this->View->showMessage('Berhasil', "index.php?s=news");
					}
				}
			}else{
				if( !$this->query("UPDATE gm_article SET title='$title', brief='$brief', detail='$detail' WHERE id=$id")){
					$this->View->assign("msg","Edit news failure");
				}else{
					return $this->View->showMessage('Berhasil', "index.php?s=news");
				}
			}
		}
		
		$this->open(0);
		$news = $this->fetch("SELECT * FROM gm_article WHERE id=$id");
		if( is_array($news) ){
			$this->close();
			$this->View->assign("id",$news['id']);
			$this->View->assign("title",$news['title']);
			$this->View->assign("brief",$news['brief']);
			$this->View->assign("detail",$this->forTinyEditor($news['detail']) );
			$this->View->assign("img",$news['img']);
			return $this->View->toString("News/admin/edit.html");
		}else{
			$this->close();
			return $this->View->showMessage('Invalid news id', "index.php?s=news");
		}
	}
	function deleteNews($req){
		$id = $req->getParam('id');
		$this->open(0);
		if( !$this->query("DELETE FROM gm_article WHERE id=$id")){
			$this->close();
			return $this->View->showMessage('Gagal', "index.php?s=news");
		}else{
			$this->close();
			return $this->View->showMessage('Berhasil', "index.php?s=news");
		}
	}
	function frontNewsList($req,$total_per_page=5){
		$start = $req->getParam("st");
		if($start==NULL){$start = 0;}
		$this->open(0);
		$r = $this->fetch("SELECT * FROM gm_article", 1);
		$total = count( $r );
		$list = $this->fetch("SELECT * FROM gm_article LIMIT $start,$total_per_page", 1);
		$this->frontList = $list;
		$this->Paging = new Paginate();
		$this->frontPaging = $this->Paging->getAdminPaging($start, $total_per_page, $total, "news.php?"); 
		$this->close();
	}
	function getList(){
		return $this->frontList;
	}
	function getPaging(){
		return $this->frontPaging;
	}
	function frontGetNews($id){
		$this->open(0);
		$news = $this->fetch("SELECT * FROM gm_article WHERE id=$id");
		if( is_array($news) ){
			$this->close();
			$this->ftitle = $news['title'];
			$this->fdetail = $news['detail'];
			$this->fdate = $news['posted_date'];
		}else{
			$this->close();
		}
	}
	
	function fixTinyEditor($content){
		$content = str_replace("\\r\\n","",$content);
		$content = stripslashes( $content );
		$content = str_replace("../contents","contents", $content);
		$content = str_replace("&lt;", "<", $content);
		$content = str_replace("&gt;", ">", $content);
		return $content;
	}
	
	function forTinyEditor($content){
		$content = str_replace("contents","../contents", $content);
		return $content;
	}
	
	function comment($req){
		$id = $req->getParam('id');
		$que = "SELECT c.*,m.name FROM gm_article_comments c LEFT JOIN social_member m ON c.user_id=m.id WHERE article_id=$id;";
		$list = $this->fetch($que, 1);
		$this->View->assign("list",$list);
		$this->View->assign("nid",$id);
		return $this->View->toString("News/admin/comment.html");
	}
	
	function setComment($req){
		$que = "UPDATE gm_article_comments SET n_status='".$req->getParam('set')."' WHERE id='".$req->getParam('id')."'";
		$r = $this->query( $que );
		if($r){
			return $this->View->showMessage('Berhasil', "index.php?s=news&act=comment&id=". $req->getParam('nid'));
		}else{
			return $this->View->showMessage('Gagal', "index.php?s=news&act=comment&id=". $req->getParam('nid'));
		}
	}
	
	function deleteComment($req){
		if( $this->query("DELETE FROM gm_article_comments WHERE id=".$req->getParam('id')) ){
			return $this->View->showMessage('Berhasil', "index.php?s=news&act=comment&id=". $req->getParam('nid'));
		}else{
			return $this->View->showMessage('Gagal', "index.php?s=news&act=comment&id=". $req->getParam('nid'));
		}
	}
	
}
?>
