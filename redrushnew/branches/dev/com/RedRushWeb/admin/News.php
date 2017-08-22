<?php
/*
 *	Irvan Fanani
 *	2 Maret 2011
 * 	update with category	30 Maret 2011
 *  Update with trash helper from Babar 11 April 2012
 */
include_once $ENGINE_PATH."Admin/UserManager.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once APP_PATH.APPLICATION."/helper/trashHelper.php";
class News extends SQLData{
	var $fronList = array();
	var $frontPaging;
	var $ftitle;
	var $fdetail;
	var $fdate;
	var $_msg='';
	var $_imgNum=1;
	var $_img=array();
	var $_param = 'news';
	var $trash;
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->trash = new trashHelper();
		if($req){
			$this->User = new UserManager();
		}
	}
	function admin(){
		$req = $this->Request;
		if( $req->getParam('act') == 'add'){
			return $this->addNews($req);
		}else if($req->getParam('act') == 'edit'){
			return $this->editNews($req);
		}else if($req->getParam('act') == 'delete'){
			return $this->deleteNews($req);
		}else if($req->getParam('act') == 'featured'){
			return $this->featuredNews($req);
		}else if($req->getParam('act') == 'comment'){
			return $this->comment($req);
		}elseif($req->getParam('act') == 'delcom'){
			return $this->deleteComment($req);
		}elseif($req->getParam('act') == 'setcom'){
			return $this->setComment($req);
		}elseif($req->getParam('act') == 'addcalendar'){
			return $this->addCalendar($req);
		}elseif($req->getParam('act') == 'editcalendar'){
			return $this->editCalendar($req);
		}elseif($req->getParam('act') == 'city' && $req->getParam('ajax') == '1'){
			return $this->getCity( $req->getParam('id') );
		}else if($req->getParam('cmd') == 'hot'){
			return $this->adminArticleHot();
		}
		else{
			return $this->newsList($req);
		}
	}
	/*
	function adminArticleHot(){
		$id = $this->Request->getParam("id");
		$set = $this->Request->getParam("set");
		$r = $this->setHot($id,$set);
		if( $r ){
			return $this->View->showMessage("Berhasil","index.php?s=articles");
		}else{
			return $this->View->showMessage("Gagal","index.php?s=articles");
		}
	}
	
	function setHot($id,$set){
		$que = "UPDATE avo_article_content SET hot=$set WHERE id=$id;";
		$r = $this->query($que);
		return $r;
	}
	*/
	function deleteComment($req){
		if( $this->query("DELETE FROM ".DB_PREFIX."_news_comments WHERE id=".$req->getParam('id')) ){
			if( $req->getParam('ref') == 'dashboard' ){
				return $this->View->showMessage('Berhasil', "index.php");
			}else{
				return $this->View->showMessage('Berhasil', "index.php?s=".$this->_param."&act=comment&id=". $req->getParam('nid'));
			}
		}else{
			if( $req->getParam('ref') == 'dashboard' ){
				return $this->View->showMessage('Gagal', "index.php");
			}else{
				return $this->View->showMessage('Gagal', "index.php?s=".$this->_param."&act=comment&id=". $req->getParam('nid'));
			}
		}
	}
	function setComment($req){
		$que = "UPDATE ".DB_PREFIX."_news_comments SET n_status='".$req->getParam('set')."' WHERE id='".$req->getParam('id')."'";
		$r = $this->query( $que );
		if($r){
			if( $req->getParam('ref') == 'dashboard' ){
				return $this->View->showMessage('Berhasil', "index.php");
			}else{
				return $this->View->showMessage('Berhasil', "index.php?s=".$this->_param."&act=comment&id=". $req->getParam('nid'));
			}
		}else{
			if( $req->getParam('ref') == 'dashboard' ){
				return $this->View->showMessage('Gagal', "index.php");
			}else{
				return $this->View->showMessage('Gagal', "index.php?s=".$this->_param."&act=comment&id=". $req->getParam('nid'));
			}
		}
	}
	
	function comment($req){
		
		$id = intval($req->getParam('id')) == 0 ? '' : ' && c.article_id='.intval($req->getParam('id'));
		$pid = intval($req->getParam('id')) == 0 ? '' : '&id='.intval($req->getParam('id'));
		$template = intval($req->getParam('id')) == 0 ? 'news_comment_all' : 'news_comment';
		$status = $req->getParam('status') == '' ? '' : " && c.n_status='".$req->getParam('status')."'";
		$status_param = $req->getParam('status') == '' ? '' : "&status=".$req->getParam('status');
		$this->View->assign("status",$req->getParam('status'));
		
		$this->View->assign("nid",$id);
		$start = $req->getParam("st");
		if($start==NULL){$start = 0;}
		
		$r = $this->fetch("SELECT count(*) total FROM ".DB_PREFIX."_news_comments c WHERE 1 $id $status");
		
		$total = $r['total'];
		$total_per_page = 30;
		
		$list = $this->fetch("SELECT c.*,m.name,a.title,ac.category_name FROM ".DB_PREFIX."_news_comments c LEFT JOIN ".DB_PREFIX."_member m ON c.user_id=m.id LEFT JOIN ".DB_PREFIX."_news a ON c.article_id=a.id LEFT JOIN ".DB_PREFIX."_news_categories ac ON a.category_id=ac.category_id WHERE 1 $id $status LIMIT $start,$total_per_page", 1);
		$this->View->assign("list",$list);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=".$this->_param."&act=comment$pid$status_param"));
		return $this->View->toString(APPLICATION."/admin/$template.html");
	}
	
	function newsList($req,$total_per_page=50){
		
		$start = $req->getParam("st");
		$req->getParam("group")  != '' ? $group = " && a.category_id=".$req->getParam("group") : $group = "";
		if($req->getParam("kw")  != ''){
			$group = " && ( a.title LIKE '%".$req->getParam("kw")."%' OR a.brief LIKE '%".$req->getParam("kw")."%' )";
		}
		
		if($start==NULL){$start = 0;}
		$q="SELECT * FROM ".DB_PREFIX."_news a WHERE 1 $group";
		$r = $this->fetch($q, 1);
		$total = count( $r );
		$que = "SELECT 
					a.*,
					c.comment,
					ct.category_name as category
				FROM 
					".DB_PREFIX."_news a
					LEFT JOIN (SELECT COUNT(*) COMMENT, article_id FROM ".DB_PREFIX."_news_comments GROUP BY article_id) c
					ON a.id=c.article_id 
					LEFT JOIN ".DB_PREFIX."_news_categories ct
					ON a.category_id = ct.category_id 
				WHERE 1 $group
				ORDER BY a.status DESC,a.posted_date DESC
				LIMIT
					$start,$total_per_page;";
		$list = $this->fetch($que, 1);
		//print_r($list);exit;
		$this->View->assign("cat_id",$req->getParam("group") );
		$this->View->assign("list",$list);
		$cat = $this->fetch("SELECT * FROM ".DB_PREFIX."_news_categories;",1);
		$this->View->assign("cat",$cat);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=".$this->_param."&group=". $req->getParam("group")."&kw=".$req->getParam("kw")));
		return $this->View->toString(APPLICATION."/admin/news_list.html");
	}
	
	function addNews($req){
		/* REMAKE BY BABAR FOR REDRUSH - 11 April 2012 */
		$save = $this->Request->getPost("save");
		if($save==1){
			$title = $this->Request->getPost("title");
			$cat = $this->Request->getPost("category");
			$brief = $this->Request->getPost("brief");
			$detail = $this->Request->getPost("detail");
			$status = $this->Request->getPost("status");
			
			if($title=='' || $brief=='' || $detail==''){
				$msg=='please complet the form!';
			}else{
				if($_FILES['img1']['name']==''){
					$q = "INSERT INTO ".DB_PREFIX."_news (title, posted_date, brief, detail,category_id,status)
							VALUES ('".$title."', NOW(), '".$brief."', '".$detail."', '".$cat."', '".$status."')";
					$r = $this->query($q);
					if($r){
						return $this->View->showMessage('Success', "index.php?s=news");
					}else{
						return $this->View->showMessage('Failed, please try again later', "index.php?s=news");
					}
				}else{
					// primary image
					$img_name = $_FILES['img1']['name'];
					$img_loc = $_FILES['img1']['tmp_name'];
					$img_type = $_FILES['img1']['type'];
					$img_newname = "news_".date('YmdHis');
					
					//declare extension primary image
					if($img_type=='image/jpeg'){$ext = '.jpg';}
					if($img_type=='image/png'){$ext = '.png';}
					if($img_type=='image/gif'){$ext = '.gif';}
					
					// new file
					$newfile = $img_newname.$ext; // new file name for primary image
					$thumbfile = "thumb_".$newfile; // new file name for thumbnail image
					$folder = "../public_html/contents/news/";
					//echo $newfile;
					global $ENGINE_PATH;
					include_once $ENGINE_PATH."Utility/Thumbnail.php";	
					$thumb 	= new Thumbnail();
					if(move_uploaded_file($img_loc,$folder.$newfile)){
						if($thumb->createThumbnail($folder.$newfile,$folder.$thumbfile,125,125)){
							$q = "INSERT INTO ".DB_PREFIX."_news (title, posted_date, brief, detail,img,category_id,status)
							VALUES ('".$title."', NOW(), '".$brief."', '".$detail."', '".$newfile."', '".$cat."', '".$status."')";
							$r = $this->query($q);
							if($r){
								return $this->View->showMessage('Success', "index.php?s=news");
							}
							else{
								$err = mysql_error();
								$err = "failed upload image! $err";
								/* $this->trash->execute($folder,$newfile);
								$this->trash->execute($folder,$thumbfile); */
								@unlink($folder.$newfile);
								@unlink($folder.$thumbfile);
							}
							$this->close();
							return $this->View->showMessage($msg, "index.php?s=news");
						}
						else{ 
								$msg = "failed processing the image"; 
								@unlink($folder.$newfile);
						}
					}
					else{
						$msg = "failed move upload file";
					}
				}
			}
			
		}
		
		$category = $this->getCategoryList();
		$this->View->assign('cat',$category);
		$this->View->assign('msg',$msg);
		return $this->View->toString(APPLICATION."/admin/news_add.html");
	}
	
	function addNewsAseli($req){
		if( $req->getPost('cmd') == 'add' ){
			
			$title 		= mysql_escape_string($req->getPost('title'));
			$category 	= intval($req->getPost('category'));
			$brief 		= mysql_escape_string($req->getPost('brief'));
			$video 		= mysql_escape_string($req->getPost('video'));
			$detail 	= $this->fixTinyEditor( $req->getPost('detail') );
			$status 	= intval($req->getPost('status'));
			
			$img1=$this->imageInfo('img1');
			$img2=$this->imageInfo('img2');
			$img3=$this->imageInfo('img3');
			$img4=$this->imageInfo('img4');
			$img5=$this->imageInfo('img5');
			
			if( $title=='' || $category=='' || $brief=='' || $detail==''){
				$category = $this->getCategoryList();
				$this->View->assign('cat',$category);
				$this->View->assign('msg',"Please complete the form!");
				return $this->View->toString("News/admin/new.html");
			}
			
			//echo "INSERT INTO gm_article (title,category_id,brief,video,detail,status,posted_date) VALUES ('$title','$category','$brief','$video','$detail','$status',NOW())";
			//exit;
			
			if( !$this->query("INSERT INTO ".DB_PREFIX."_news (title,category_id,brief,video,detail,status,posted_date) VALUES ('$title','$category','$brief','$video','$detail','$status',NOW())")){
				$this->View->assign("msg","Add process failure");
				$category = $this->getCategoryList();
				$this->View->assign('cat',$category);
				return $this->View->toString(APPLICATION."/admin/news_add.html");
			}else{
				$last_id = mysql_insert_id();
				require_once '../engines/Utility/phpthumb/ThumbLib.inc.php';
				$this->_imgNum = 1;
				$this->_img = array();
				$this->uploadImage($img1,'img1',$last_id);
				$this->uploadImage($img2,'img2',$last_id);
				$this->uploadImage($img3,'img3',$last_id);
				$this->uploadImage($img4,'img4',$last_id);
				$this->uploadImage($img5,'img5',$last_id);
				$this->inputImageData($last_id);
				return $this->View->showMessage('Berhasil', "index.php?s=".$this->_param);
			}
		}
		$category = $this->getCategoryList();
		$this->View->assign('cat',$category);
		return $this->View->toString(APPLICATION."/admin/news_add.html");
	}
	
	function addCalendar($req){
		if( $req->getPost('cmd') == 'add' ){
			
			$title 			= htmlspecialchars($req->getPost('title'));
			$location	= htmlspecialchars($req->getPost('location'));
			$date 			= $req->getPost('date');
			$start 			= $req->getPost('start');
			$end 			= $req->getPost('end');
			$detail 		= $req->getPost('detail');
			$status 		= $req->getPost('status');
			$kota 		= $req->getPost('kota');
			$band 		= $req->getPost('band');
			
			$img1=$this->imageInfo('img1');
			if( $title=='' || $location=='' || $date=='' || $detail=='' || $status=='' ){
				$this->View->assign('msg',"Please complete the form!");
				$band = $this->fetch("SELECT id,page_name band FROM social_pages WHERE n_status=1 ORDER BY page_name ASC;",1);
				$this->View->assign('band', $band);
				$propinsi = $this->fetch("SELECT id,provinceName name FROM province ORDER BY provinceName ASC;",1);
				$this->View->assign('propinsi', $propinsi);
				$kota = $this->fetch("SELECT id,cityName name FROM city WHERE provinceId=".$propinsi[0]['id']." ORDER BY cityName ASC;",1);
				$this->View->assign('kota', $kota);
				return $this->View->toString("News/admin/add_calendar.html");
			}
			if( !$this->query("INSERT INTO gm_article (category_id,title,location,date_time,start_time,end_time,detail,status,posted_date,page_id,city_id) VALUES (4,'$title','$location','$date','$start','$end','$detail','$status',NOW(),'$band','$kota')")){
				$this->View->assign("msg","Add process failure");
				$band = $this->fetch("SELECT id,page_name band FROM social_pages WHERE n_status=1 ORDER BY page_name ASC;",1);
				$this->View->assign('band', $band);
				$propinsi = $this->fetch("SELECT id,provinceName name FROM province ORDER BY provinceName ASC;",1);
				$this->View->assign('propinsi', $propinsi);
				$kota = $this->fetch("SELECT id,cityName name FROM city WHERE provinceId=".$propinsi[0]['id']." ORDER BY cityName ASC;",1);
				$this->View->assign('kota', $kota);
				return $this->View->toString("News/admin/add_calendar.html");
			}else{
				$last_id = mysql_insert_id();
				require_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
				$this->uploadImageCalendar($img1,'img1',$last_id);
				$this->inputImageDataCalendar($last_id,$img1);
				return $this->View->showMessage('Berhasil', "index.php?s=news");
			}
		}
		$band = $this->fetch("SELECT id,page_name band FROM social_pages WHERE n_status=1 ORDER BY page_name ASC;",1);
		$this->View->assign('band', $band);
		$propinsi = $this->fetch("SELECT id,provinceName name FROM province ORDER BY provinceName ASC;",1);
		$this->View->assign('propinsi', $propinsi);
		/*
		$kota = $this->fetch("SELECT id,cityName name FROM city WHERE provinceId=".$propinsi[0]['id']." ORDER BY cityName ASC;",1);
		$this->View->assign('kota', $kota);
		*/
		
		$kota = $this->fetch("SELECT id,cityName name FROM city ORDER BY cityName ASC;",1);
		$this->View->assign('kota', $kota);
		
		return $this->View->toString("News/admin/add_calendar.html");
	}
	
	function getCity($id){
		$kota = $this->fetch("SELECT id,cityName name FROM city WHERE provinceID=".$id." ORDER BY cityName ASC;",1);
		$data = array("kota" => $kota);
		echo json_encode( $data );
		exit;
	}
	
	function editCalendar($req){
		$id = $req->getParam('id');
		if( $req->getPost('cmd') == 'add' ){
			$title 		= htmlspecialchars($req->getPost('title'));
			$location	= htmlspecialchars($req->getPost('location'));
			$date 		= $req->getPost('date');
			$start 		= $req->getPost('start');
			$end 		= $req->getPost('end');
			$detail 		= $req->getPost('detail');
			$status 		= $req->getPost('status');
			$kota 		= $req->getPost('kota');
			$band 		= $req->getPost('band') == '' ? 0 : $req->getPost('band') ;
			
			$img1=$this->imageInfo('img1',183,0);
			if( $title=='' || $location=='' || $date=='' || $detail==''){
				$this->View->assign('msg',"Please complete the form!");
				$news = $this->fetch("SELECT * FROM gm_article WHERE id=$id");
				if( is_array($news) ){
					$this->View->assign("id",$news['id']);
					$this->View->assign("title",$news['title']);
					$this->View->assign("img",$news['img']);
					$this->View->assign("location",$news['location']);
					$this->View->assign("date",$news['date_time']);
					$s = substr( implode('',explode(':',$news['start_time'])), 0, 4);
					$e = substr(implode('',explode(':',$news['end_time'])), 0, 4);
					$this->View->assign("s$s","selected");
					$this->View->assign("e$e","selected");
					$this->View->assign("detail",$news['detail']);
					$this->View->assign("status",$news['status']);
					
					$prov = $this->fetch("SELECT provinceID FROM city WHERE id=".$news['city_id']." LIMIT 1");
					$this->View->assign("prov_id",$prov['provinceID']);
					$this->View->assign("band_id",$news['page_id']);
					$this->View->assign("city_id",$news['city_id']);
					$band = $this->fetch("SELECT id,page_name band FROM social_pages WHERE n_status=1 ORDER BY page_name ASC;",1);
					$this->View->assign('band', $band);
					$propinsi = $this->fetch("SELECT id,provinceName name FROM province ORDER BY provinceName ASC;",1);
					$this->View->assign('propinsi', $propinsi);
					$kota = $this->fetch("SELECT id,cityName name FROM city WHERE id=".$news['city_id']." ORDER BY cityName ASC;",1);
					$this->View->assign('kota', $kota);
					
					return $this->View->toString("News/admin/edit_calendar.html");
				}else{
					return $this->View->showMessage('Invalid news id', "index.php?s=news");
				}			
			}
			
			
			if( !$this->query("UPDATE gm_article SET title='$title', location='$location', date_time='$date',start_time='$start',end_time='$end',detail='$detail',status='$status',page_id='$band',city_id='$kota' WHERE id=$id; ")){
				$this->View->assign("msg","Add process failure");
				$news = $this->fetch("SELECT * FROM gm_article WHERE id=$id");
				if( is_array($news) ){
					$this->View->assign("id",$news['id']);
					$this->View->assign("title",$news['title']);
					$this->View->assign("img",$news['img']);
					$this->View->assign("location",$news['location']);
					$this->View->assign("date",$news['date_time']);
					$s = substr( implode('',explode(':',$news['start_time'])), 0, 4);
					$e = substr(implode('',explode(':',$news['end_time'])), 0, 4);
					$this->View->assign("s$s","selected");
					$this->View->assign("e$e","selected");
					$this->View->assign("detail",$news['detail']);
					$this->View->assign("status",$news['status']);
					
					$prov = $this->fetch("SELECT provinceID FROM city WHERE id=".$news['city_id']." LIMIT 1");
					$this->View->assign("prov_id",$prov['provinceID']);
					$this->View->assign("band_id",$news['page_id']);
					$this->View->assign("city_id",$news['city_id']);
					$band = $this->fetch("SELECT id,page_name band FROM social_pages WHERE n_status=1 ORDER BY page_name ASC;",1);
					$this->View->assign('band', $band);
					$propinsi = $this->fetch("SELECT id,provinceName name FROM province ORDER BY provinceName ASC;",1);
					$this->View->assign('propinsi', $propinsi);
					$kota = $this->fetch("SELECT id,cityName name FROM city WHERE id=".$news['city_id']." ORDER BY cityName ASC;",1);
					$this->View->assign('kota', $kota);
					
					return $this->View->toString("News/admin/edit_calendar.html");
				}else{
					return $this->View->showMessage('Invalid news id', "index.php?s=news");
				}
			}else{
				require_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
				if( $img1 ){
					$this->uploadImageCalendar($img1,'img1',$id);
					$this->inputImageDataCalendar($id,$img1);
				}
				return $this->View->showMessage('Berhasil', "index.php?s=news");
			}
		}
		
		$news = $this->fetch("SELECT * FROM gm_article WHERE id=$id");
		if( is_array($news) ){
			$this->View->assign("id",$news['id']);
			$this->View->assign("title",$news['title']);
			$this->View->assign("img",$news['img']);
			$this->View->assign("location",$news['location']);
			$this->View->assign("date",$news['date_time']);
			$s = substr( implode('',explode(':',$news['start_time'])), 0, 4);
			$e = substr(implode('',explode(':',$news['end_time'])), 0, 4);
			$this->View->assign("s$s","selected");
			$this->View->assign("e$e","selected");
			$this->View->assign("detail",$news['detail']);
			$this->View->assign("status",$news['status']);
			
			$prov = $this->fetch("SELECT provinceID FROM city WHERE id=".$news['city_id']." LIMIT 1");
			$this->View->assign("prov_id",$prov['provinceID']);
			$this->View->assign("band_id",$news['page_id']);
			$this->View->assign("city_id",$news['city_id']);
			$band = $this->fetch("SELECT id,page_name band FROM social_pages WHERE n_status=1 ORDER BY page_name ASC;",1);
			$this->View->assign('band', $band);
			$propinsi = $this->fetch("SELECT id,provinceName name FROM province ORDER BY provinceName ASC;",1);
			$this->View->assign('propinsi', $propinsi);
			/*
			$kota = $this->fetch("SELECT id,cityName name FROM city WHERE id=".$news['city_id']." ORDER BY cityName ASC;",1);
			$this->View->assign('kota', $kota);
			*/
			$kota = $this->fetch("SELECT id,cityName name FROM city ORDER BY cityName ASC;",1);
			$this->View->assign('kota', $kota);
			
			return $this->View->toString("News/admin/edit_calendar.html");
		}else{
			return $this->View->showMessage('Invalid news id', "index.php?s=news");
		}
	}
	
	function editNews($req){
		/* REMAKE BY BABAR FOR REDRUSH - 11 April 2012 */
		$id = $req->getParam('id');
		$news = $this->fetch("SELECT * FROM ".DB_PREFIX."_news WHERE id=$id");
		
		if( is_array($news) ){
			$edit = $req->getPost('edit');
			/* Update News*/
			if($edit == 1){
					$title = $req->getPost('title');
					$brief = $req->getPost('brief');
					$category = $req->getPost('category');
					$detail = $req->getPost('detail');
					$status = $req->getPost('status');
					// $id = $req->getPost('id');
					if($title=='' || $brief=='' || $category=='' || $detail=='' || $status=='' || $id==''){
						$msg = 'Please complete the form!';
					}else{
						if($_FILES['img1']['name']==''){
							$r = $this->query("UPDATE ".DB_PREFIX."_news SET title='".$title."', 
												posted_date=NOW() ,brief='".$brief."', video='".$video."',
												detail='".$detail."', status='".$status."'
									   WHERE id='".$id."'");
							return $this->View->showMessage('Success!', "index.php?s=news");
						}else{
							// primary images data
								$img_name = $_FILES['img1']['name'];
								$img_loc = $_FILES['img1']['tmp_name'];
								$img_type = $_FILES['img1']['type'];
								$img_newname = "news_".date('YmdHis');
								
								//declare extension
								if($img_type=='image/jpeg'){$ext = '.jpg';}
								if($img_type=='image/png'){$ext = '.png';}
								if($img_type=='image/gif'){$ext = '.gif';}
								
								$newfile = $img_newname.$ext; // new images name
								
								if($ext!='.jpg' && $ext!='.png' && $ext!='.gif'){
									$msg = "Invalid file type! (Allowed: *.jpg, *.gif, *.png)";//return false;
								}else{
									global $ENGINE_PATH;
									include_once $ENGINE_PATH."Utility/Thumbnail.php";	
									$thumb 	= new Thumbnail();
									$folder = "../public_html/contents/news/";
									$nfolder = "public_html/contents/news/";
									if(move_uploaded_file($img_loc,$folder.$newfile)){
										if($thumb->createThumbnail($folder.$newfile,$folder."thumb_".$newfile,64,64)){
											$r = $this->query("UPDATE ".DB_PREFIX."_news SET title='".$title."', 
												posted_date=NOW() ,brief='".$brief."', video='".$video."',
												detail='".$detail."', img='".$newfile."', status='".$status."'
												WHERE id='".$id."'");
											if($r){
												$msg = "Success!";
												
												$this->trash->execute($folder,$news['img'],$nfolder); // copy file ke trash
												$this->trash->execute($folder,"thumb_".$news['img'],$nfolder); // copy file ke trash
												@unlink($folder.$news['img']);
												@unlink($folder."thumb_".$news['img']);
												return $this->View->showMessage($msg, "index.php?s=news");
											}
											else{
												$err = mysql_error();
												$msg = "failed upload image! $err";
											}
											// return $this->View->showMessage($msg, "index.php?s=merchandise");
										}
										else{ 
												$msg = "failed processing the image"; 
											}
									}
									else{
										$msg = "failed move upload file";
									}
								}
						}
					}
				}
			
			$category = $this->getCategoryList();
			$this->View->assign('cat',$category);
			$this->View->assign("id",$news['id']);
			$this->View->assign("title",$news['title']);
			$this->View->assign("category",$news['category_id']);
			$this->View->assign("video",$news['video']);
			$this->View->assign("status",$news['status']);
			$this->View->assign("brief",$news['brief']);
			$this->View->assign("detail", $this->showTinyEditor( $news['detail'] ) );
			$this->View->assign('img',$news['img']);
			$this->View->assign('msg',$msg);
			return $this->View->toString(APPLICATION."/admin/news_edit.html");
		}else{
			return $this->View->showMessage('Invalid news id', "index.php?s=news");
		}
	}
	
	function editNewsAseli($req){
		$id = $req->getParam('id');
		if( $req->getPost('cmd') == 'edit' ){
			$id			= $req->getPost('id');
			$title 		= $req->getPost('title');
			$category 	= $req->getPost('category');
			$brief 		= $req->getPost('brief');
			$video 		= $req->getPost('video');
			$detail 	= $this->fixTinyEditor( $req->getPost('detail') );
			$status 	= $req->getPost('status');
			
			$img1=$this->imageInfo('img1');
			$img2=$this->imageInfo('img2');
			$img3=$this->imageInfo('img3');
			$img4=$this->imageInfo('img4');
			$img5=$this->imageInfo('img5');
			
			if( $title=='' || $category=='' || $brief=='' || $detail=='' || $status=='' ){
				$this->View->assign('msg',"Please complete the form!");
				$news = $this->fetch("SELECT * FROM ".DB_PREFIX."_news WHERE id=$id");
				if( is_array($news) ){
					$category = $this->getCategoryList();
					$this->View->assign('cat',$category);
					$this->View->assign("id",$news['id']);
					$this->View->assign("title",$news['title']);
					$this->View->assign("category",$news['category_id']);
					$this->View->assign("video",$news['video']);
					$this->View->assign("status",$news['status']);
					$this->View->assign("brief",$news['brief']);
					$this->View->assign("detail",$news['detail']);
					$img = unserialize($news['img']);
					foreach($img as $k => $v){
						$i = $k + 1;
						$this->View->assign("img$i",$news['id'] . '-' . $i . $v);
					}
					return $this->View->toString(APPLICATION."/admin/news_edit.html");
				}
			}
			/*$r=$this->query('UPDATE '.DB_PREFIX.'_news 
								SET title="'.$title.'", 
									category_id="'.$category.'", 
									brief="'.$brief.'",
									video="'.$video.'",
									detail="'.$detail.'",
									status="'.$status.'" 
								WHERE id='.$id);*/
			$r = $this->query("UPDATE ".DB_PREFIX."_news SET title='$title', posted_date=NOW() ,brief='$brief', video='$video',
															 detail='$detail', status='$status'
							   WHERE id='$id'");
			//return $this->View->showMessage('Berhasil', "index.php?s=".$this->_param);
			//echo "dlashdas     ".'UPDATE gm_article SET title="'.$title.'", category_id="'.$category.'",brief="'.$brief.'",video="'.$video.'",detail="'.$detail.'",status="'.$status.'" WHERE id='.$id;
			//print mysql_error();
			//exit;
			if(!$r){
				//echo $detail . "<hr />";
				//print mysql_error();
				//exit;
				$this->View->assign("msg","Edit process failure");
				$news = $this->fetch("SELECT * FROM ".DB_PREFIX."_news WHERE id=$id");
				if( is_array($news) ){
					$category = $this->getCategoryList();
					$this->View->assign('cat',$category);
					$this->View->assign("id",$news['id']);
					$this->View->assign("title",$news['title']);
					$this->View->assign("category",$news['category_id']);
					$this->View->assign("video",$news['video']);
					$this->View->assign("status",$news['status']);
					$this->View->assign("brief",$news['brief']);
					$this->View->assign("detail",$news['detail']);
					$img = unserialize($news['img']);
					foreach($img as $k => $v){
						$i = $k + 1;
						$this->View->assign("img$i",$news['id'] . '-' . $i . $v);
					}
					return $this->View->toString(APPLICATION."/admin/news_edit.html");
				}
			}else{
				require_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
				$news = $this->fetch("SELECT * FROM ".DB_PREFIX."_news WHERE id=$id");
				$this->_img = unserialize($news['img']);
				
				$this->_imgNum = count($this->_img);
				if( $this->_imgNum >= 1 ) { $this->uploadImageEdit($img1,'img1',$id, 1); }else{ $this->_imgNum = $this->_imgNum + 1; $this->uploadImage($img1,'img1',$id); }
				if( $this->_imgNum >= 2 ) { $this->uploadImageEdit($img2,'img2',$id, 2); }else{ $this->_imgNum = $this->_imgNum + 1; $this->uploadImage($img2,'img2',$id); }
				if( $this->_imgNum >= 3 ) { $this->uploadImageEdit($img3,'img3',$id, 3); }else{ $this->_imgNum = $this->_imgNum + 1; $this->uploadImage($img3,'img3',$id); }
				if( $this->_imgNum >= 4 ) { $this->uploadImageEdit($img4,'img4',$id, 4); }else{ $this->_imgNum = $this->_imgNum + 1; $this->uploadImage($img4,'img4',$id); }
				if( $this->_imgNum >= 5 ) { $this->uploadImageEdit($img5,'img5',$id, 5); }else{ $this->_imgNum = $this->_imgNum + 1; $this->uploadImage($img5,'img5',$id); }
				$this->inputImageData($id);
				//return $this->View->showMessage('Berhasil', "index.php?s=news");
			}
			return $this->View->showMessage('Berhasil', "index.php?s=".$this->_param);
		}
		
		$news = $this->fetch("SELECT * FROM ".DB_PREFIX."_news WHERE id=$id");
		if( is_array($news) ){
			$category = $this->getCategoryList();
			$this->View->assign('cat',$category);
			$this->View->assign("id",$news['id']);
			$this->View->assign("title",$news['title']);
			$this->View->assign("category",$news['category_id']);
			$this->View->assign("video",$news['video']);
			$this->View->assign("status",$news['status']);
			$this->View->assign("brief",$news['brief']);
			$this->View->assign("detail", $this->showTinyEditor( $news['detail'] ) );
			$img = unserialize($news['img']);
			foreach($img as $k => $v){
				$i = $k + 1;
				$this->View->assign("img$i",$news['id'] . '-' . $i . $v);
			}
			return $this->View->toString(APPLICATION."/admin/news_edit.html");
		}else{
			return $this->View->showMessage('Invalid news id', "index.php?s=news");
		}
	}
	
	function featuredNews(){
		$f = $this->Request->getParam('f');
		$id = $this->Request->getParam('id');
		if($id!=''){
			$q = "UPDATE ".DB_PREFIX."_news SET featured='".$f."' WHERE id='".$id."'";
			$this->open(0);
			$this->fetch($q);
			$this->close();
			return $this->View->showMessage('Success edit news!', "index.php?s=news");
		}
		else{
			return $this->View->showMessage('Invalid news id', "index.php?s=news");
		}
	}
	
	function deleteNews($req){
		$id = $req->getParam('id');
		if( !$this->query("DELETE FROM ".DB_PREFIX."_news WHERE id=$id")){
			return $this->View->showMessage('Gagal', "index.php?s=".$this->_param);
		}else{
			return $this->View->showMessage('Berhasil', "index.php?s=".$this->_param);
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
	function getCategoryList(){
		$category = $this->fetch("SELECT * FROM ".DB_PREFIX."_news_categories",1);
		return $category;
	}
	function imageInfo($img, $w=290, $h=290){
			if( $_FILES[$img]['size'] > 0 ){
				list($width, $height, $type, $attr) = getimagesize( $_FILES[$img]['tmp_name'] );
				if( $_FILES[$img]['error'] > 0 ){
					$this->_msg = "broken images file!";
					return false;
				}else{
					if( $width < $w || $height < $h ){
						$this->_msg = "Images resolution minimum must be 290x290!";
						return false;
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
						
						return $ext;
						
					}else{
						$this->_msg="Only JPG, GIF, PNG dan BMP is allowed";
						return false;
					}
				}
			}else{
				$this->_msg = "broken images file!";
			}
			return false;
	}
	function uploadImage($ext,$files,$name){
		global $_FILES;
			if($ext){
				try{ $thumb = PhpThumbFactory::create( $_FILES[$files]['tmp_name'] );	}catch (Exception $e){}
				$thumb->AdaptiveResize(290, 290);
				if( ! is_dir( '../public_html/contents/' ) ){
					mkdir( '../public_html/contents/' );
				}
				if( ! is_dir( '../public_html/contents/news/' ) ){
					mkdir( '../public_html/contents/news/' );
				}
				if( ! is_dir( '../public_html/contents/news/thumb/' ) ){
					mkdir( '../public_html/contents/news/thumb/' );
				}
				if( ! is_dir( '../public_html/contents/news/big/' ) ){
					mkdir( '../public_html/contents/news/big/' );
				}
				$thumb->save( "../public_html/contents/news/". $name .'-'. $this->_imgNum . $ext );
				$thumb->AdaptiveResize(50,50);
				$thumb->save( "../public_html/contents/news/thumb/". $name .'-'. $this->_imgNum . $ext );
				
				@move_uploaded_file($_FILES[$files]['tmp_name'], "../public_html/contents/news/big/". $name .'-'. $this->_imgNum . $ext);
				
				$this->_img[$this->_imgNum - 1] = $ext;
				$this->_imgNum = $this->_imgNum + 1;
			}
	}
	function uploadImageCalendar($ext,$files,$name,$path='../contents/'){
		global $_FILES;
			if($ext){
				try{ $thumb = PhpThumbFactory::create( $_FILES[$files]['tmp_name'] );	}catch (Exception $e){}
				$thumb->resize(183,0);
				if( ! is_dir( $path ) ){
					mkdir( $path );
				}
				if( ! is_dir( $path.'news/' ) ){
					mkdir( $path.'news/' );
				}
				$thumb->save( $path."news/". $name . $ext );
			}
	}
	function uploadImageEdit($ext,$files,$name,$pos){
		global $_FILES;
			if($ext){
				try{ $thumb = PhpThumbFactory::create( $_FILES[$files]['tmp_name'] );	}catch (Exception $e){}
				$thumb->AdaptiveResize(290, 290);
				if( ! is_dir( '../contents/' ) ){
					mkdir( '../contents/' );
				}
				if( ! is_dir( '../contents/news/' ) ){
					mkdir( '../contents/news/' );
				}
				if( ! is_dir( '../contents/news/thumb/' ) ){
					mkdir( '../contents/news/thumb/' );
				}
				if( ! is_dir( '../contents/news/big/' ) ){
					mkdir( '../contents/news/big/' );
				}
				$thumb->save( "../contents/news/". $name .'-'. $pos . $ext );
				$thumb->AdaptiveResize(50,50);
				$thumb->save( "../contents/news/thumb/". $name .'-'. $pos . $ext );
				
				@move_uploaded_file($_FILES[$files]['tmp_name'], "../contents/news/big/". $name .'-'. $pos . $ext);
				
				$this->_img[$pos - 1] = $ext;
			}
	}
	function inputImageData($id){
		$img = serialize( $this->_img );
		$this->query("UPDATE ".DB_PREFIX."_news SET img='$img' WHERE id=$id;");
	}
	function inputImageDataCalendar($id,$ext){
		$this->query("UPDATE gm_article SET img='$ext' WHERE id=$id;");
	}
	
	function userAddCalendar($post,$files){
		if( $post['cmd'] == 'add' ){
			if( $post['edit'] ){
				$id = $post['edit'];
			}
			$title 		= $post['title'];
			$location		= $post['location'];
			$d = explode(' ',$post['date_time']);
			$date 		= $d[2] . '-' . $d[1] . '-' . $d[0];
			$start 		= $post['start'];
			$end 		= $post['end'];
			$detail 		= $post['detail'];
			$status 		= 0;
			$kota 		= intval($post['kota']);
			$band 		= $post['band'];
			
			$img1=$this->imageInfo('img1',183,0);
			
			if( $title=='' || $date=='' || $detail=='' || $band =='' || $location =='' ){
				$msg = 'Formulir belum lengkap!';
				return $this->View->showMessage($msg, "index.php?band=1");
			}
			if( $post['edit'] ){
				$que = "UPDATE gm_article SET title='$title', date_time='$date', detail='$detail', status='0', location='$location', start_time='$start', end_time='$end', city_id=$kota WHERE id=$id;";
			}else{
				$que = "INSERT INTO gm_article (category_id,title,date_time,detail,status,posted_date,page_id,location,start_time,end_time,city_id) VALUES (4,'$title','$date','$detail','$status',NOW(),'$band','$location','$start','$end',$kota)";
			}
			$this->open(0);
			if( !$this->query($que) ){
				$msg = 'Gagal menambahkan event, silakan coba lagi!';
				return $this->View->showMessage($msg, "index.php?band=1");
			}else{
				$last_id = mysql_insert_id();
				require_once '../engines/Utility/phpthumb/ThumbLib.inc.php';
				if( $post['edit'] ){
					if($img1){
						$this->uploadImageCalendar($img1,'img1',$id,'contents/');
						$this->inputImageDataCalendar($id,$img1);
					}
					$msg = 'Terimakasih, hasil edit event kamu sedang dalam proses moderasi.';
					
				}else{
					$this->uploadImageCalendar($img1,'img1',$last_id,'contents/');
					$this->inputImageDataCalendar($last_id,$img1);
					$msg = 'Terima kasih telah menambah event, event kamu sedang dalam proses moderasi.';
				}
				return $this->View->showMessage($msg, "index.php?band=1");
			}
			$this->close();
		}
		return $this->View->showMessage("Formulir belum lengkap!", "index.php?band=1");
	}
	
	function fixTinyEditor($content){
		global $CONFIG;
		$content = str_replace("\\r\\n","",$content);
		$content = htmlspecialchars(stripslashes($content), ENT_QUOTES);
		$content = str_replace("../contents", "contents", $content);
		//$content = htmlspecialchars( stripslashes($content) );
		$content = str_replace("&lt;", "<", $content);
		$content = str_replace("&gt;", ">", $content);
		return $content;
	}
	function showTinyEditor($content){
		global $CONFIG;
		$content = str_replace("contents/", "../contents/", $content);
		return $content;
	}
}
?>
