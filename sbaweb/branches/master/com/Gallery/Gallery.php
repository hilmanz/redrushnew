<?php
global $ENGINE_PATH;
include_once "ModelGallery.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
class Gallery extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->content = "";
		$this->Request = $req;
		$this->View = new BasicView();
		$this->Model = new ModelGallery($req);
	}
	
	function show_widget(){
		$this->Model->get12photos();
		$this->View->assign("show_widget",$this->Model->get12photos());
		return $this->View->toString("Gallery/show_widget.html");
	}
	function getLatestPhotos($total=999999){
		$rs = $this->Model->getLatestPictures($total);
		return $rs;
	}
	function getGalleryUpdates($total=999999){
		$rs = $this->Model->getGalleryUpdates($total);
		return $rs;
	}
	function getAlbumName($album_id){
		$rs = $this->Model->getAlbumDetail($album_id);
		return $rs['album_name'];
	}
	function main($user_id){			
		if ($this->Request->getPost("insertalbumform")){
			return $this->insert_album($user_id);
		}
		elseif ($this->Request->getPost("updateAlbum")){
			return $this->update_album($user_id);
		}
		elseif($this->Request->getPost("tag_friend")=="1"){
			return $this->list_tag();
		}
		
		elseif ($this->Request->getPost("insertnewpic")){
			return $this->insert_new_pic($user_id);
		}
		elseif ($this->Request->getPost("updatePic")){
			return $this->update_pic($user_id);
		}
		elseif($this->Request->getParam("taggingphoto")=="1"){
			return $this->list_ba();
		}
		elseif($this->Request->getParam("get_tagging")=="1"){
			return $this->get_list_ba();
		}
		elseif($this->Request->getParam("pa")=="1"){
			return $this->profile_album();
		}		
		elseif ($this->Request->getParam("getupdatepic")){
			return $this->get_update_pic($user_id);
		}
		elseif ($this->Request->getParam("deletepicgallery")){
			return $this->delete_pic($user_id);
		}
		elseif ($this->Request->getParam("insertpicgallery")){
			return $this->form_insert_pic($user_id);
		}
		elseif ($this->Request->getParam("view")=="gallery"){
			return $this->gallery($user_id);	
		}
		elseif($this->Request->getParam("album")){
			return $this->albumGallery($user_id);
		}
		elseif ($this->Request->getParam("getupdateAlbum")){
			return $this->get_update_album($user_id);
		}
		elseif ($this->Request->getParam("getdeleteAlbum")){
			return $this->delete_album($user_id);
		}
		elseif($this->Request->getParam("pic")=="view"){
			return $this->view_picture($user_id);
		}
		elseif ($this->Request->getParam("insertAlbum")){
			return $this->form_insert_album($user_id);
		}
		elseif($this->Request->getParam("myalbum")=="1"){
			return $this->list_my_album($user_id);
		}
		elseif($this->Request->getParam("comment-gallery")=="gallery"){
			return $this->addComment();
		}
		else{
			return $this->albumGallery($user_id);
		}
	}	
	
	function profile_album(){
		$id=$this->Request->getParam("pic_id");
		$album_id=$this->Request->getParam("album_id");
		$this->open(0);
		$sql="select * from social_photo where album_id='".$album_id."' and n_status=1 LIMIT 1";
		$rs=$this->fetch($sql);
		if ($rs==null){
			$query="UPDATE social_photo SET n_status=1 WHERE id='".$id."'";
			
			if( $this->query($query) ){
				return $this->View->showMessage("Picture has been set as default succesfully","?gallery=1");
			}
			else {
				return $this->View->showMessage("Picture has been failed set as default succesfully","?gallery=1");				
			}			
		}
		else{
			$qry="UPDATE social_photo set n_status=0 WHERE id='".$rs[id]."'";
			$hasil=$this->query($qry);
			
			$quer="UPDATE social_photo set n_status=1 WHERE id='".$id."'";
			$rslt=$this->query($quer);
			
			if($hasil==true && $rslt==true){
				return $this->View->showMessage("Picture has been set as default succesfully","?gallery=1");
			}
			else{
				return $this->View->showMessage("Picture has been failed set as default succesfully","?gallery=1");
			}
		} 
		$this->close();
	}
	
	function list_ba(){
		$pic_id=$this->Request->getParam("pic_id");
		$this->open(0);
		$sql = "SELECT a.*,b.nama FROM social_member a
		INNER JOIN dm_member b
		ON a.register_id = b.id
		WHERE b.n_status=1 
		ORDER BY nama ASC
		LIMIT 1000";
		
		$result = $this->fetch($sql,1);
		$n = sizeof($result);
			for($i=0;$i<$n;$i++){
			$result[$i]['val'] = $result[$i]['nama'].",".$result[$i]['id'];
		}

		
		$this->View->assign("ba",$result);
		$this->View->assign("pic_id",$pic_id);
		return $this->View->toString("Gallery/list_ba.html");
	}
	
	function get_list_ba(){
		$pic_id=$this->Request->getParam("pic_id");
		$this->open(0);
		
		$sql = "SELECT a.*,b.nama FROM social_member a
		INNER JOIN dm_member b
		ON a.register_id = b.id
		WHERE b.n_status=1 
		ORDER BY nama ASC
		LIMIT 1000";
		
		$tql = "SELECT * FROM social_photo_tag WHERE photo_id=$pic_id";
		$tag = $this->fetch($tql);
		$tagged = array();
		$data = urldecode64($tag['data']);
		$tg = unserialize($data);
		if(count($tg) > 0){
			foreach ($tg as $tagname){
				if ($tagname!=""){
					array_push($tagged, $tagname);
				}
			}
		}
		//print_r($tagged);
		//exit;
		
		$result = $this->fetch($sql,1);
		$n = sizeof($result);
			for($i=0;$i<$n;$i++){
			$result[$i]['val'] = $result[$i]['nama'].",".$result[$i]['id'];
		}

		
		//$this->View->assign("ba",$result);
		$data = '<span id="txt_recipient">';
		for($i=0;$i<$n;$i++){
			
			$key = array_search( $result[$i]['nama'], $tagged); 
			//echo $key." - ";
			
			if( $key !== false ){
				$data .= '<input name="ba[]" type="checkbox" value="' . $result[$i]['nama'] . '" checked="checked" />'. $result[$i]['nama'] .'<br/>';
			}else{
				$data .= '<input name="ba[]" type="checkbox" value="' . $result[$i]['nama'] . '" />'. $result[$i]['nama'] .'<br/>';
			}
		}
		$data .= '</span>';
		
		echo $data;
		exit;
			
		//return $this->View->toString("Gallery/list_ba.html");
	}
	
	function list_tag(){
		$list_tag=$_POST['ba'];
		$list=(urlencode64(serialize($list_tag)));
		
		$album=$_POST['album'];
		$owner=$_POST['owner_id'];
		
		$id_pic=$this->Request->GetPost('id_pic');
		$this->open(0);
		$query="SELECT * FROM social_photo_tag WHERE photo_id='".$id_pic."' LIMIT 1";
		$rs=$this->fetch($query);
		if ($rs==false){			
			$signed_request = urlencode64($list_tag);
			$sql="INSERT INTO social_photo_tag VALUES('$id_pic','$list')";
			$this->query($sql);
			return $this->View->showMessage("Tag Picture Succeed","?view=gallery&owner_id=$owner&album=$album");
		}
		else {
			$signed_request = urlencode64($list_tag);
			$qr="UPDATE social_photo_tag SET data='".$list."' WHERE photo_id='".$id_pic."'";
			$this->query($qr);
			return $this->View->showMessage("Tag Picture Succeed","?view=gallery&owner_id=$owner&album=$album");
		}
		$this->close();					
	}
	
	function list_my_album($user_id,$totals=5){
		$this->open(0);
		$start = $this->Request->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT COUNT(*) as total FROM social_albums WHERE owner_id='".$user_id."' ORDER BY id LIMIT 1");
		$total = $r["total"];
		
		$qry = $this->Model->getAllAlbumshidden($user_id,$start,$totals);
			for($i=0;$i<sizeof($qry);$i++){
				$albm_id = $qry[$i]['id'];
				$qry[$i]['thumb'] = $this->Model->takealbumpic($albm_id);
				if($qry[$i]['thumb']==NULL){
					$qry[$i]['thumb']="../no_image.jpg";
				}
			}	
		
		if ($qry[0]['owner_id']==$user_id){
			$this->View->assign("album_owned_by_me",1);
		}
		
		
		$qr =$this->Model->getThumbAlbum();
		$this->View->assign("show_albumgallery",$qry);
		$this->View->assign("posted_by",$qry);
		$this->View->assign("show_thumb",$qr);
		$this->View->assign("show_hidden_albumgallery",$qry);
		
		$this->Paging = new Paginate();	
		$this->View->assign("paging",$this->Paging->generate($start, $totals, $total, "?gallery=1&myalbum=1"));
		$this->close();
		return $this->View->toString("Gallery/myalbum.html");	
	}
	
	
	function update_pic($user_id){
	global $ENGINE_PATH;
		include_once $ENGINE_PATH."Utility/Thumbnail.php";	
		$thumb = new Thumbnail(); 
		$id = $this->Request->getPost('id');	
		$caption = $this->Request->getPost('txtcaption');
		$img=$_FILES["txtimg"]["name"];
		$hiddenimg= $this->Request->getPost('hiddentxtimg');
		$album_id=$this->Request->getPost('album_id');

		if ($caption==""){
			return $this->View->showMessage("Masukkan judul yang akan diinsert", "?view=gallery&owner_id=$user_id&album=$album_id");
		}
		else {
			if ($img==""){
	    		if($this->Model->updatepicblank($id,$caption)){
					$msg = "Gambar berhasil diupdate tanpa perubahan image";}
				else{
					$msg = "Gambar tidak berhasil diupdate";}
					return $this->View->showMessage($msg, "?view=gallery&owner_id=$user_id&album=$album_id"); 
				}
			else{		
	    		if(eregi(".jpg",$_FILES['txtimg']['name'])){
					$name = md5($_FILES['txtimg']['name'].rand(1000,9999)).".jpg";
					$usermd5 =md5($user_id);
						if(move_uploaded_file($_FILES['txtimg']['tmp_name'],"contents/gallery/".$usermd5."/".$name)){
							move_uploaded_file($_FILES['txtimg']['name'],"contents/gallery/".$usermd5."/".$name);

							global $ENGINE_PATH;
							require_once $ENGINE_PATH . 'Utility/phpthumb/ThumbLib.inc.php';
							try{
								$tb = PhpThumbFactory::create( "contents/gallery/".$usermd5."/".$name );
							}catch (Exception $e){
								 // handle error here however you'd like
							}	
							$tb->adaptiveResize(50,50);
							
						/*
						if($thumb->createThumbnail("contents/gallery/".$usermd5."/".$name,"contents/gallery/".$usermd5."/small_".$name,140,100)&&
							$thumb->createThumbnail("contents/gallery/".$usermd5."/".$name,"contents/gallery/".$usermd5."/mini_".$name,50,50)){
						*/
						if($thumb->createThumbnail("contents/gallery/".$usermd5."/".$name,"contents/gallery/".$usermd5."/small_".$name,140,100)&&
							$tb->save("contents/gallery/".$usermd5."/mini_".$name) ){
								if($this->Model->updatepic($id,$caption,$name)){
//									print "3. update pic oke nih<br/>";
									@unlink("contents/gallery/".$usermd5."/".$hiddenimg);
									@unlink("contents/gallery/".$usermd5."/small_".$hiddenimg);
									@unlink("contents/gallery/".$usermd5."/mini_".$hiddenimg);
									$msg = "pic berhasil diupdate";
									return $this->View->showMessage($msg, "?view=gallery&owner_id=$user_id&album=$album_id");
									
								}else{
									$msg = "Banner tidak berhasil diupdate";
									@unlink("contents/gallery/".$usermd5."/".$name);
									@unlink("contents/gallery/".$usermd5."/small_".$name);
									@unlink("contents/gallery/".$usermd5."/mini_".$name);									
								}
						}else{
							$msg = "Maaf, gagal memproses gambar anda.";
						}
					}else{
						$msg = "failed move upload file";	
					}
				}else{
					$msg = "bukan jpeg atau salah di MD5";	
				}
			return $this->View->showMessage($msg, "?gallery=1");		
		}
	}
	}
	
	function get_update_pic(){
		$id = $this->Request->getParam("pic_id");
			if($this->Model->selectPic($id)){
			$this->View->assign("show_id",$this->Model->selectid($id));	
			$this->View->assign("show_pic",$this->Model->selectPic($id));
			$this->View->assign("album_id",$this->Request->getParam("album_id"));
			return $this->View->toString("Gallery/update_form_pic.html");
		}else{	
			$msg = "data gk ada";
			return $this->View->showMessage($msg,"?gallery=1");
		}
	}
	
	function delete_pic($user_id){
		
		$id = $this->Request->getParam("pic_id");
		$data_lama = $this->Model->selectdelpic($id);
		$album_id=$this->Request->getParam("album_id");
		
		
		$usermd5 =md5($user_id);
			@unlink("contents/gallery/".$usermd5."/".$hiddenimg);
		$old_file = "contents/gallery/".$usermd5."/".$data_lama['img'];
		$old_file_small = "contents/gallery/".$usermd5."/".$data_lama['img_thumb'];
		$old_file_mini = "contents/gallery/".$usermd5."/mini_".$data_lama['img'];
			if($this->Model->deletepic($id)){
			@unlink($old_file_mini);
			@unlink($old_file);
			@unlink($old_file_small);
			$msg = "Image berhasil dihapus";
		}else{
			$msg = "Image tidak berhasil dihapus";
		}
		return $this->View->showMessage($msg, "?view=gallery&owner_id=$user_id&album=$album_id"); 	
	}
	
	function insert_new_pic($user_id){
		global $ENGINE_PATH;
		include_once $ENGINE_PATH."Utility/Thumbnail.php";		
		$thumb = new Thumbnail(); 
		$usermd5 =md5($user_id);
		
		$caption = $this->Request->getPost('txtcaption');
		$album_id= $this->Request->getPost('idAlbumPic');
		$img=$_FILES["txtimg"]["name"];	
		
		if ($caption==""){
			return $this->View->showMessage("Masukkan caption yang akan diinsert", "?view=gallery&owner_id=$user_id&album=$album_id");
		}
		elseif ($img==""){
			return $this->View->showMessage("Masukkan Gambar yang akan diinsert", "?view=gallery&owner_id=$user_id&album=$album_id");
		}
		else {
			$newfile = "contents/gallery/".$usermd5."/".$_FILES['txtimg']['name'];
		
			if ($_FILES["txtimg"]["error"] > 0){
 //   			echo "Return Code: " . $_FILES["txtimg"]["error"] . "<br />";
		    }
  			else{
//			    echo "Upload: " . $_FILES["txtimg"]["name"] . "<br />";
//	   			echo "Type: " . $_FILES["txtimg"]["type"] . "<br />";
//	  			echo "Size: " . ($_FILES["txtimg"]["size"] / 1024) . " Kb<br />";
//		    	echo "Temp file: " . $_FILES["txtimg"]["tmp_name"] . "<br />";
  			}   		
	    		if(eregi(".jpg",$_FILES['txtimg']['name'])){
					$name = md5($_FILES['txtimg']['name'].rand(1000,9999)).".jpg";		
					if (!file_exists("contents/gallery/".$usermd5)==1){
						mkdir("contents/gallery/".$usermd5);
					}
					
					if(!is_dir("contents")){
						@mkdir("contents");
					}
					if(!is_dir("contents/gallery")){
						@mkdir("contents/gallery");
					}
					
					if(move_uploaded_file($_FILES['txtimg']['tmp_name'],"contents/gallery/".$usermd5."/".$name)){
							move_uploaded_file($_FILES['txtimg']['name'],"contents/gallery/".$usermd5."/".$name);
							global $ENGINE_PATH;
							require_once $ENGINE_PATH . 'Utility/phpthumb/ThumbLib.inc.php';
							try{
								$tb = PhpThumbFactory::create( "contents/gallery/".$usermd5."/".$name );
							}catch (Exception $e){
								 // handle error here however you'd like
							}	
							$tb->adaptiveResize(50,50);
							
							/*
							if($thumb->createThumbnail("contents/gallery/".$usermd5."/".$name,"contents/gallery/".$usermd5."/small_".$name,140,100)&&
								$thumb->createThumbnail("contents/gallery/".$usermd5."/".$name,"contents/gallery/".$usermd5."/mini_".$name,50,50)){
							*/
							if($thumb->createThumbnail("contents/gallery/".$usermd5."/".$name,"contents/gallery/".$usermd5."/small_".$name,140,100)&&
								$tb->save("contents/gallery/".$usermd5."/mini_".$name) ){
									if($this->Model->insertnewpic($caption,$name,$album_id,$user_id)){
										$msg = "Picture berhasil diinsert";
										return $this->View->showMessage($msg, "?view=gallery&owner_id=$user_id&album=$album_id");
									}else{
										$msg = "Picture tidak berhasil diinsert";
										@unlink("contents/gallery/".$usermd5."/".$name);
										@unlink("contents/gallery/".$usermd5."/small_".$name);
										@unlink("contents/gallery/".$usermd5."/mini_".$name);
									}
							}else{
								$msg = "Maaf, gagal memproses gambar anda.";
							}
						}else{
							$msg = "failed move upload file";	
						}
					}else{
						$msg = "bukan jpeg atau salah di MD5";	
					}
				return $this->View->showMessage($msg, "?view=gallery&owner_id=$user_id&album=$album_id");		
			}
		}
	
	function form_insert_pic(){
		$id_album=$this->Request->getParam("album_id");
		$id_user=$this->Request->getParam("user_id");
		$this->View->assign("form_insert_new_pic",$id_album);
		return $this->View->toString("Gallery/insert_pic_form.html");
	}
	
	function delete_album($user_id){
		$usermd5 =md5($user_id);
		$id=$this->Request->getParam("album_id");		
		$data_lama = $this->Model->selectdelallpic($id);
		$jum=$this->Model->selectcountdelallpic($id);
		if ($this->Model->deleteallpic($id)){
			for($i=0;$i<$jum["jum"];$i++){
				$old_file[$i] = "contents/gallery/".$usermd5."/".$data_lama[$i]['img'];
				$old_file_small[$i] = "contents/gallery/".$usermd5."/".$data_lama[$i]['img_thumb'];
				$old_file_mini[$i] = "contents/gallery/".$usermd5."/mini_".$data_lama[$i]['img'];
				@unlink($old_file_mini[$i]);
				@unlink($old_file[$i]);
				@unlink($old_file_small[$i]);
			}	
				if($this->Model->deletealbum($id)){
					$msg="Berhasil Delete Album";}
				else {$msg="Gagal Delete Pic";
				}
		}else{
			$msg="Gagal Delete Album";
		}
			return $this->View->showMessage($msg, "?gallery=1");
	}
		
	function update_album(){
		$id=$this->Request->getPost("id");
		$namaAlbum = $this->Request->getPost("txtjudul");
		$statuspublished = $this->Request->getPost ("rdopublished");

			if ($this->Model->updateAlbum($id,$namaAlbum,$statuspublished)){
				$msg="Berhasil Update Album";
			}
			else {
				$msg="Gagal Update Album";
			}
			return $this->View->showMessage($msg, "?gallery=1"); 
		}
	
	function update_album_admin(){
		$id=$this->Request->getPost("id");
		$namaAlbum = $this->Request->getPost("txtjudul");
		$statuspublished = $this->Request->getPost ("rdopublished");

			if ($this->Model->updateAlbumAdmin($id,$namaAlbum,$statuspublished)){
				$msg="Berhasil Update Album by admin";
			}
			else {
				$msg="Gagal Update Album by admin";
			}
			return $this->View->showMessage($msg, "?s=gallery"); 
		}	
	
	function albumGallery($user_id,$req=null,$total_per_page=6){
		$this->open(0);
		$start = $this->Request->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT COUNT(*) as total FROM social_albums WHERE published=1 ORDER BY id LIMIT 1");
		$total = $r["total"];
				
		$quer=$this->Model->getId();
		$query=$this->Model->getlist();
		
		$rs = $this->Model->getAllAlbums($start,$total_per_page);
		

		for($i=0;$i<sizeof($rs);$i++){
			$album_id = $rs[$i]['id'];
			$rs[$i]['thumb'] = $this->Model->takealbumpic($album_id);
			if($rs[$i]['thumb']==NULL){
				$rs[$i]['thumb']="../no_image.jpg";
			}
		}
		

		
		
		
		$qry = $this->Model->getAllAlbumshidden($user_id,$start,$total);
			for($i=0;$i<sizeof($qry);$i++){
				$albm_id = $qry[$i]['id'];
				$qry[$i]['thumb'] = $this->Model->takePic($albm_id);
				if($qry[$i]['thumb']==NULL){
					$qry[$i]['thumb']="../no_image.jpg";
				}
			}	
		
		if ($qry[0]['owner_id']==$user_id){
			$this->View->assign("album_owned_by_me",1);
		}
		
		
		$qr =$this->Model->getThumbAlbum();
		$this->View->assign("get",$query);
		$this->View->assign("show_albumgallery",$rs);
		$this->View->assign("posted_by",$rs);
		$this->View->assign("show_thumb",$qr);
		$this->View->assign("show_hidden_albumgallery",$qry);
		
		$this->Paging = new Paginate();	
		$this->View->assign("paging",$this->Paging->generate($start, $total_per_page, $total, "?gallery=1"));
		$this->close();
		return $this->View->toString("Gallery/album.html");	
	}
		
	function gallery($user_id,$req=null,$total_per_page=10){
		$album_id=$this->Request->getParam("album");	
		
		$this->open(0);
		$start = $this->Request->getParam("st");
		
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT COUNT(*) AS total 
		FROM social_photo AS a
        LEFT JOIN social_photo_tag AS b
        ON a.id = b.photo_id
        LEFT JOIN social_albums AS c   
        ON a.album_id = c.id
        WHERE c.id='".$album_id."' LIMIT 1");
		$total = $r["total"];
		

		$sql = "SELECT a.*,b.nama FROM social_member a
		INNER JOIN dm_member b
		ON a.register_id = b.id
		WHERE b.n_status=1 
		ORDER BY nama ASC
		LIMIT 1000";
		
		$result = $this->fetch($sql,1);
		
		$n = sizeof($result);
		for($i=0;$i<$n;$i++){
			$result[$i]['val'] = $result[$i]['id'].",".$result[$i]['nama'];
		}			
		
		$albumDetail=$this->Model->getAlbumDetail($album_id);
		
		
		$n = sizeof($result);
		for($i=0;$i<$n;$i++){
			$result[$i]['val'] = $result[$i]['id'].",".$result[$i]['nama'];
		}	
		
		if ($albumDetail['owner_id']==$user_id){
			$this->View->assign("is_owned_by_me",1);
		}
		$rs= $this->Model->getGallery($album_id,$start,$total_per_page);
		
//	print_r($rs);
//	exit();
		$str="";
		
		$n = sizeof($rs);
		for($i=0;$i<$n;$i++){
			 $data[$i]= urldecode64($rs[$i]['DATA']);
			 $rs[$i]['data']= unserialize($data[$i]);
//			 print_r($rs[$i]['data']);
			if( count($rs[$i]['data']) > 0){
				foreach ($rs[$i]['data'] as $tagname){
					if ($tagname!=""){
						$nama = $tagname;
						
						$tsql = "SELECT * FROM social_member WHERE name LIKE '%$nama%' LIMIT 1";
						
						//$this->open(0);
						$tid= $this->fetch($tsql);
						$str .= "<a href='index.php?profile=1&profile_id=".$tid['id']."'>". ucfirst(strtolower($tagname))."</a>, ";
						//$str[$i]=$rs[$i]['data'].",";
							//print_r($str[$i]);
					}
				}
			}
			//$rs[$i]['tag_name'] = ucfirst($str);
			$rs[$i]['tag_name'] =  substr($str, 0, strlen($str) - 2);
			$str="";	
		}
			
//print_r($rs[0]['data']);
//	print_r($rs[2]['data']);
//	print_r($dat);
//	print_r($str);
//print $data[1];

		//comment
		$this->close();
		$rs2 = $this->commentAlbum($album_id);
		$this->View->assign("comm",$rs2['list']);
		$this->View->assign("comm_num",$rs2['num']);
		
		$this->open(0);
		$u=$this->fetch("SELECT * FROM social_member WHERE id=$user_id;");
		$this->close();
		
		//$this->View->assign("tag",$str);
		$this->View->assign("album_id",$album_id);
		$this->View->assign("owner_id",$user_id);
		$this->View->assign("user_image",$u['small_img']);
		$this->Paging = new Paginate();	
		$this->View->assign("show_gallery",$rs);
		$this->View->assign("ba",$result);
		$this->View->assign("album_id",$album_id);
		$this->View->assign("paging",$this->Paging->generate($start, $total_per_page, $total, "?view=gallery&owner_id=".$this->Request->getParam('owner_id')."&album=".$this->Request->getParam('album').""));	
		return $this->View->toString("Gallery/gallery.html");	
	}	
	
	function view_picture($user_id){
		$id = $this->Request->getParam('id');
		$owner_id=$this->Model->getownerid($id);
		$detailPicture=$this->Model->getGalleryDetail($id);
		
		$getidpic=$this->Model->getIdPic($id);

		if ($detailPicture['owner_id']==$user_id){
			$this->View->assign("is_owned_by_me",1);
		}
		$this->View->assign("getidpic",$getidpic);
		$this->View->assign("viewpic",$this->Model->getPicture($id));
		$this->View->assign("owner_id",$this->Model->getownerid($id));
		
		return $this->View->toString("Gallery/detail_picture.html");
	}
	
	function admin(){
		if($this->Request->getPost("updateform")){
			return $this->update_gallery();
		
		}elseif($this->Request->getPost("insertform")){
			return $this->insert_gallery();
		}
		elseif ($this->Request->getPost("updateAlbumAdmin")=="1"){
			return $this->update_album_admin();
		}
		elseif($this->Request->getParam("insertPic")){
			return $this->form_insert_gallery();
		}elseif ($this->Request->getParam("view")=="photo_view"){
			return  $this->form_view_photo();
		}elseif($this->Request->getParam("delete")){
			return $this->delete_gallery();
			
		}elseif($this->Request->getParam("getform")){
			return $this->getUpdateGallery();
		}
		elseif($this->Request->getParam("deleteadmin")){
			return $this->doDeletePic();
		}
		elseif($this->Request->getParam("deletealbum")){
			return $this->doDeleteAlbum();
		}
		elseif ($this->Request->getParam("pic_detail_admin")){
			return $this->viewPicDetailAdmin();
		}
		elseif ($this->Request->getParam("pic_edit_admin")){
			return $this->picEditAdmin();
		}
		elseif($this->Request->getParam("updatealbum")=="1"){
			return $this->admin_get_update_album();
		}
		elseif($this->Request->getParam("comment")=="1"){
			return $this->commentList();
		}
		elseif($this->Request->getParam("change")=="1"){
			return $this->commentSetStatus();
		}
		elseif($this->Request->getParam("delete-comment")=="1"){
			return $this->commentDelete();
		}
		else{
			return $this->manage_gallery($this->Request);
		}
	}
	
	function doDeleteAlbum(){
		$owner=$this->Request->getParam("owner_id");
	
		$usermd5 =md5($owner);
	
		$id=$this->Request->getParam("id");		
		$data_lama = $this->Model->selectdelallpic($id);
		$jum=$this->Model->selectcountdelallpic($id);
			if ($this->Model->deleteallpic($id)){
				for ($i=0;$i<$jum["jum"];$i++){
					$old_file[$i] = "../contents/gallery/".$usermd5."/".$data_lama[$i]['img'];
					$old_file_small[$i] = "../contents/gallery/".$usermd5."/".$data_lama[$i]['img_thumb'];
					$old_file_mini[$i] = "../contents/gallery/".$usermd5."/mini_".$data_lama[$i]['img'];	
					
					
		
				@unlink($old_file_mini[$i]);
				@unlink($old_file[$i]);
				@unlink($old_file_small[$i]);
					
					
				}
				
				if($this->Model->deletealbum($id)){
					$msg="Berhasil Delete Album";}
				else $msg="Gagal Delete Pic";
				}
			else {
				$msg="Gagal Delete Album";
			}
			return $this->View->showMessage($msg, "index.php?s=gallery");
	}
	
	function viewPicDetailAdmin(){
		$id=$this->Request->getParam("id");
		$query=$this->Model->selectDelPicAdmin($id);
		$this->View->assign("view",$query);
		return $this->View->toString("Gallery/admin/view_pic_detail.html");		
	}
	
	function doDeletePic(){
		$id = $this->Request->getParam("id");
		$ini= $this->Request->getParam("srcgallery");
		
		$data_lama = $this->Model->selectDelPicAdmin($id);
		$usermd5 =md5($data_lama[owner_id]);
		$owner=$data_lama[owner_id];
		
		$old_file = "../contents/gallery/".$usermd5."/".$data_lama['img'];
		$old_file_small = "../contents/gallery/".$usermd5."/".$data_lama['img_thumb'];
		$old_file_mini = "../contents/gallery/".$usermd5."/mini_".$data_lama['img'];

		if($this->Model->deletePicAdmin($id)){
			@unlink($old_file);
			@unlink($old_file_small);
			@unlink($old_file_mini);
			$msg = "Gambar berhasil dihapus";
		}else{
			$msg = "Gambar tidak berhasil dihapus";
		}
		return $this->View->showMessage($msg, "index.php?s=gallery"); 
		
	}
	
	function form_view_photo($total_per_page=6){
		$id=$this->Request->getParam("id");
		$this->open(0);
		$start = $this->Request->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT COUNT(*) as total FROM social_photo 
		WHERE album_id='".$id."' LIMIT 1");
		$total = $r["total"];
		
		
	 	$query=$this->Model->selectgalpic($id,$start,$total_per_page);
	 	$this->View->assign("show_pic_gallery_admin",$query);
		
	 	$this->Paging = new Paginate();	
		$this->View->assign("paging",$this->Paging->generate($start, $total_per_page, $total, "?s=gallery&view=photo_view&id=".$id.""));
		$this->close();
	 	
	 	return $this->View->toString("Gallery/admin/manage_pic.html");
	}
	
	function  manage_gallery($req,$total_per_page=6){
		$this->open(0);
		$start = $this->Request->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT COUNT(*) as total FROM social_albums LIMIT 1");
		$total = $r["total"];
		
		$this->View->assign("show_gallery",$this->Model->getAllGallery($start,$total_per_page));
		$this->Paging = new Paginate();	
		$this->View->assign("paging",$this->Paging->generate($start, $total_per_page, $total, "?s=gallery"));
		$this->close();
		
		return $this->View->toString("Gallery/admin/manage_gallery.html");
	}
	function delete_gallery(){
		$id = $this->Request->getParam("id");
		$ini= $this->Request->getParam("srcgallery");
		
		$data_lama = $this->Model->selectdelGallery($id);
		$old_file = "../../Gallery/contents/images/".$data_lama['src_Gallery'];
		$old_file_small = "../../Gallery/contents/images/small_".$data_lama['src_Gallery'];
		
		if($this->Model->deleteGallery($id)){
			@unlink($old_file);
			@unlink($old_file_small);
			$msg = "Gambar berhasil dihapus";
		}else{
			$msg = "Gambar tidak berhasil dihapus";
		}
		return $this->View->showMessage($msg, "index.php?"); 
		
	}
	function getUpdateGallery(){
		$id = $this->Request->getParam("id");
	
		if($this->Model->selectGallery($id)){
			$this->View->assign("show_gallery",$this->Model->selectGallery($id));
			return $this->View->toString("Gallery/admin/update_form.html");
		}else{	
			$msg = "data gk ada";
			return $this->View->showMessage($msg,"gallery.php?s=gallery");
		}		
	}

	
	function get_update_album(){
		$id = $this->Request->getParam("album_id");
		
		if($this->Model->selectAlbum($id)){
			$this->View->assign("show_album",$this->Model->selectAlbum($id));
	
			return $this->View->toString("Gallery/update_form.html");
		}else{	
			$msg = "data gk ada";
			return $this->View->showMessage($msg,"?gallery=1");
		}		
	}
	
	function admin_get_update_album(){
		$id = $this->Request->getParam("id");
		if($this->Model->selectAlbum($id)){
			$this->View->assign("show_album",$this->Model->selectAlbum($id));
			
			return $this->View->toString("Gallery/admin/update_album.html");
		}else{	
			$msg = "data gk ada";
			return $this->View->showMessage($msg,"?s=gallery");
		}		
	}
	
	function picEditAdmin(){
		$id = intval($this->Request->getParam("id"));
		if( $id < 1 ){ return $this->View->showMessage("Data photo tidak ada!","?s=gallery"); }
		
		if($this->Request->getParam("edit")){
			$caption = strip_tags($this->Request->getParam("caption"));
			$caption = mysql_escape_string($caption);
			if($this->Model->editPhoto($id,$caption)){
				return $this->View->showMessage("Gambar berhasil diedit!", "index.php?s=gallery"); 
			}else{
				return $this->View->showMessage("Gambar tidak berhasil diedit!", "index.php?s=gallery"); 
			}
		}
		
		$rs=$this->Model->selectid($id);
		$this->View->assign("list",$rs);	
		return $this->View->toString("Gallery/admin/edit_photo.html");		
	}
	
	function update_gallery(){
		
		global $ENGINE_PATH;
		include_once $ENGINE_PATH."Utility/Thumbnail.php";
		
		$thumb = new Thumbnail();
		
		$id = $this->Request->getPost("id");;
		$judul = $this->Request->getPost('txtjudul');
		$deskripsi = $this->Request->getPost('txtdesk');
		$hiddenimg= $this->Request->getPost('hiddentxtimg');
		$img=$_FILES["txtimg"]["name"];
		
		if ($judul==""){
			return $this->View->showMessage("Masukkan judul yang akan diupdate", "index.php?s=gallery");
		}
		elseif ($deskripsi==""){
			return $this->View->showMessage("Masukkan deskripsi yang akan diupdate", "index.php?s=gallery");
		}
		else {
		
		$newfile = "../../Gallery/contents/images/".$_FILES['txtimg']['name'];
		
		$data_lama = $this->Model->selectdelGallery($id);
		$old_file = "../../Gallery/contents/images/".$data_lama['src_Gallery'];
		$old_file_small = "../../Gallery/contents/images/small_".$data_lama['src_Gallery'];
		
		if ($img==""){
		$hiddenimg= $this->Request->getPost('hiddentxtimg');
		
	    if($this->Model->updateGallery($id,$judul,$deskripsi,$hiddenimg)){
			$msg = "Gambar berhasil diupdate";
		}else{
			$msg = "Gambar tidak berhasil diupdate";
		}
		return $this->View->showMessage($msg, "index.php?s=gallery"); 
		}
		else {
		$name=($_FILES['txtimg']['name']);
		if ($_FILES["txtimg"]["error"] > 0)
	    {
  //  		echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
	    }
  		else{
//		    echo "Upload: " . $_FILES["txtimg"]["name"] . "<br />";
//    		echo "Type: " . $_FILES["txtimg"]["type"] . "<br />";
//   		echo "Size: " . ($_FILES["txtimg"]["size"] / 1024) . " Kb<br />";
//		    echo "Temp file: " . $_FILES["txtimg"]["tmp_name"] . "<br />";
  		}
		
  		if (file_exists("../../Gallery/contents/images/" . $_FILES["txtimg"]["name"]))
  		{
//  			echo $_FILES["txtimg"]["name"] . " already exists. ";
  		}
	    else{
			move_uploaded_file($_FILES["txtimg"]["tmp_name"],
			"../../Gallery/contents/images/" . $_FILES["txtimg"]["name"]);
//			echo "Stored in: " . "../../Gallery/contents/images/" . $_FILES["txtimg"]["name"];

			if($thumb->createThumbnail("../../Gallery/contents/images/".$name,"../../Gallery/contents/images/".$name,160,160)&&
					$thumb->createThumbnail("../../Gallery/contents/images/".$name,"../../Gallery/contents/images/small_".$name,140,100)
				){
//					print "resize ok";
				}
	    
	    }
		
	    if($this->Model->updateGallery($id,$judul,$deskripsi,$img)){
			@unlink($old_file);
			@unlink($old_file_small);
			$msg = "Gambar berhasil diupdate";
		}else{
			$msg = "Gambar tidak berhasil diupdate";
		}
		return $this->View->showMessage($msg, "index.php?s=gallery"); 
		}
		}	
	}
	
	function form_insert_album($id_user){
		return $this->View->toString("Gallery/insert_form.html");
		
	}
	
	function form_insert_gallery(){
		return $this->View->toString("Gallery/admin/insert_form.html");
	}
	
	function insert_album($user_id){
		$namealbum = $this->Request->getPost('txtalbumname');
		$published = $this->Request->getPost('rdopublished');
		if ($namealbum==""){
			return $this->View->showMessage("Masukkan Nama Album yang akan diinsert", "?gallery=1&insertAlbum=1");
		}
		else {	
			if(list($rs,$id)=$this->Model->insertAlbum($user_id,$namealbum,$published,$id)){			
				$msg = "Album berhasil diinsert";	
				return $this->View->showMessage($msg, "?view=gallery&owner_id=$user_id&album=$id");
				}
				
			else{
				$msg = "Album tidak berhasil diinsert";
				return $this->View->showMessage($msg, "?gallery=1&insertAlbum=1");
			}
	 	}	
	}

	function commentAlbum($album=0,$start=0,$total=9999999,$ajax=false,$status='1'){
		$album = $album == 0 ? '' : '&& c.album_id='.$album;
		$status = $status == 'all' ? '' : "&& c.n_status='".$status."'";
		$sql = "SELECT 
					c.*,
					m.id user_id,
					m.name,
					m.small_img,
					a.album_name album
				FROM 
					social_photo_comment c
					LEFT JOIN social_member m
					ON c.user_id=m.id
					LEFT JOIN social_albums a
					ON c.album_id=a.id
				WHERE
					1 $status $album
				ORDER BY 
					c.comment_date ASC
				LIMIT $start,$total;";
		//echo $sql;exit;
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		if($ajax){
		}else{
			$sql = "SELECT count(*) total
				FROM 
					social_photo_comment c
					LEFT JOIN social_member m
					ON c.user_id=m.id
					LEFT JOIN social_albums a
					ON c.album_id=a.id
				WHERE
					1 $status $album
				ORDER BY 
					c.comment_date ASC;";
			$this->open(0);
			$num = $this->fetch($sql);
			$this->close();
			//print_r(array('list'=>$rs,'num'=>$num['total']));exit;
			return array('list'=>$rs,'num'=>$num['total']);
		}
	}
	
	function addComment(){
		$album_id=intval($this->Request->getParam("album_id"));
		$user_id=intval($this->Request->getParam("user_id"));
		if( $this->Request->getParam("comment") == ''){
			return $this->View->showMessage("Silakan isi komentar terlebih dahulu!", "index.php?view=gallery&album=$album_id");
		}
		$comment = $this->Request->getParam("comment");
		$comment = strip_tags($comment);
		$comment = stripslashes($comment);
		$comment = mysql_escape_string($comment);
		//$sql = 'INSERT INTO social_photo_comment (user_id,comment_text,comment_date,album_id) VALUES ('.$user_id.',"'.$comment.'",now(),'.$album_id.');'; //pakai moderasi
		$sql = 'INSERT INTO social_photo_comment (user_id,comment_text,comment_date,album_id,n_status) VALUES ('.$user_id.',"'.$comment.'",now(),'.$album_id.',"1");'; //tidak pakai moderasi
		$this->open(0);
		if($this->query($sql)){
			return $this->View->showMessage("Komentar berhasil disimpan!", "index.php?view=gallery&album=$album_id");
		}else{
			return $this->View->showMessage("Komentar gagal disimpan, silakan coba lagi!", "index.php?view=gallery&album=$album_id");
		}
	}

	function commentList(){
		$start = $this->Request->getParam("st");
		if($start==NULL){$start = 0;}
		$total_per_page = 50;
		
		$rs=$this->commentAlbum(0,$start,$total_per_page,false,'all');
		
		$list = $rs['list'];
		$this->View->assign("list",$list);
		$Paging = new Paginate();
		$this->View->assign("paging",$Paging->getAdminPaging($start, $total_per_page, $rs['num'], "?s=gallery&comment=1"));
		return $this->View->toString("Gallery/admin/comment.html");
	}
	function commentSetStatus(){
		$status = intval( $this->Request->getParam("status") == '1' ? '0' : '1' );
		$id = intval($this->Request->getParam("id"));
		if( $this->query("UPDATE social_photo_comment SET n_status='$status' WHERE comment_id=$id;") ){
			return $this->View->showMessage("Status komentar berhasil di rubah!", "index.php?s=gallery&comment=1");
		}else{
			return $this->View->showMessage("Status komentar gagal di rubah!", "index.php?s=gallery&comment=1");
		}
	}
	function commentDelete(){
		$id = intval($this->Request->getParam("id"));
		if( $this->query("DELETE FROM social_photo_comment WHERE comment_id=$id;") ){
			return $this->View->showMessage("Komentar berhasil dihapus", "index.php?s=gallery&comment=1");
		}else{
			return $this->View->showMessage("Komentar gagal dihapus", "index.php?s=gallery&comment=1");
		}
	}
}
?>
