<?php
global $ENGINE_PATH;
include_once "ModelDownload.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
class Download extends SQLData{
	var $user_id=0;
	function __construct($req, $user_id=0){
		parent::SQLData();
		$this->content = "";
		$this->user_id = $user_id;
		$this->Request = $req;
		$this->View = new BasicView();
		$this->Model = new ModelDownload($req);
	}
	function main(){
		if($this->Request->getParam("q")){
			return $this->query_category();
		}
		elseif($this->Request->getParam("view")=="download"){
			return $this->download_file();
		}
		else {
			return $this->category_download();
		}
	}
	
	function Admin(){
		if($this->Request->getPost("insertcategory")){
			return $this->doinsert_category();
		}
		elseif ($this->Request->getPost("insertfile")){
			return $this->doinsert_file();
		}
		elseif ($this->Request->getPost("updatecategory")){
			return $this->doupdate_category();
		}
		elseif ($this->Request->getPost("updatePic")){
			return $this->doupdate_file();
		}
		elseif($this->Request->getParam("deletefile")){
			return $this->dodelete_file();
		}
		elseif($this->Request->getParam("getformfile")){
			$id=$this->Request->getParam("id");
			$isi=$this->Model->getUpdateFile($id);
			$this->View->assign("content",$isi);
			return $this->View->toString("Download/admin/update_form_file.html");
		}
		elseif ($this->Request->getParam("new_category")){
			return $this->View->toString("Download/admin/insert_category.html");
		}
		elseif($this->Request->getParam("getform")){
			$id=$this->Request->getParam("id");
			$content=$this->Model->getCategory($id);
			$this->View->assign("id",$content);
			return $this->View->toString("Download/admin/update_form_category.html");
		}
		elseif($this->Request->getParam("view")=="item"){
			return $this->getfile();
		}
		elseif ($this->Request->getParam("delete")){
			return $this->delete_category();
		}
		elseif($this->Request->getParam("view")=="detail"){
			return $this->view_file();
		}
		elseif($this->Request->getParam("new_file")){
			return $this->new_file();
		}
		else{
			return $this->listcategory();
		}
	}
	
	function download_file(){
		$id=$this->Request->getParam("id");
		$this->Model->getdatadownload($id);
	}
	
	function query_category(){
		$id = $this->Request->getParam("q");
		$query=$this->Model->Selectbycategory($id);
		$arr = array ('don'=>$query);
		echo json_encode($arr);		
		exit;
	}
	
	function select_download(){
		
		$dataPerPage = 10;
		
		if(isset($this->Request->getParam["page"]))
		{
			$noPage = $this->Request->getParam["page"];
		}
		else $noPage = 1;
		
		$offset = ($noPage - 1) * $dataPerPage;
		$filt=$this->Model->getlistcategory();
		$qr=$this->Model->getalldownload($offset,$dataPerPage);
		
		$data  =$this->Model->getCountPage();
		$jumData=$data["jumData"];

		$jumPage = ceil($jumData/$dataPerPage);
		
		$this->View->assign("uid",$this->user_id);
		
		if ($noPage > 1) {
			$this->View->assign("page",$noPage);
			$this->View->toString("Download/listdownload.html");	
		}
		$this->View->assign("select_filter",$filt);
		$this->View->assign("select_row",$qr);
		
		return $this->View->toString("Download/listdownload.html");
	}
	
	function listcategory($total_per_page=6){
		$this->open(0);
		$start = $this->Request->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT COUNT(*) as total FROM tbl_download_category LIMIT 1");
		$total = $r["total"];
				
		$rs = $this->Model->getlistcategorydownload($start,$total_per_page);
		$this->View->assign("list_category_download",$rs);
		$this->Paging = new Paginate();	
		$this->View->assign("paging",$this->Paging->generate($start, $total_per_page, $total, "?download=1&s=download"));
		$this->close();
		return $this->View->toString("Download/admin/manage_category_download.html");
	}
	
