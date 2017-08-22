<?php
global $APP_PATH;
include_once $APP_PATH . "Application.php";
include_once $APP_PATH . "Social/NewsHelper.php";
class ModelGallery extends SQLData{
	var $news;
	function __construct($req){
		$this->news = new NewsHelper($req);
	}
	function getLatestNews($total=10){
		$sql = "SELECT * FROM coba_news ORDER BY id DESC LIMIT 10";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	function getNews($id){
		$id = mysql_escape_string($id);
		$id = intval($id);
		$sql = "SELECT * FROM coba_news WHERE id=".$id." LIMIT 1";
		$this->open(0);
		$rs = $this->fetch($sql);
		$this->close();
		return $rs;
	}
	
	function deleteallpic($id){
		$sql="DELETE FROM social_photo WHERE album_id='".$id."'";
		$this->open(0);
		$rs= $this->query($sql);
		$this->close();
		return $rs;
	}
	
	function deletealbum($id){
		$sql="DELETE FROM social_albums WHERE id='".$id."'";
		$this->open(0);
		$rs = $this->query($sql);
		$this->close();
		return $rs;
	}
	
	function selectdelpic($id){
		$sql=
		"SELECT * FROM social_photo WHERE id='".$id."' LIMIT 1";
		$this->open(0);
		$rs=$this->fetch($sql);
		print mysql_error();
		$this->close();
		return $rs;	
	}
	
	function getGalleryDetail($id){
		$sql="SELECT album_id,caption,img,img_thumb,owner_id,b.id 
		FROM social_albums b INNER JOIN 
		(SELECT * FROM social_photo WHERE id='".$id."' ) a ON b.id=a.album_id LIMIT 1";
		
		$this->open(0);
		$rs=$this->fetch($sql);
		print mysql_error();
		$this->close();
		return $rs;
	}
	
	function deletepic($id){
		$sql=
		"DELETE FROM social_photo WHERE id='".$id."' ";
		$this->open(0);
		$rs=$this->query($sql);
		print mysql_error();
		$this->close();
		return $rs;	
	}
	
	function getAlbumDetail($album_id){
		$sql="SELECT * FROM social_albums WHERE id='".$album_id."' LIMIT 1";
		$this->open(0);
		$rs=$this->fetch($sql);
		$this->close();
		return $rs;
	}
	
	function getlist(){
		$sql="SELECT * FROM social_albums ORDER BY id ASC";
		$this->open(0);
		$rs=$this->fetch($sql,1);
		print mysql_error();
		$this->close();
		return $rs;
	}
	
	function getAllAlbumshidden($user_id,$start,$totals){
	/*	$sql="SELECT * FROM social_albums 
		WHERE published=0 
		AND owner_id='".$user_id."' 
		LIMIT $start,$totals";*/
		
		$sql="SELECT
		a.`name`,
  		b.`id`,
  		b.`album_name`,
  		b.`created_date`,
	  	b.`owner_id`,
  		b.`owner_type`,
  		b.`published`
		FROM social_albums AS b INNER JOIN social_member AS a ON a.id=b.owner_id WHERE owner_id='".$user_id."' ORDER BY b.id 
		LIMIT $start,$totals";
		$this->open(0);
		$qr=$this->fetch($sql,1);
		$this->close();
		return $qr;
	}
	
	function getAllAlbums($start,$total_per_page){
	/*	$sql=
		"SELECT * 
		FROM social_albums 
		WHERE published=1 
		ORDER BY id 
		LIMIT $start,$total_per_page";*/
		
		$sql="SELECT
		a.`name`,
  		b.`id`,
  		b.`album_name`,
  		b.`created_date`,
	  	b.`owner_id`,
  		b.`owner_type`,
  		b.`published`
		FROM social_albums AS b INNER JOIN social_member AS a ON a.id=b.owner_id WHERE b.published=1 
		ORDER BY b.id DESC
		LIMIT $start,$total_per_page";
		$this->open(0);
		$rs=$this->fetch($sql,1);
		print mysql_error();
		$this->close();
		return $rs;
	}
	
	function takePic($album_id){
		$sql="SELECT * FROM social_photo WHERE album_id='".$album_id."' LIMIT 1";
		$this->open(0);
		$rs=$this->fetch($sql);
		print mysql_error();
		$this->close();
		return $rs["img_thumb"];
	}
	
	function takealbumpic($album_id){
		$sql="SELECT * FROM social_photo WHERE album_id='".$album_id."' AND n_status=1 LIMIT 1";
		$this->open(0);
		$rs=$this->fetch($sql);
		print mysql_error();
		$this->close();
		return $rs["img_thumb"];
	}
	
	function getAlbum(){
		$sql=
		"SELECT * FROM social_albums a
        INNER JOIN (SELECT album_id,img,img_thumb FROM social_photo  GROUP BY album_id
        ) b
        ON a.id = b.album_id WHERE published='1'
        LIMIT 10";
		$this->open(0);
		$rs=$this->fetch($sql,1);
		print mysql_error();
		$this->close();
		return $rs;
	}
	
	function get12photos(){
		$sql="SELECT * FROM social_photo ORDER BY id ASC LIMIT 12";
		$this->open(0);
		$rs=$this->fetch($sql,1);
		print mysql_error();
		$this->close();
		return $rs;
	}
	
	function getId(){
		$sql="SELECT id FROM social_albums";
		$this->open(0);
		$quer=$this->fetch($sql,1);
		print mysql_error();
		$this->close();
		return $quer;
	}
	
	function getThumbAlbum(){
	$sql=
	"SELECT * FROM social_albums a
        INNER JOIN (SELECT album_id,img,img_thumb FROM social_photo GROUP BY album_id
        ) b
        ON a.id = b.album_id
        LIMIT 10";
	$this->open(0);
	$qr=$this->fetch($sql,1);
	print mysql_error();
	$this->close();
	return $qr;
	}
	
	function get_user($album_id){
		$sql="SELECT * FROM social_albums WHERE id='".$album_id."'";
		$this->open(0);
		$rs= $this->fetch($sql);
		print mysql_error();
		$this->close();
		return $rs;
	}
	
	function getAllGallery($start,$total_per_page){
		$sql="SELECT * 
		FROM social_albums LIMIT $start,$total_per_page";
		$this->open(0);
		$rs= $this->fetch($sql,1);
		
		$this->close();
		return $rs;
	}
	
	function selectgalpic($id,$start,$total_per_page){
		$sql="SELECT * 
		FROM social_photo 
		WHERE album_id='".$id."' LIMIT $start,$total_per_page";
		$this->open(0);
		$rs= $this->fetch($sql,1);
		print mysql_error();
		$this->close();
		return $rs;
	}
	
	function selectDelPicAdmin($id){
		$sql="SELECT album_id,caption,img,img_thumb,owner_id,a.id AS idpic
		FROM social_albums b INNER JOIN 
		(SELECT * FROM social_photo WHERE id='".$id."' ) a ON b.id=a.album_id LIMIT 1";
		$this->open(0);
		$rs= $this->fetch($sql);
		
		$this->close();
		return $rs;
	}
	
	function deletePicAdmin($id){
		$sql= "DELETE FROM social_photo WHERE id=".$id."";
		$this->open(0);
		$rs= $this->query($sql);
		
		$this->close();
		
		return $rs;
	}
	
	
	
	function getGallery($album_id,$start,$total_per_page){
		$sql="SELECT a.id AS idpic,caption,album_id,img,img_thumb,c.owner_id,b.data AS DATA FROM social_photo AS a
        LEFT JOIN social_photo_tag AS b
        ON a.id = b.photo_id
        LEFT JOIN social_albums AS c   
        ON a.album_id = c.id
        WHERE c.id='".$album_id."' LIMIT $start,$total_per_page";
		
/*		$sql= "SELECT album_id,caption,img,img_thumb,owner_id,a.id AS idpic
		FROM social_photo a INNER JOIN 
		(SELECT * FROM social_albums WHERE id='".$album_id."' ) b ON b.id=a.album_id LIMIT $start,$total_per_page";*/
		$this->open(0);
		$rs= $this->fetch($sql,1);
		//$this->close();
		return $rs;
	}	
	function deleteGallery($id,$srcgallery){
		$sql= "DELETE FROM tbl_gallery WHERE id_Gallery=".$id."";
		$this->open(0);
		$rs= $this->query($sql);
		
		$this->close();
		
		return $rs;
	}
	
	function getuserid($user_id){
		
	}
	
	function updatepicblank($id,$caption){
		$id = intval($id);
		$caption = mysql_escape_string($caption);
		$sql= 
		"UPDATE social_photo 
		SET caption='".$caption."'
		WHERE id=".$id;
		$this->open(0);
		$rs= $this->query($sql);
		
		$this->close();
		return $rs;	
	}
	
	function updatepic($id,$caption,$name){
		$id = intval($id);
		$caption = mysql_escape_string($caption);
		$name = mysql_escape_string($name);
		$sql= 
		"UPDATE social_photo
		SET caption='".$caption."',img='".$name."',img_thumb='small_".$name."'
		WHERE id=".$id;
		$this->open(0);
		$rs= $this->query($sql);
		
		$this->close();
		return $rs;	
		}
	function selectAlbum($id){
		$sql="SELECT * 
		FROM social_albums 
		WHERE id='".$id."' LIMIT 1";
		$this->open(0);
		$rs=$this->fetch($sql);
		$this->close();
		return $rs;
	}
	
	
	function selectid($id){
		$sql="SELECT * FROM social_photo WHERE id='".$id."'";
		$this->open(0);
		$rs=$this->fetch($sql);
		
		$this->close();
		return $rs;
	}
	
	
	function selectPic($id){
		
		$sql="SELECT album_id,caption,img,img_thumb,owner_id,a.id AS idpic
		FROM social_albums b INNER JOIN 
		(SELECT * FROM social_photo WHERE id='".$id."' ) a ON b.id=a.album_id";
		$this->open(0);
		$rs=$this->fetch($sql);
		
		$this->close();
		return $rs;
/*		SELECT * 
		FROM social_photo 
		WHERE id='".$id."' LIMIT 1";
		*/
	}
	
	function updateAlbum($id,$namaAlbum,$statuspublished){
		
		$sql="UPDATE social_albums 
		SET album_name='".$namaAlbum."'
		WHERE id=".$id."";
		$this->open(0);
		$rs=$this->query($sql);
		$this->close();
		return $rs;
	}
	
	function editPhoto($id=0,$caption=''){
		$sql="UPDATE social_photo SET caption='".$caption."' WHERE id=".$id.";";
		$this->open(0);
		$rs=$this->query($sql);
		$this->close();
		return $rs;
	}
	
	function updateAlbumAdmin($id,$namaAlbum,$statuspublished){
		
		$sql="UPDATE social_albums 
		SET album_name='".$namaAlbum."',published='".$statuspublished."'
		WHERE id=".$id."";
		$this->open(0);
		$rs=$this->query($sql);
		$this->close();
		return $rs;
	}
	
	function selectGallery($id){
		$sql= 
		"SELECT * FROM 
		tbl_gallery 
		WHERE id_Gallery=".$id." 
		LIMIT 1";
		$this->open(0);
		$rs= $this->fetch($sql);
		
		$this->close();
		return $rs;
	}
	

	function selectdelGallery($id){
		$sql= 
		"SELECT * FROM 
		tbl_gallery 
		WHERE id_Gallery=".$id." 
		LIMIT 1";
		$this->open(0);
		$rs= $this->fetch($sql);
		
		$this->close();
		return $rs;
	}
	
	function getBanner(){
		$rand=rand(1,100);
		$sql=
		"SELECT * FROM tbl_banner WHERE n_status=1 LIMIT 1";
		$this->open(0);
		$rs=$this->fetch($sql);
		
		$this->close();
		return $rs;
	}
		
	function updateGallery($id,$judul,$deskripsi,$img){
		$id = intval($id);
		$judul = mysql_escape_string($judul);
		$deskripsi = mysql_escape_string($deskripsi);
		$img = mysql_escape_string($img);
		$sql= 
		"UPDATE tbl_gallery 
		SET nama_Gallery='".$judul."', deskripsi='".$deskripsi."', 
		src_Gallery='".$img."'
		WHERE id_Gallery=".$id;
		$this->open(0);
		$rs= $this->query($sql);
		
		$this->close();
		return $rs;	
	}
	
	function selectdelallpic($id){
		$sql="
		SELECT * 
		FROM social_photo 
		WHERE album_id='".$id."'";
		$this->open(0);
		$rs= $this->fetch($sql,1);
		
		$this->close();
		return $rs;
	}
	
	function selectcountdelallpic($id){
		$sql="
		SELECT count(*)AS jum 
		FROM social_photo 
		WHERE album_id='".$id."'";
		$this->open(0);
		$rs= $this->fetch($sql);
		
		$this->close();
		return $rs;
	}
	
	function insertAlbum($user_id,$namealbum,$published,$id){
		$sql=
		"INSERT INTO social_albums (album_name,owner_id,published)
		VALUES ('".$namealbum."',$user_id,0)";
		$this->open(0);
		$rs= $this->query($sql);
		$id= mysql_insert_id();
		$this->close();
		
		$this->open(0);
		$r = $this->fetch("SELECT * FROM social_member WHERE id=". $user_id);
		$this->close();
		$msg = "<a href='index.php?profile=1&profile_id=". $user_id ."'>".$r['name']."</a> create new album";
		$this->news->send($user_id,$msg);
		
		return array($rs,$id);
		
	}
	
	function insertnewpic($caption,$name,$album_id,$user_id){
		if($album_id!=0){
		$this->open(0);
		$cql = "SELECT COUNT(*) total FROM social_photo WHERE album_id=$album_id;";
		$cek = $this->fetch($cql);
		
		if( intval($cek['total']) > 0 ){ 
			$sql=
			"INSERT INTO 
			social_photo 
			(caption,img,img_thumb,album_id) 
			VALUES('".$caption."','".$name."','small_".$name."',".$album_id.")";
		}else{
			$sql=
			"INSERT INTO 
			social_photo 
			(caption,img,img_thumb,album_id,n_status) 
			VALUES('".$caption."','".$name."','small_".$name."',".$album_id.",1)";
		}
		
		$rs= $this->query($sql);
		//print mysql_error();exit;
		$this->close();
		
		$this->open(0);
		$r = $this->fetch("SELECT * FROM social_member WHERE id=". $user_id);
		$this->close();
		$msg = "<a href='index.php?profile=1&profile_id=". $user_id ."'>".$r['name']."</a> add new photo";
		$this->news->send($user_id,$msg);
		
		return $rs;
		}
	}
	
	function insertGallery($judul,$deskripsi,$img){
		
		$judul = mysql_escape_string($judul);
		$deskripsi = mysql_escape_string($deskripsi);
		$sql=
		"INSERT INTO tbl_gallery (nama_Gallery,deskripsi,src_Gallery)
		VALUES ('".$judul."','".$deskripsi."','".$img."')";
		$this->open(0);
		$rs= $this->query($sql);
		print mysql_error();
		$this->close();
		return $rs;	
	}
	
	function getIdPic($id){
		$sql="SELECT * FROM social_photo where id='".$id."'";
		$this->open(0);
		$rs=$this->fetch($sql);
		print mysql_error();
		$this->close();
		return $rs;
	}
	
	function getownerid($id){
		$sql="SELECT * 
		FROM social_albums
		WHERE id='".$id."' 
		LIMIT 1";
		$this->open(0);
		$rs=$this->fetch($sql);
		print mysql_error();
		$this->close();
		return $rs;
	}
	
	function getPicture($id){
		$id = intval($id);
		$sql="SELECT album_id,caption,img,img_thumb,owner_id,b.id 
		FROM social_albums b INNER JOIN 
		(SELECT * FROM social_photo WHERE id='".$id."' ) a ON b.id=a.album_id";
		$this->open(0);
		$rs=$this->fetch($sql);
		print mysql_error();
		$this->close();
		return $rs;
	}
	function getLatestPictures($total=20){
		/*
		$sql = "SELECT a.*,b.album_name,b.owner_id FROM social_photo a
				INNER JOIN social_albums b 
				ON a.album_id = b.id
				WHERE b.published = 1 && a.n_status=1  
				ORDER BY a.id DESC  LIMIT ".$total;
		*/
		$sql = "SELECT a.*,b.album_name,b.owner_id FROM social_photo a
				INNER JOIN social_albums b 
				ON a.album_id = b.id
				WHERE b.published = 1
				ORDER BY a.id DESC  LIMIT ".$total;
		
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
		
	}
	function getGalleryUpdates($total=999999){
		$sql = "SELECT a.*,b.album_name,b.owner_id FROM social_photo a
				INNER JOIN social_albums b 
				ON a.album_id = b.id
				WHERE b.published = 1 && a.n_status=1  
				ORDER BY a.id DESC  LIMIT ".$total;
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
}
?>
