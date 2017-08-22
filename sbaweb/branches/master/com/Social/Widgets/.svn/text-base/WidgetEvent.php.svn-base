<?php
global $ENGINE_PATH,$APP_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once $APP_PATH."Social/NewsHelper.php";
class WidgetEvent extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $_template = '';
	var $_userid = 0;
	var $_userba = 0;
	var $news;
	var $msg = '';
	var $alert = false;
	function __construct($req, $user_id=0, $user_type=0){
		$this->Request = $req;
		$this->news = new NewsHelper($req);
		$this->_userid = $user_id;
		$this->_userba = $user_type;
		$this->View = new BasicView();
		$this->run();
	}
	
	function run(){
		$list = $this->getLatestEvent();
		$this->View->assign("list",$list);
	}
	
	function getLatestEvent($total=2){
		
		//$sql = "SELECT * FROM events WHERE n_status=1 && tanggal_event > NOW() ORDER BY tanggal_event ASC LIMIT $total;";
		//$sql = "SELECT * FROM events WHERE n_status=1 ORDER BY tanggal_event DESC LIMIT $total;";
		$sql = "SELECT * FROM events WHERE n_status=1 
		AND MONTH(tanggal_event) = MONTH(NOW()) 
		ORDER BY tanggal_event DESC LIMIT $total;";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		
		$num = count($rs);
		if($num <= 0){
			$sql = "SELECT * FROM events WHERE n_status=1 ORDER BY tanggal_event DESC LIMIT $total;";
			$rs = $this->fetch($sql,1);
			$num = count($rs);
		}
		
		for($i=0;$i<$num;$i++){
			$chars = 200;  
			$mytext = substr($rs[$i]['deskripsi'],0,$chars);  
			$mytext = substr($mytext,0,strrpos($mytext,' '));
			$rs[$i]['deskripsi'] = $mytext;
				
			$tgl = array();
			$tgl = explode(' ', $rs[$i]['tanggal_event']);
			$tgl = explode('-', $tgl[0]);
				
			$tgl = date("j F Y", mktime(0, 0, 0, $tgl[1], $tgl[2], $tgl[0]));
			$rs[$i]['tanggal_event'] = $tgl;	
		}
		//$this->View->assign("list",$rs);
		$this->_template = "latest_event";
		$this->close();
		return $rs;
	}
	
	function getListEvent($total_per_page=4){
		//echo "hdlfhldhf";
		//exit;
		
		$this->open(0);
		$start = $this->Request ->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT count(*) total FROM events WHERE n_status=1");
		$total = $r['total'];
		$qry = "SELECT 
						e.*,
						IF(e.user_id=0,'Admin',m.name) name,
						IF( c.comments IS NULL, 0, c.comments) comments,
						l.suka
					FROM 
						events e
						LEFT JOIN ( SELECT COUNT(id) comments, event_id FROM events_comments WHERE n_status=1 GROUP BY event_id ) c
						ON c.event_id=e.id
						LEFT JOIN ( SELECT event_id, COUNT(*) AS suka FROM events_like GROUP BY event_id ) l
						ON l.event_id=e.id
						LEFT JOIN social_member m
						ON m.id=e.user_id
					WHERE 
						e.n_status=1 
					ORDER BY 
						e.posted_date DESC
					LIMIT 
						$start,$total_per_page;";
		
		$list = $this->fetch($qry, 1);
		$num = count($list);
		for($i=0;$i<$num;$i++){
			$chars = 300;  
			$mytext = substr($list[$i]['deskripsi'],0,$chars);  
			$list[$i]['deskripsi'] = $mytext;
		}
		
		$this->View->assign("userid", $this->_userid);
		$this->View->assign("list",$list);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?events=1"));
		$this->close();
		$this->View->assign("user_ba",$this->_userba);
		$this->_template = "event_list";
	}
	
	function getEvent($id){
		$sql = "SELECT * FROM events WHERE id=$id && n_status=1;";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$tgl = explode(' ', $rs[0]['tanggal_event']);
		$tgl = explode('-', $tgl[0]);
		$tgl = date("j F Y", mktime(0, 0, 0, $tgl[1], $tgl[2], $tgl[0]));
		$this->View->assign("tanggal",$tgl);
		$this->View->assign("id",$rs[0]['id']);
		$this->View->assign("ext",$rs[0]['image_ext']);
		$this->View->assign("judul",$rs[0]['nama_event']);
		$this->View->assign("event_id",$rs[0]['id']);
		$this->View->assign("isi",$rs[0]['deskripsi']);
		$this->close();
		
		$sql = "SELECT *,b.name,b.img,b.small_img FROM events_comments a
				INNER JOIN social_member b
				ON a.user_id = b.id 
				WHERE a.n_status=1 AND a.event_id=$id 
				ORDER BY posted_date ASC;";
		$this->open(0);
		$com = $this->fetch($sql,1);
		$this->close();
		$num_com = count( $com );
		$this->View->assign("com",$com);
		$this->View->assign("num_com",$num_com);
		
		$this->View->assign("userid", $this->_userid);
		$this->View->assign("event_userid",$rs[0]['user_id']);
		
		$this->_template = "event_detail";
	}
	
	function likeEvent( $id ){
		
		$id = intval($id);
		$this->open(0);
		$r = $this->fetch("SELECT * FROM social_member WHERE id=". $this->_userid);
		$r2 = $this->fetch("SELECT * FROM events WHERE id=".$id." LIMIT 1");
		$this->close();
		$msg = "<a href='index.php?profile=1&profile_id=". $this->_userid ."'>".$r['name']."</a> likes  '<a href='index.php?events=1&id=".$id."'>".$r2['nama_event']."</a>'";
		$this->news->send($this->_userid,$msg);
		
		
		$this->open(0);
		$has=$this->fetch("SELECT count(*) total FROM events_like WHERE event_id=$id && user_id=".$this->_userid.";");
		//echo $has['total'];exit;
		if(intval($has['total']) == 0){
			if( !$this->query("INSERT INTO events_like (event_id, user_id, like_date) values ($id, ". $this->_userid .", NOW())")){
				$this->close();
				echo 0;
			}else{
				global $tracker;
				$tracker->doTrack(0,$this->_userid, 1, 1, "index.php?events=1&id=".$id);
				
				$like=$this->fetch("SELECT COUNT(*) total FROM events_like WHERE event_id=$id;");
				$this->close();
				echo $like['total'];
			}
		}else{
			echo 2;
		}
		exit;
	}
	
	function unlikeEvent( $id ){
		$this->open(0);
		if( !$this->query("DELETE FROM events_like WHERE event_id=$id && user_id=".$this->_userid)){
			$this->close();
			echo 0;
		}else{
			$this->close();
			echo 1;
		}
		exit;
	}
	
	function hideComment( $id ){
		$this->open(0);
		if( !$this->query("UPDATE events_comments SET n_status=0 WHERE id=$id")){
			$this->close();
			echo 0;
		}else{
			$this->close();
			echo 1;
		}
		exit;
	}
	
	function addEvent(){
		
		if( $this->Request->getPost('title') != null && $this->_userba == 1){
			require_once '../engines/Utility/phpthumb/ThumbLib.inc.php';
			$with_img = false;
			$img_err = false;
			if( $_FILES['img']['size'] > 0 ){
				$with_img = true;
				list($width, $height, $type, $attr) = getimagesize( $_FILES['img']['tmp_name'] );
				if( $_FILES['img']['error'] != 0 ){
					$img_err = true;
					$this->View->assign("sure","no");
					$this->View->assign("msg","FIle gambar rusak");
				}
				if( $width < 110 || $height < 120 ){
					$img_err = true;
					$this->View->assign("sure","no");
					$this->View->assign("msg","Resolusi gambar terlalu kecil, minimal 110x120.");
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
					$this->View->assign("sure","no");
					$this->View->assign("msg","Hanya file JPG, GIF, PNG dan BMP yang diperbolehkan");
				}				
			}
			
			if( !$img_err ){
				$title = $this->Request->getPost('title');
				$sum = htmlspecialchars(stripslashes( $this->Request->getPost('sum')),ENT_QUOTES);
				$desc = $this->fixTinyEditor( $this->Request->getPost('desc') );
				$date = $this->Request->getPost('date') . " " . $this->Request->getPost('time') . ':00'; 
				$post_date = date('Y-m-d H:i:s');
				$att = ( $this->Request->getPost('attend') == '' ) ? 0 : $this->Request->getPost('attend') ;
				$qry = "INSERT INTO events 
								(user_id, tanggal_event, posted_date, nama_event, deskripsi, attendants, summary)
							VALUES
								(".$this->_userid.", '$date', '$post_date', '$title', '$desc', '$att', '$sum');";
				$this->open(0);
				if( !$this->query($qry)){
					$this->close();
					$this->View->assign("sukses","no");
					$this->View->assign("msg","Tambah event gagal");
				}else{
					$this->close();
					$this->open(0);
					$r = $this->fetch("SELECT * FROM social_member WHERE id=". $this->_userid);
					$this->close();
					$msg = "<a href='index.php?profile=1&profile_id=". $this->_userid ."'>".$r['name']."</a> add new event";
					$this->news->send($this->_userid,$msg);
					$this->View->assign("sukses","yes");
					
				}
				
				if( $with_img ){
				
					$sql = "SELECT id FROM events WHERE user_id=".$this->_userid." && posted_date='$post_date' && nama_event='$title';";
					$this->open(0);
					$r = $this->fetch($sql);
					$this->close();
					try{
						 $thumb = PhpThumbFactory::create( $_FILES['img']['tmp_name'] );
					}catch (Exception $e){
						 // handle error here however you'd like
					}
					
					$thumb->adaptiveResize(110, 120);
					
					if( $thumb->save( "contents/events/". $r['id'] . $ext ) ){
						@move_uploaded_file( $_FILES['img']['tmp_name'], "contents/events/big_". $r['id'] . $ext );
						$this->open(0);
						$qry = "UPDATE events SET image_ext='$ext' WHERE id=". $r['id'];
						if( !$this->query($qry)){
							$this->close();
						}else{
							$this->close();
						}
					}else{
						//jika resize image bermasalah
					}
					$this->msg = "Event berhasil disimpan";
					$this->alert = true;
					
					global $tracker;
					$tracker->doTrack(0,$this->_userid, 4, 5, "index.php?events=1&id=".$r['id']);
				}else{
					$this->msg = "Event berhasil disimpan";
					$this->alert = true;
					
					global $tracker;
					$tracker->doTrack(0,$this->_userid, 4, 5, "index.php?events=1&id=".$r['id']);
				}
			}
		}
		$this->_template = "event_add";
	}
	
	function addComment( $id, $text ){
		$this->open(0);
		$r = $this->fetch("SELECT * FROM social_member WHERE id=". $this->_userid);
		$r2 = $this->fetch("SELECT * FROM events WHERE id=".$id." LIMIT 1");
		$this->close();
		$msg = "<a href='index.php?profile=1&profile_id=". $this->_userid ."'>".$r['name']."</a> commenting  '<a href='index.php?events=1&id=".$id."'>".$r2['nama_event']."</a>'";
		$this->news->send($this->_userid,$msg);
		$this->open(0);
		
		//$qry = "INSERT INTO events_comments (event_id, user_id, posted_date, comments) values ($id, ". $this->_userid .", NOW(), '$text')"; //kalo mau dimoderasi dulu
		$qry = "INSERT INTO events_comments (event_id, user_id, posted_date, comments, n_status) values ($id, ". $this->_userid .", NOW(), '$text', '1')"; //kalo ngga mau dimoderasi
		
		if( !$this->query($qry)){
			$this->close();
			echo 0;
		}else{
			$this->close();
			echo 1;
		}
		exit;
	}
	
	function fixTinyEditor($content){
		$content = str_replace("\\r\\n","",$content);
		$content = stripslashes( $content );
		$content = str_replace("&lt;", "<", $content);
		$content = str_replace("&gt;", ">", $content);
		return $content;
	}
	
	function __toString(){
		if( $this->alert ){
			return $this->View->showMessage($this->msg, "index.php?events=1");	
		}else{
			return $this->View->toString("Social/widgets/".$this->_template.".html");
		}
	}
	
}
?>
