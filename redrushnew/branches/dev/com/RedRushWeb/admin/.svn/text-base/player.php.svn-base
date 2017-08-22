<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once APP_PATH.APPLICATION."/models/PlayerModel.php";
include_once APP_PATH.APPLICATION."/helper/trashHelper.php";
class player extends SQLData{
	var $model;
	var $trash;
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->model = new PlayerModel();
		$this->trash = new trashHelper();
	}
	function admin(){
		$act = $this->Request->getParam('act');
		if( $act == 'edit' ){
			return $this->editPlayer();
		}elseif($act == 'hapus_avatar' ) {
			return $this->hapus_avatar();
		}else{
			return $this->PlayerList();
		}
	}
	
	function PlayerList($req,$total_per_page=50){
		
		$start = $this->Request->getParam("st");
		$this->Request->getParam("q")  != '' ? $q = "&& ( kana_member.name LIKE '%".$this->Request->getParam("q")."%' OR kana_member.nickname  LIKE '%".$this->Request->getParam("q")."%')" : $q = "";
		
		if($start==NULL){$start = 0;}
		$list=$this->model->getPlayer($start,$total_per_page,$q);
		$total = $this->model->countAll($q);
		
		$this->View->assign("q",$this->Request->getParam("q") );
		$this->View->assign("list",$list);
		$this->Paging = new Paginate();
		$page = $this->Paging->getAdminPaging($start,$total_per_page,$total,'index.php?s=player&q='.$this->Request->getParam("q"));
		$this->View->assign("paging",$page);
		$this->View->assign("BASEURL",BASEURL);
		return $this->View->toString(APPLICATION."/admin/player/list.html");
	}
	
	function editPlayer(){
		$id = $this->Request->getParam('id');
		$list = $this->model->getPlayerByID($id);
		$form = array("img"=>$list['img'],"nickname"=>$list['nickname']);
		$save = $this->Request->getPost('save');
		
		if($save==1){
			
			if(empty($_FILES['images']['name'])){
				$err = "Please complete the form!";
			}else{
				$img_name = $_FILES['images']['name'];
				$img_temp = $_FILES['images']['tmp_name'];
				$img_size = $_FILES['images']['size'];
				$fileParts  = pathinfo($_FILES['images']['name']);
				$extension = ".".strtolower($fileParts['extension']);
				$new_img = sha1(date('Ymd').$img_name).$extension;
				// echo $new_img."<br>";
				// print_r($_FILES);exit;
				
				// cek file extension
				if($extension!='.jpg' && $extension!='.png' && $extension!='.gif'){
					$err = "Invalid file type! (Allowed: *.jpg, *.gif, *.png)";//return false;
				}elseif($img_size > 250000){
					$err = "Invalid file size! (Allowed: Max.250Kb)";//return false;
				}else{
					$folder = "../public_html/contents/avatar/small/";
					$nfolder = "public_html/contents/avatar/small/";
					if(move_uploaded_file($img_temp,$folder.$new_img)){
						$q	= $this->model->editPlayer($id,$new_img,$new_img);
						if($q){
								$msg = "Success edit player!";
								$this->trash->execute($folder,$list['img'],$nfolder); // copy file ke trash
								$this->trash->execute($folder,"thumb_".$list['img'],$nfolder); // copy file ke trash
								@unlink($folder.$list['img']);
								@unlink($folder."thumb_".$list['img']);
						}
						else{
								$err = mysql_error();
								$err = "failed upload image! $err";
						}
						return $this->View->showMessage($msg, "index.php?s=player");
					}else{
						$err = "failed move upload file";
					}
				}
			}
		}
		$this->View->assign("BASEURL",BASEURL);
		$this->View->assign('form',$form);
		$this->View->assign('err',$err);
		return $this->View->toString(APPLICATION."/admin/player/edit.html");
	}
	
	function hapus_avatar(){
	$id = $this->Request->getParam('id');
	// echo $id;exit;
	$deleteAvatar = $this->model->deleteAvatarByID($id);
	sendRedirect('index.php?s=player');
	}
}
