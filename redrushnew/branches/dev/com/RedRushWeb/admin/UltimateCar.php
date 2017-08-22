<?php
/*
 *	M.Babar Jihad
 *	09 Maret 2012
 * 	
 */
include_once $ENGINE_PATH."Admin/UserManager.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once APP_PATH.APPLICATION."/models/UltimateCarModel.php";
include_once APP_PATH.APPLICATION."/helper/trashHelper.php";
class UltimateCar extends SQLData{
	var $fronList = array();
	var $frontPaging;
	var $ftitle;
	var $fdetail;
	var $fdate;
	var $_msg='';
	var $_imgNum=1;
	var $_img=array();
	var $_param = 'ultimate-car';
	var $model;
	var $trash;
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->model = new UltimateCarModel();
		$this->trash = new trashHelper();
		if($req){
			$this->User = new UserManager();
		}
	}
	function admin(){
		$req = $this->Request;
		if( $req->getParam('act') == 'add'){
			return $this->addCar($req);
		}elseif( $req->getParam('act') == 'edit'){
			return $this->editCar($req);
		}elseif( $req->getParam('act') == 'change-status'){
			return $this->changeStatus($req);
		}elseif( $req->getParam('act') == 'delete'){
			return $this->delete($req);
		}elseif( $req->getParam('act') == 'cekid'){
			return $this->cekID($req);
		}else{
			return $this->listCar($req);
		}
	}
	
	/* ADD NEW ULTIMATE CAR*/
	function addCar(){
		$save = $this->Request->getPost('save');
		
		if($save==1){
			$id = $this->Request->getPost('id');
			$name = $this->Request->getPost('name');
			$level = $this->Request->getPost('level');
			$persen = $this->Request->getPost('persentase');
			$status = $this->Request->getPost('status');
			
			// primary image
			$img_name = $_FILES['images']['name'];
			$img_loc = $_FILES['images']['tmp_name'];
			$img_type = $_FILES['images']['type'];
			$img_newname = "ultimate_".date('YmdHis');
			
			//declare extension primary image
			if($img_type=='image/jpeg'){$ext = '.jpg';}
			if($img_type=='image/png'){$ext = '.png';}
			if($img_type=='image/gif'){$ext = '.gif';}
			
			// new file
			$newfile = $img_newname.$ext; // new file name for primary image
			$thumbfile = "thumb_".$newfile; // new file name for thumbnail image
			$folder = "../public_html/contents/avatar/original/";
			$thumbfolder = "../public_html/contents/avatar/small/";
			//echo $newfile;
			
			$form = array("id"=>$id,"name"=>$name,"level"=>$level,"persentase"=>$persen,"status"=>$status);
			$this->View->assign('form',$form);
			
			if($id == '' && $name == '' && $level == '' && $persen == '' && $img_name == ''){
				$err = 'Please Complete the form!';
			}else{
				if(!is_numeric($id)){
					$err = "ID must be a numeric!";
					//return false;
				}
				elseif(!is_numeric($level)){
					$err = "Level must be a numeric!";
					//return false;
				}
				elseif(!is_numeric($persen)){
					$err = "Persentase must be a numeric!";
					//return false;
				}
				elseif($ext!='.jpg' && $ext!='.png' && $ext!='.gif'){
					$err = "Invalid file type! (Allowed: *.jpg, *.gif, *.png)";//return false;
				}
				else{
					global $ENGINE_PATH;
					include_once $ENGINE_PATH."Utility/Thumbnail.php";	
					$thumb 	= new Thumbnail();
					if(move_uploaded_file($img_loc,$folder.$newfile)){
						if($thumb->createThumbnail($folder.$newfile,$thumbfolder.$thumbfile,64,64)){
							$data['id'] = $id;
							$data['name'] = $name;
							$data['level'] = $level;
							$data['persen'] = $persen;
							$data['img'] = $newfile;
							$data['thumb'] = $thumbfile;
							$data['status'] = $status;
							$q	= $this->model->addCar($data);
							if($q){
								$msg = "Success add new ultimate car!";
							}
							else{
								$err = mysql_error();
								$err = "failed add new car! $err";
								@unlink($folder.$newfile);
								@unlink($folder.$thumbfile);
							}
							$this->close();
							return $this->View->showMessage($msg, "index.php?s=ultimate-car");
						}
						else{ 
								$err = "failed processing the image"; 
								@unlink($folder.$newfile);
						}
					}
					else{
						$err = "failed move upload file";
					}
				}
				
			}
			
		}
		$this->View->assign('err',$err);
		return $this->View->toString(APPLICATION."/admin/UltimateCar/add.html");
	}
	
	/* CEK ID */
	function cekID(){
		$id = $this->Request->getParam('id');
		if($id==''){
			return false;
		}else{
			$this->open(0);
			$q = "SELECT COUNT(ultimate_id) total FROM ".DB_PREFIX."_ultimate_car WHERE ultimate_id='".$id."';";
			$r = $this->fetch($q);
			$r = $r['total'];
			$this->close();
			echo $r;
			exit;
		}
	}
	
	function editCar(){
		$id = $this->Request->getParam('id');
		$list = $this->model->getCarByID($id);
		$form = array("name"=>$list['name'],"level"=>$list['level'],"persentase"=>$list['persentase_lose'],"img"=>$list['img'],"status"=>$list['n_status']);
		$save = $this->Request->getPost('save');
		
		if($save==1){
			$name = $this->Request->getPost('name');
			$level = $this->Request->getPost('level');
			$persen = $this->Request->getPost('persentase');
			$status = $this->Request->getPost('status');
			
			//echo $newfile;
			$form = array("name"=>$name,"level"=>$level,"persentase"=>$list['persentase_lose'],"img"=>$list['img'],"status"=>$list['n_status']);
			
			if($name == '' && $level == '' && $persen == ''){
				$err = 'Please Complete the form!';
			}else{
				if(!is_numeric($level)){
					$err = "Level must be a numeric!";
				}elseif(!is_numeric($persen)){
					$err = "Persentase must be a numeric!";
					//return false;
				}
				else{
					if(empty($_FILES['images']['name'])){
						$data['name'] = $name;
						$data['level'] = $level;
						$data['persen'] = $persen;
						$data['status'] = $status;
						$q	= $this->model->editCar($id,$data,0);
						if($q){
								$msg = "Success edit car!";
						}
						else{
							$err = mysql_error();
							$err = "failed edit car! $err";
						}
						return $this->View->showMessage($msg, "index.php?s=ultimate-car");
						
					}else{
						// primary images data
						$img_name = $_FILES['images']['name'];
						$img_loc = $_FILES['images']['tmp_name'];
						$img_type = $_FILES['images']['type'];
						$img_newname = "ultimate_".date('YmdHis');
						
						//declare extension
						if($img_type=='image/jpeg'){$ext = '.jpg';}
						if($img_type=='image/png'){$ext = '.png';}
						if($img_type=='image/gif'){$ext = '.gif';}
						
						$newfile = $img_newname.$ext; // new images name
						$thumbfile = "thumb_".$newfile; // new thumbnail name
						
						if($ext!='.jpg' && $ext!='.png' && $ext!='.gif'){
							$err = "Invalid file type! (Allowed: *.jpg, *.gif, *.png)";//return false;
						}else{
							global $ENGINE_PATH;
							include_once $ENGINE_PATH."Utility/Thumbnail.php";	
							$thumb 	= new Thumbnail();
							
							$folder = "../public_html/contents/avatar/original/";
							$thumbfolder = "../public_html/contents/avatar/small/";
							$nfolder = "public_html/contents/avatar/original/";
							$nfolder2 = "public_html/contents/avatar/small/";
							
							if(move_uploaded_file($img_loc,$folder.$newfile)){
								if($thumb->createThumbnail($folder.$newfile,$thumbfolder.$thumbfile,64,64)){
									$data['name'] = $name;
									$data['level'] = $level;
									$data['persen'] = $persen;
									$data['img'] = $newfile;
									$data['thumb'] = $thumbfile;
									$data['status'] = $status;
									$q	= $this->model->editCar($id,$data,1);
									if($q){
										$msg = "Success edit car!";
										$this->trash->execute($folder,$list['img'],$nfolder); // copy file ke trash
										$this->trash->execute($thumbfolder,$list['small_img'],$nfolder2); // copy file ke trash
										@unlink($folder.$list['img']);
										@unlink($thumbfolder.$list['small_img']);
									}
									else{
										$err = mysql_error();
										$err = "failed edit car! $err";
									}
									return $this->View->showMessage($msg, "index.php?s=ultimate-car");
								}
								else{ 
										$err = "failed processing the image"; 
									}
							}
							else{
								$err = "failed move upload file";
							}
						}
					}
				}
				
			}
			
		}
		$this->View->assign('form',$form);
		$this->View->assign('err',$err);
		return $this->View->toString(APPLICATION."/admin/UltimateCar/edit.html");
	}
	
	function listCar($req,$total_per_page=50){
		
		$start = $this->Request->getParam("st");
		$this->Request->getParam("q")  != '' ? $q = " && rr_ultimate_car.name LIKE '%".$this->Request->getParam("q")."%'" : $q = "";
		
		if($start==NULL){$start = 0;}
		$list=$this->model->getCarList($start,$total_per_page,$q);
		$total = $this->model->countAll($q);
		
		$this->View->assign("q",$this->Request->getParam("q") );
		$this->View->assign("list",$list);
		$this->Paging = new Paginate();
		$page = $this->Paging->getAdminPaging($start,$total_per_page,$total,'index.php?s=ultimate-car&q='.$this->Request->getParam("q"));
		$this->View->assign("paging",$page);
		return $this->View->toString(APPLICATION."/admin/UltimateCar/list.html");
	}
	
	function changeStatus(){
		$id = intval($this->Request->getParam('id'));
		$status = intval($this->Request->getParam('status'));
		if($id==null && $status==null){
			return $this->listCar();
		}else{
			$r = $this->model->changeStatus($id,$status);
			if($r){
				sendRedirect('index.php?s=ultimate-car');exit;
			}else{
				sendRedirect('index.php?s=ultimate-car');exit;
			}
		}
	}
	
	function delete(){
		$id = intval($this->Request->getParam('id'));
		if($id==null){
			return $this->listCar();
		}else{
			$i = "SELECT img,small_img FROM ".RedRushDB.".rr_ultimate_car WHERE ultimate_id='".$id."'";
			$this->open(0);
			$img = $this->fetch($i);
			//print_r($img);exit;
			$this->close();
			if(!empty($img['img']) && !empty($img['small_img'])){
				$folder1 = "../public_html/contents/avatar/original/";
				$nfolder1 = "public_html/contents/avatar/original/";
				$folder2 = "../public_html/contents/avatar/small/";
				$nfolder2 = "public_html/contents/avatar/small/";
				$this->trash->execute($folder1,$img['img'],$nfolder1); // copy file ke trash
				$this->trash->execute($folder2,$img['small_img'],$nfolder2); // copy file ke trash
				@unlink($folder1.$img['img']);
				@unlink($folder2.$img['small_img']);
			}
			$r = $this->model->deleteCar($id);
			if($r){
				sendRedirect('index.php?s=ultimate-car');exit;
			}else{
				sendRedirect('index.php?s=ultimate-car');exit;
			}
		}
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
