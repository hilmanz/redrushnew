<?php
global $ENGINE_PATH;
include_once "ModelBanner.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
class Banner extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->content = "";
		$this->Request = $req;
		$this->View = new BasicView();
		$this->Model = new ModelBanner($req);
	}
	function main(){
		return $this->view_banner();		
	}
	function admin(){
		if($this->Request->getPost("updateform")){
			return $this->update_banner();
		
		}elseif($this->Request->getPost("insertform")){
			return $this->insert_banner();
			
		}elseif($this->Request->getParam("insertPic")){
			return $this->form_insert_banner();
		
		}elseif($this->Request->getParam("delete")){
			return $this->delete_banner();
			
		}elseif($this->Request->getParam("getform")){
			return $this->getUpdateBanner();
		}
		else{	
			return $this->manage_banner();
		}
	}
	
	function view_banner(){
		$rs= $this->Model->getBanner();
		$this->View->assign("show_banner",$rs);
		return $this->View->toString("Banner/banner.html");
	}
	
	function header_banner(){		//um diintegrate baru dibuat fungsi dan um di assign
		$rs= $this->Model->getBannerHeader();
		$this->View->assign("show_banner_header",$rs);
		return $this->View->toString("Banner/headerbanner.html");
	}
	
	function  manage_banner($total_per_page=6){
		$this->open(0);
		$start = $this->Request->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT COUNT(*) as total FROM tbl_banner LIMIT 1");
		$total = $r["total"];
		
	//	var_dump($this->Model->getListBanner());
	$this->Paging = new Paginate();	
			$this->View->assign("list_banner",$this->Model->getListBanner($start,$total_per_page));
		$this->View->assign("paging",$this->Paging->generate($start, $total_per_page, $total, "?s=banner"));
		$this->close();
		
		return $this->View->toString("Banner/admin/manage_banner.html");
	}
	
	function form_insert_banner(){		
		return $this->View->toString("Banner/admin/insert_form.html");
	}
	
	function getUpdateBanner(){
		$id = $this->Request->getParam("id");
		if($this->Model->selectdelBanner($id)){
			$this->View->assign("show_banner",$this->Model->selectdelBanner($id));
			//var_dump($this->Model->selectdelBanner($id));
			return $this->View->toString("Banner/admin/update_form.html");
		}else{	
			$msg = "tidak menemukan data yang anda cari";
			return $this->View->showMessage($msg,"index.php?s=banner");
		}
	}
	
	function update_banner(){
		global $ENGINE_PATH;
		include_once $ENGINE_PATH."Utility/Thumbnail.php"; //ENGINE PATH UNTUK thumbnail		
		$thumb = new Thumbnail(); 
		$id = $this->Request->getPost('id');	
		$nama = $this->Request->getPost('txtnama');
		$status = $this->Request->getPost('rdostatus');
		$img=$_FILES["txtimg"]["name"];		// metode untuk memanggil file, bukan dengan getPost dan getParam
		$hiddenimg= $this->Request->getPost('hiddentxtimg');
		$position = $this->Request->getPost('rdoposition');
		$rtype = $this->Request->getPost('rtype');
		$url = $this->Request->getPost('txturl');
		
		
		if ($nama==""){
			return $this->View->showMessage("Masukkan judul yang akan diinsert", "index.php?s=banner");
		}
		elseif ($status==""){
			return $this->View->showMessage("Pilih status image yang akan diinsert", "index.php?s=banner");
		}
		elseif($url==""){
			return $this->View->showMessage("Masukkan Link URL", "index.php?s=banner");
		}else{
			
			if ($img==""){
	    		if($this->Model->updateBannerblank($id,$nama,$status,$url,$rtype)){
					$msg = "Banner berhasil diupdate tanpa perubahan image dan position";}
				else{
					$msg = "Banner tidak berhasil diupdate";}
					return $this->View->showMessage($msg, "index.php?s=banner"); 
				}
			else{
			
				if($rtype=='image'){
					
					list($width, $height, $type, $attr) = getimagesize( $_FILES['txtimg']['tmp_name'] );
					if(!is_dir("../contents")){
						mkdir("../contents");
					}
					if(!is_dir("../contents/banner")){
						mkdir("../contents/banner");
					}
					
					if(eregi(".jpg",$_FILES['txtimg']['name'])){
						$name = md5($_FILES['txtimg']['name'].rand(1000,9999)).".jpg";
					
						if(move_uploaded_file($_FILES['txtimg']['tmp_name'],"../contents/banner/".$name)){	
						
							if ($position==0){
								if($width<970 or $height<170){
									@unlink("../contents/banner/".$name);
									$msg = "Gagal minimal resolusi 970x170, silakan coba kembali!";
									return $this->View->showMessage($msg, "index.php?s=banner");
								}
								require_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
								try{ $thumb2 = PhpThumbFactory::create( "../contents/banner/".$name ); }catch (Exception $e){}
								try{ $thumb3 = PhpThumbFactory::create( "../contents/banner/".$name ); }catch (Exception $e){}
								
								$thumb2->crop(0,0,970, 170);
								$thumb3->crop(0,0,291, 51);
								if( $thumb2->save("../contents/banner/".$name) && $thumb3->save("../contents/banner/small_".$name)){
								
										if($this->Model->updateBanner($id,$nama,$status,$name,$url,$position,$rtype)){
								
											@unlink("../contents/banner/".$hiddenimg);
											@unlink("../contents/banner/small_".$hiddenimg);
											$msg = "Banner Header berhasil diupdate";
											return $this->View->showMessage($msg, "index.php?s=banner");
									
										}else{
								
											$msg = "Banner tidak berhasil diupdate";
											@unlink("../contents/banner/".$name);
											@unlink("../contents/banner/small_".$name);
										}
								}else{
									$msg = "Maaf, gagal dalam membuat thumbnail.";
								}
							}elseif ($position==1){
								
								if($width<240 or $height<200){
									@unlink("../contents/banner/".$name);
									$msg = "Gagal minimal resolusi 240x200, silakan coba kembali!";
									return $this->View->showMessage($msg, "index.php?s=banner");
								}
								require_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
								try{ $thumb2 = PhpThumbFactory::create( "../contents/banner/".$name ); }catch (Exception $e){}
								try{ $thumb3 = PhpThumbFactory::create( "../contents/banner/".$name ); }catch (Exception $e){}
								$thumb2->crop(0,0,240,200);
								$thumb3->crop(0,0,72,60);
								
								if($thumb2->save("../contents/banner/".$name) && $thumb3->save("../contents/banner/small_".$name)){
										if($this->Model->updateBanner($id,$nama,$status,$name,$url,$position,$rtype)){
								
											@unlink("../contents/banner/".$hiddenimg);
											@unlink("../contents/banner/small_".$hiddenimg);
											$msg = "Banner Right Side berhasil diupdate";
											return $this->View->showMessage($msg, "index.php?s=banner");
									
										}else{
								
											$msg = "Banner tidak berhasil diupdate";
											@unlink("../contents/banner/".$name);
											@unlink("../contents/banner/small_".$name);
										}
								}else{
									$msg = "Maaf, gagal dalam membuat thumbnail.";
								}
							}
						}else{
							$msg = "Gagal upload file, silakan coba kembali!";
							//Print "failed move upload file";	
						}
					}else{
						$msg = "Gagal file anda bukan JPG, silakan coba kembali!";
						//print "bukan jpeg atau salah di MD5";	
					}
				}else{
					list($width, $height, $type, $attr) = getimagesize( $_FILES['txtimg']['tmp_name'] );
					if(!is_dir("../contents")){
						mkdir("../contents");
					}
					if(!is_dir("../contents/banner")){
						mkdir("../contents/banner");
					}
					
					if(eregi(".swf",$_FILES['txtimg']['name'])){
						$name = md5($_FILES['txtimg']['name'].rand(1000,9999)).".swf";
					
						if(move_uploaded_file($_FILES['txtimg']['tmp_name'],"../contents/banner/".$name)){	
						
							if ($position==0){
								if($width!=970 or $height!=170){
									@unlink("../contents/banner/".$name);
									$msg = "Gagal resolusi harus 970x170, silakan coba kembali!";
									return $this->View->showMessage($msg, "index.php?s=banner");
								}
								if($this->Model->updateBanner($id,$nama,$status,$name,$url,$position,$rtype)){
									$msg = "Banner Header berhasil diupdate";
									@unlink("../contents/banner/".$hiddenimg);
									return $this->View->showMessage($msg, "index.php?s=banner");
								}else{
									@unlink("../contents/banner/".$name);
									$msg = "Banner tidak berhasil diupdate";
								}
							}elseif ($position==1){
								if($width!=240 or $height!=200){
									@unlink("../contents/banner/".$name);
									$msg = "Gagal resolusi harus 240x200, silakan coba kembali!";
									return $this->View->showMessage($msg, "index.php?s=banner");
								}
								if($this->Model->updateBanner($id,$nama,$status,$name,$url,$position,$rtype)){
									@unlink("../contents/banner/".$hiddenimg);
									$msg = "Banner Right Side berhasil diupdate";
									return $this->View->showMessage($msg, "index.php?s=banner");
								}else{
									$msg = "Banner tidak berhasil diupdate";
									@unlink("../contents/banner/".$name);
								}
							}
						}else{
							$msg = "Gagal upload file, silakan coba kembali!";
						}
					}else{
						$msg = "Gagal file anda bukan SWF, silakan coba kembali!";
					}
				}
			return $this->View->showMessage($msg, "index.php?s=banner");		
		}
	}
	}
	
	function delete_banner(){
		$id = $this->Request->getParam("id");
		
		$data_lama = $this->Model->selectdelBanner($id);
		$old_file = "../contents/banner/".$data_lama['file'];
		$old_file_small = "../contents/banner/small_".$data_lama['file'];
	//	var_dump($old_file_small);
		if($this->Model->deleteBanner($id)){
			@unlink($old_file);
			@unlink($old_file_small);
			$msg = "Banner berhasil dihapus";
		}else{
			$msg = "Banner tidak berhasil dihapus";
		}
		return $this->View->showMessage($msg, "index.php?s=banner"); 	
	}
	
	function insert_banner(){
		global $ENGINE_PATH;
		include_once $ENGINE_PATH."Utility/Thumbnail.php"; //ENGINE PATH UNTUK thumbnail		
		$thumb = new Thumbnail(); 
				
		$judul = $this->Request->getPost('txtjudul');
		$status = $this->Request->getPost('rdostatus');
		$position = $this->Request->getPost('rdoposition');
		$rtype = $this->Request->getPost('rtype');
		$url= $this->Request->getPost('txturl');
		
		$img=$_FILES["txtimg"]["name"];
		
		if ($judul==""){
			return $this->View->showMessage("Masukkan judul yang akan diinsert", "index.php?s=banner");
		}
		elseif ($status==""){
			return $this->View->showMessage("Pilih status image yang akan diinsert", "index.php?s=banner");
		}
		elseif ($position==""){
			return $this->View->showMessage("Pilih Position image yang akan diinsert", "index.php?s=banner");
		}
		elseif ($url==""){
			return $this->View->showMessage("Masukkan URL yang akan dituju", "index.php?s=banner");
		}
		elseif ($img==""){
			return $this->View->showMessage("Masukkan Gambar yang akan diinsert", "index.php?s=banner");
		}
		else {
			$newfile = "../../contents/banner/".$_FILES['txtimg']['name'];
		
	    	if($rtype=='image'){
	    		list($width, $height, $type, $attr) = getimagesize( $_FILES['txtimg']['tmp_name'] );
	    		if(!is_dir("../contents")){
					mkdir("../contents");
				}
				if(!is_dir("../contents/banner")){
					mkdir("../contents/banner");
				}
	    		
	    		if(eregi(".jpg",$_FILES['txtimg']['name'])){
					$name = md5($_FILES['txtimg']['name'].rand(1000,9999)).".jpg";
					if(move_uploaded_file($_FILES['txtimg']['tmp_name'],"../contents/banner/".$name)){
						if($position==0){
							if($width<970 or $height<170){
								@unlink("../contents/banner/".$name);
								$msg = "Gagal minimal resolusi 970x170, silakan coba kembali!";
								return $this->View->showMessage($msg, "index.php?s=banner");
							}
							require_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
							try{ $thumb2 = PhpThumbFactory::create( "../contents/banner/".$name ); }catch (Exception $e){}
							try{ $thumb3 = PhpThumbFactory::create( "../contents/banner/".$name ); }catch (Exception $e){}
								
							$thumb2->crop(0,0,970, 170);
							$thumb3->crop(0,0,291, 51);
							if( $thumb2->save("../contents/banner/".$name) && $thumb3->save("../contents/banner/small_".$name)){
								if($this->Model->insertBanner($judul,$status,$name,$url,$position,$rtype)){
									$msg = "Banner berhasil diinsert";
									return $this->View->showMessage($msg, "index.php?s=banner");
								}else{
									$msg = "Banner tidak berhasil diinsert";
									@unlink("../contents/banner/".$name);
									@unlink("../contents/banner/small_".$name);
								}
							}else{
								$msg = "Maaf, gagal memproses gambar anda.";
							}
						}elseif($position==1){
							if($width<240 or $height<200){
								@unlink("../contents/banner/".$name);
								$msg = "Gagal minimal resolusi 240x200, silakan coba kembali!";
								return $this->View->showMessage($msg, "index.php?s=banner");
							}
							require_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
							try{ $thumb2 = PhpThumbFactory::create( "../contents/banner/".$name ); }catch (Exception $e){}
							try{ $thumb3 = PhpThumbFactory::create( "../contents/banner/".$name ); }catch (Exception $e){}
							$thumb2->crop(0,0,240,200);
							$thumb3->crop(0,0,72,60);
								
							if($thumb2->save("../contents/banner/".$name) && $thumb3->save("../contents/banner/small_".$name)){
								if($this->Model->insertBanner($judul,$status,$name,$url,$position,$rtype)){
									$msg = "Banner berhasil diinsert";
									return $this->View->showMessage($msg, "index.php?s=banner");
								}else{
									$msg = "Banner tidak berhasil diinsert";
									@unlink("../contents/banner/".$name);
									@unlink("../contents/banner/small_".$name);
								}
							}else{
								$msg = "Maaf, gagal memproses gambar anda.";
							}
						}
					}else{
						$msg = "Gagal mengupload file, silakan ulangi kembali!";	
					}
				}else{
					$msg = "Gagal file anda bukan JPG, silakan ulangi kembali!";	
				}
			}else{
				list($width, $height, $type, $attr) = getimagesize( $_FILES['txtimg']['tmp_name'] );
				if(!is_dir("../contents")){
					mkdir("../contents");
				}
				if(!is_dir("../contents/banner")){
					mkdir("../contents/banner");
				}
					
					if(eregi(".swf",$_FILES['txtimg']['name'])){
						$name = md5($_FILES['txtimg']['name'].rand(1000,9999)).".swf";
					
						if(move_uploaded_file($_FILES['txtimg']['tmp_name'],"../contents/banner/".$name)){	
						
							if ($position==0){
								if($width!=970 or $height!=170){
									@unlink("../contents/banner/".$name);
									$msg = "Gagal resolusi harus 970x170, silakan coba kembali!";
									return $this->View->showMessage($msg, "index.php?s=banner");
								}
								if($this->Model->insertBanner($judul,$status,$name,$url,$position,$rtype)){
									$msg = "Banner Header berhasil disimpan";
									return $this->View->showMessage($msg, "index.php?s=banner");
								}else{
									@unlink("../contents/banner/".$name);
									$msg = "Banner tidak berhasil disimpan";
								}
							}elseif ($position==1){
								if($width!=240 or $height!=200){
									@unlink("../contents/banner/".$name);
									$msg = "Gagal resolusi harus 240x200, silakan coba kembali!";
									return $this->View->showMessage($msg, "index.php?s=banner");
								}
								if($this->Model->insertBanner($judul,$status,$name,$url,$position,$rtype)){
									$msg = "Banner Right Side berhasil disimpan";
									return $this->View->showMessage($msg, "index.php?s=banner");
								}else{
									$msg = "Banner tidak berhasil disimpan";
									@unlink("../contents/banner/".$name);
								}
							}
						}else{
							$msg = "Gagal upload file, silakan coba kembali!";
						}
					}else{
						$msg = "Gagal file anda bukan SWF, silakan coba kembali!";
					}
			}
			return $this->View->showMessage($msg, "index.php?s=banner");		
		}
	}
}
	//}
?>