	function category_download($total_per_page=9){
	
		$id=$this->Request->getParam("c");
	
		$this->open(0);
		$start = $this->Request->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT COUNT(*) as total FROM tbl_download WHERE category_id='".$id."' LIMIT 1");
		$total = $r["total"];
		
		if ($id==0){
			$hasil_category = $this->Model->getContentDownloadnull($start,$total_per_page);
			$filt=$this->Model->getlistcategory();
		}
		else{
			$hasil_category = $this->Model->getContentDownload($id,$start,$total_per_page);
			$filt=$this->Model->getlistcategory();
		}
		
		$this->View->assign("select_filter",$filt);
	//	$hasil_category = $this->Model->getContentDownload($id,$start,$total_per_page);
		
		$this->View->assign("content_download",$hasil_category);
		
		$this->View->assign("uid",$this->user_id);
		
//		var_dump($hasil_category);
		$this->Paging = new Paginate();	
		$this->View->assign("paging",$this->Paging->generate($start, $total_per_page, $total, "?download=1&c=".$this->Request->getParam("c").""));
		$this->close();
		
		
		
		return $this->View->toString("Download/listdownload.html");
	}
	
	function dodelete_file(){
		$id=$this->Request->getParam("id");
		$data_lama = $this->Model->selectdel($id);
		$old_file_file = "../contents/download/".$data_lama['file'];		
		$old_file_pic = "../contents/download/".$data_lama['thumb'];
		if($this->Model->deletedata($id)){
			@unlink($old_file_file);
			@unlink($old_file_pic);
			$msg="Sukses hapus data";
		}
		else
		{
			$msg="Gagal hapus data";
		}
	return $this->View->showMessage($msg, "index.php?s=download");	
	}
	
