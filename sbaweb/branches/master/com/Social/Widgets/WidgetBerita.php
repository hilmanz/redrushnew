<?php
global $ENGINE_PATH,$APP_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once $APP_PATH."Social/NewsHelper.php";
class WidgetBerita extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $_template="berita_terbaru";
	var $_userid = 0;
	var $_userba = 0;
	function __construct($req, $user_id=0, $user_type=0){
		$this->Request = $req;
		$this->_userid = $user_id;
		$this->_userba = $user_type;
		$this->View = new BasicView();
		$this->news = new NewsHelper($req);
		$this->run();
	}
	function run(){
		if( $this->param('news') == 1 && $this->param('id') == null){
			$this->_template = 'berita_list';
		}else if( $this->param('news') == 1 && $this->param('id') != null){
			$this->_template = 'berita_detail';
		}
		$list = $this->getLatestNews(3);
		//some logic lagi
		//--->
		
		//assign hasilnya ke template.
		$this->View->assign("list",$list);
		
		
		//--> we're done :)
	}
	function getLatestNews($total=2){
		$total = intval($total);
		$sql = "SELECT * FROM gm_article ORDER BY posted_date DESC LIMIT $total";
		
		$this->open(0);
		$list = $this->fetch($sql,1);
		$this->close();
		return $list;
	}
	
	function getListNews($total_per_page=4){
		$this->open(0);
		$start = $this->Request ->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT * FROM gm_article", 1);
		$total = count( $r );
		$qry = "SELECT 
						e.*,
						IF( c.comments IS NULL, 0, c.comments) comments,
						l.suka 	 
					FROM 
						gm_article e
						LEFT JOIN ( SELECT COUNT(id) comments, article_id FROM gm_article_comments WHERE n_status=1 GROUP BY article_id ) c
						ON c.article_id=e.id
						LEFT JOIN ( SELECT article_id, count(*) as suka FROM gm_article_like GROUP BY article_id ) l
						ON e.id=l.article_id 
					ORDER BY 
						e.posted_date DESC
					LIMIT 
						$start,$total_per_page;";
		$list = $this->fetch($qry, 1);
		
		//print_r($list); exit;
		$this->View->assign("userid", $this->_userid);
		$this->View->assign("list",$list);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?news=1"));
		$this->close();
		$this->View->assign("user_ba",$this->_userba);
		$this->_template = "berita_list";
	}
	
	function getNews($id){
		$id = intval($id);
		$sql = "SELECT * FROM gm_article WHERE id=$id LIMIT 1";
		$this->open(0);
		$rs = $this->fetch($sql);
		
		$this->close();
		$tgl = explode(' ', $rs['posted_date']);
		$tgl = explode('-', $tgl[0]);
		$tgl = date("j F Y", mktime(0, 0, 0, $tgl[1], $tgl[2], intval($tgl[0])));
		$this->View->assign("tanggal",$tgl);
		$this->View->assign("judul",$rs['title']);
		$this->View->assign("news_id",$rs['id']);
		$this->View->assign("isi",$rs['detail']);
		$this->View->assign("img",$rs['img']);
		
		
		$sql = "SELECT * FROM gm_article_comments c LEFT JOIN social_member m
ON c.user_id=m.id WHERE c.article_id=$id && c.n_status=1 ORDER BY posted_date ASC LIMIT 20";
		//echo $sql . '<hr />';
		//exit;
		$this->open(0);
		$com = $this->fetch($sql,1);
		$num_com = count( $com );
		$this->View->assign("com",$com);
		$this->View->assign("num_com",$num_com);
		
		$this->View->assign("userid", $this->_userid);
		$this->View->assign("event_userid",$com[0]['user_id']);
		$this->close();
		$this->_template = "berita_detail";
		//exit;
	}
	
	function addComment( $id, $text ){
		global $tracker;
		$this->open(0);
		$r = $this->fetch("SELECT * FROM social_member WHERE id=". $this->_userid);
		$r2 = $this->fetch("SELECT * FROM gm_article WHERE id=".$id." LIMIT 1");
		$this->close();
		$msg = "<a href='index.php?profile=1&profile_id=". $this->_userid ."'>".$r['name']."</a> commenting  '<a href='index.php?news=1&id=".$id."'>".$r2['title']."</a>'";
		$this->news->send($this->_userid,$msg);
		
		$this->open(0);
		//$qry = "INSERT INTO gm_article_comments (article_id, user_id, posted_date, comments, n_status) values ($id, ". $this->_userid .", NOW(), '$text', 0)";  //status dibikin 0 biar dimoderasi dulu
		$qry = "INSERT INTO gm_article_comments (article_id, user_id, posted_date, comments, n_status) values ($id, ". $this->_userid .", NOW(), '$text', 1)"; //status dibikin 1, tanpa moderasi
		if( !$this->query($qry)){
			$this->close();
			echo 0;
		}else{
			$this->close();
			$tracker->doTrack(0,$this->_userid, 0, 2, "index.php?news=1&id=".$id);
			echo 1;
		}
		exit;
	}
	
	function likeNews( $id ){
		$this->open(0);
		$r = $this->fetch("SELECT * FROM social_member WHERE id=". $this->_userid);
		$r2 = $this->fetch("SELECT * FROM gm_article WHERE id=".$id." LIMIT 1");
		$this->close();
		$msg = "<a href='index.php?profile=1&profile_id=". $this->_userid ."'>".$r['name']."</a> likes  '<a href='index.php?news=1&id=".$id."'>".$r2['title']."</a>'";
		$this->news->send($this->_userid,$msg);
		
		$this->open(0);
		if( !$this->query("INSERT INTO gm_article_like (article_id, user_id, like_date) values ($id, ". $this->_userid .", NOW())")){
			$this->close();
			echo 0;
		}else{
			$this->close();
			global $tracker;
			$tracker->doTrack(0,$this->_userid, 1, 1, "index.php?news=1&id=".$id);
			echo 1;
		}
		exit;
	}
	
	function unlikeNews( $id ){
		$this->open(0);
		if( !$this->query("DELETE FROM gm_article_like WHERE article_id=$id && user_id=".$this->_userid)){
			$this->close();
			echo 0;
		}else{
			$this->close();
			echo 1;
		}
		exit;
	}
	
	function __toString(){
		
		return $this->View->toString("Social/widgets/".$this->_template.".html");
	}
	
}
?>