	function doupdate_file(){
		global $ENGINE_PATH;
		include_once $ENGINE_PATH."Utility/Thumbnail.php";
		
		$thumb = new Thumbnail();
		
		$id = $this->Request->getPost("id");;
		$namafile = $this->Request->getPost('txtname');
		$hiddenimg= $this->Request->getPost('hiddentxtimg');
		$hiddenfile= $this->Request->getPost('hiddentxtfile');
		$img=$_FILES["txtimg"]["name"];
		$file=$_FILES["txtfile"]["name"];
		
		$data_lama = $this->Model->selectdel($id);
		$old_file_file = "../contents/download/".$data_lama['file'];
		$old_file_pic = "../contents/download/".$data_lama['thumb'];
		
		if ($namafile==""){
			return $this->View->showMessage("Masukkan judul yang akan diinsert", "gallery.php");
		}
		else {
	//		print $id;
			if ($img=="" & $file==""){
	    		if($this->Model->updatefileblank($id,$namafile)){
					$msg = "Gambar berhasil diupdate tanpa perubahan image dan file";}
				else{
					$msg = "Gambar tidak berhasil diupdate";}
					return $this->View->showMessage($msg, "index.php?s=download"); 
				}
			elseif($img==""){
					$file=$_FILES["txtfile"]["name"];
					$num=(explode(".",$file));
					$size=sizeof($num)-1;

					$ekstensi=$num[$size];					
					if(eregi('.'.$ekstensi,$_FILES['txtfile']['name'])){
						$filedownload = md5($_FILES['txtfile']['name'].rand(1000,9999)).".".$ekstensi;
						if(move_uploaded_file($_FILES['txtfile']['tmp_name'],"../contents/download/".$filedownload)){	
									if($this->Model->updatepicwithoutimg($id,$namafile,$filedownload)){
		//								print "3. update pic oke nih<br/>";
										@unlink($old_file_file);
										$msg = "File berhasil diupdate tanpa perubahan image";
										return $this->View->showMessage($msg, "index.php?s=download");
									
									}else{
		//								print "3.1. update ke database gagal<br/>";
										$msg = "Picture dan file tidak berhasil diupdate";
										@unlink("../contents/download/".$name);
									}
						}else{
							$msg="Gagal move upload filenya file";
						}
					}else{
						$msg="Gagal MD 5 untuk pic";	
					}
		return $this->View->showMessage($msg, "index.php?s=download");				
		}
			elseif($file==""){
	    		if(eregi(".jpg",$_FILES['txtimg']['name'])){
					$name = md5($_FILES['txtimg']['name'].rand(1000,9999)).".jpg";
			//		print $name;
					if(move_uploaded_file($_FILES['txtimg']['tmp_name'],"../contents/download/".$name)){	
	//					print "1. move uploaded file. <br/>";				
									if($this->Model->updatepicwithoutfiles($id,$namafile,$name)){
				//						print "3. update pic oke nih<br/>";
										@unlink($old_file_pic);
										$msg = "Image berhasil diupdate tanpa perubahan file";
										return $this->View->showMessage($msg, "index.php?s=download");
									
									}else{
			//							print "3.1. update ke database gagal<br/>";
										$msg = "Picture dan file tidak berhasil diupdate";
										@unlink("../contents/download/".$name);
									}
						}else{
							$msg="Gagal move upload filenya thumb";
						}
					}else{
						$msg="Gagal MD 5 untuk thumb";	
					}
		return $this->View->showMessage($msg, "index.php?s=download");				
		}
			else{
	    		if(eregi(".jpg",$_FILES['txtimg']['name'])){
					$name = md5($_FILES['txtimg']['name'].rand(1000,9999)).".jpg";
		//			print $name;
					if(move_uploaded_file($_FILES['txtimg']['tmp_name'],"../contents/download/".$name)){	
	//					print "1. move uploaded file. <br/>";

					$file=$_FILES["txtfile"]["name"];
					$num=(explode(".",$file));
					$size=sizeof($num)-1;
					
					$ekstensi=$num[$size];					
					if(eregi('.'.$ekstensi,$_FILES['txtfile']['name'])){
						$filedownload = md5($_FILES['txtfile']['name'].rand(1000,9999)).".".$ekstensi;
						if(move_uploaded_file($_FILES['txtfile']['tmp_name'],"../contents/download/".$filedownload)){	
							if($thumb->createThumbnail("../contents/download/".$name,"../contents/download/".$name,160,160)){
	//								print "2. create thumbnail done.<br/>";
									if($this->Model->updatepic($id,$namafile,$name,$filedownload)){
	//									print "3. update pic oke nih<br/>";
										@unlink($old_file_pic);
										@unlink($old_file_file);
										$msg = "Picture berhasil diupdate";
										return $this->View->showMessage($msg, "index.php?s=download");
									
									}else{
	//									print "3.1. update ke database gagal<br/>";
										$msg = "Picture dan file tidak berhasil diupdate";
										@unlink("../contents/download/".$name);
									}
							}else{
	//							print "2.1. resizenya gagal <br/>";
								$msg = "Maaf, gagal memproses gambar anda.";
							}
						}else{
							$msg="Gagal move upload filenya file";
						}
					}else{
						$msg="Gagal MD 5 untuk file";	
					}
				}else{
					$msg = "failed move upload file gambar";	
				}
			}else{
				$msg = "bukan jpeg atau salah di MD5 untuk gambar";	
			}
		return $this->View->showMessage($msg, "index.php?s=download");		
	}
			}	
		}
	
	
	function doinsert_category(){
		$nama=$this->Request->getPost("txtcategory");
			if ($this->Model->doinsertcategory($nama)){
				$msg = "sukses insert new category";
				return $this->View->showMessage($msg, "index.php?s=download"); 
			}
			else{
				$msg = "Gagal Insert new category";
			}
	}
	
	function delete_category(){
		$id=$this->Request->getParam("id");
		$data_lama = $this->Model->selectdelallfile($id);
			$jum=$this->Model->selectcountdelallpic($id);
			if ($this->Model->deleteallfile($id)){
				for($i=0;$i<$jum["jum"];$i++){
					$old_file_thumb[$i] = "../contents/download/".$data_lama[$i]['thumb'];
					$old_file[$i] = "../contents/download/".$data_lama[$i]['file'];
					@unlink($old_file_thumb[$i]);
					@unlink($old_file[$i]);				
				}
				
				if($this->Model->deletecategory($id)){
					$msg="Berhasil Delete Category";}
				else $msg="Gagal Delete file";
				}
			else {
				$msg="Gagal Delete Category";
			}
			return $this->View->showMessage($msg, "index.php?s=download");
		
	}
	
	function getfile($total_per_page=6){
		$id = $this->Request->getParam("id_category");

		$this->open(0);
		$start = $this->Request->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT COUNT(*) as total FROM tbl_download WHERE category_id='".$id."' LIMIT 1");
		$total = $r["total"];
				
		$dogetfile=$this->Model->dogetfile($id,$start,$total_per_page);
		$dogetidcategory = $this->Model->dogetidcategory($id);
		$this->View->assign("getidcategory",$dogetidcategory);
		$this->View->assign("getfile",$dogetfile);
		$this->Paging = new Paginate();	
		$this->View->assign("paging",$this->Paging->generate($start, $total_per_page, $total, "?s=download&view=item&id_category=".$id.""));
		$this->close();
		
		return $this->View->toString("Download/admin/manage_file_download.html");
	}

	function view_file(){
		$id = $this->Request->getParam("id_file");
		$rs = $this->Model->getspecificFile($id);
		$this->View->assign("view_file",$rs);
		return $this->View->toString("Download/admin/detail_file.html");
	}
	
	function new_file(){
		$category_id=$this->Request->getParam("category_id");
		$this->View->assign("id_category",$category_id);
		return $this->View->toString("Download/admin/insert_file.html");
	}
	
	function doupdate_category(){
		$id=$this->Request->getPost("id");
		$txtcategory = $this->Request->getPost("txtcategory");

			if ($this->Model->updateCategory($id,$txtcategory)){
				$msg="Berhasil Update Category";
			}
			else {
				$msg="Gagal Update Category";
			}
			return $this->View->showMessage($msg, "index.php?s=download"); 	
	}
	
	function doinsert_file(){
		global $ENGINE_PATH;
		include_once $ENGINE_PATH."Utility/Thumbnail.php";		
		$thumb = new Thumbnail(); 
		$id_category = $this->Request->getPost("category_id");		
		$nama=$this->Request->getPost("txtnamefile");
		
		$filethumb=$_FILES["txtfilethumb"]["name"];
		$file=$_FILES["txtfile"]["name"];
				
		if ($nama==""){
			return $this->View->showMessage("Masukkan Nama File yang akan diinsert", "index.php?s=download");
		}
		elseif ($filethumb==""){
			return $this->View->showMessage("Masukkan Gambar yang akan diinsert", "index.php?s=download");
		}
		elseif ($file==""){
			return $this->View->showMessage("Masukkan File yang akan diinsert", "index.php?s=download");
		}
		else {
			
			if ($_FILES["txtfilethumb"]["error"] > 0){
    			echo "Return Code: " . $_FILES["txtfilethumb"]["error"] . "<br />";
		    }
  				
	    		if(eregi(".jpg",$_FILES['txtfilethumb']['name'])){
					$namethumbnail = md5($_FILES['txtfilethumb']['name'].rand(1000,9999)).".jpg";

					$file=$_FILES["txtfile"]["name"];
					$num=(explode(".",$file));
					$size=sizeof($num)-1;

					$ekstensi=$num[$size];
					
					if(eregi('.'.$ekstensi,$_FILES['txtfile']['name'])){
						$filedownload = md5($_FILES['txtfile']['name'].rand(1000,9999)).".".$ekstensi;
	
						if(move_uploaded_file($_FILES['txtfilethumb']['tmp_name'],"../contents/download/".$namethumbnail)){	
							if(move_uploaded_file($_FILES['txtfile']['tmp_name'],"../contents/download/".$filedownload)){
								if($thumb->createThumbnail("../contents/download/".$namethumbnail,"../contents/download/".$namethumbnail,60,60)){
									if($this->Model->insertnewfile($nama,$namethumbnail,$filedownload,$id_category)){
									//	print "3. insert pic oke nih<br/>";
										$msg = "File berhasil diupload.";
										return $this->View->showMessage($msg, "index.php?s=download");
									}else{
									//	print "3.1. insert ke database gagal<br/>";
										$msg = "File tidak berhasil diupload.";
										@unlink("../contents/download/".$name);
									}
								}else{
									//print "2.1. resizenya gagal <br/>";
									$msg = "Maaf, gagal memproses gambar anda.";
								}
							}else{
								$msg = "Mohon maaf, file gagal diupload.";
							}
						}else{
							$msg = "Maaf, gagal menyimpan file anda. Silahkan coba kembali !";
						}
						return $this->View->showMessage($msg, "index.php?s=download");		
					}
		    	}else{
					return $this->View->showMessage("Maaf file thumbnail harus JPG", "index.php?s=download");
				}
		}
	}	
}
?>