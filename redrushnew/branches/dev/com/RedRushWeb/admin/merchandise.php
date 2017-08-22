<?php
/*
 *	M.Babar Jihad
 *	23 Februari 2012
 * 	
 */
// if(file_exists($ENGINE_PATH ."Utility/pdfHelper.php"))echo 'masuk';
// else echo 'ga ada file';
global $ENGINE_PATH;
require_once $ENGINE_PATH ."Utility/pdfHelper.php";
include_once $ENGINE_PATH."Admin/UserManager.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once APP_PATH.APPLICATION."/models/merchandiseModel.php";
include_once APP_PATH.APPLICATION."/helper/trashHelper.php";

class merchandise extends SQLData{
	var $fronList = array();
	var $frontPaging;
	var $ftitle;
	var $fdetail;
	var $fdate;
	var $_msg='';
	var $_imgNum=1;
	var $_img=array();
	var $_param = 'merchandise';
	var $model;
	var $trash;
	var $pdf;
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->model = new merchandiseModel();
		$this->trash = new trashHelper();
		$this->pdf = new pdfHelper();
		if($req){
			$this->User = new UserManager();
		}
	}
	function admin(){
		$req = $this->Request;
		if( $req->getParam('act') == 'add'){
			return $this->addMerch($req);
		}elseif( $req->getParam('act') == 'edit'){
			return $this->editMerch($req);
		}elseif( $req->getParam('act') == 'change-status'){
			return $this->changeStatus($req);
		}elseif( $req->getParam('act') == 'delete'){
			return $this->delete($req);
		}elseif( $req->getParam('act') == 'purchase-list'){
			return $this->listPurchase($req);
		}elseif( $req->getParam('act') == 'del-purchase'){
			return $this->deletePurchase($req);
		}elseif( $req->getParam('act') == 'change-purchase'){
			return $this->changePurchase($req);
		}elseif( $req->getParam('act') == 'addstock'){
			return $this->addstock($req);
		}elseif( $req->getParam('act') == 'liststock'){
			return $this->liststock($req);
		}elseif( $req->getParam('act') == 'deletestock'){
			return $this->deletestock($req);
		}elseif( $req->getParam('act') == 'editstock'){
			return $this->editstock($req);
		}elseif( $req->getParam('act') == 'getPO'){
			return $this->getPO($req);
		}else{
			return $this->listMerch($req);
		}
	}
	
	function test(){
		$folder = "../public_html/js/";
		$nfolder = "public_html/js";
		$file = 'popup.js'; 
		$this->trash->execute($folder,$file,$nfolder);
		//$this->trash->mkdir_array();
	}
	
	function addMerch(){
		$save = $this->Request->getPost('save');
		$getGroupMerchandise = $this->model->getGroupMerchandise();
		$this->View->assign('getGroupMerchandise',$getGroupMerchandise);
		
		if($save==1){
			$name = $this->Request->getPost('name');
			$amount = 0;
			if($this->Request->getPost('amount')) $amount = $this->Request->getPost('amount');
			$prize = 0;
			if($this->Request->getPost('prizes')) $prize = $this->Request->getPost('prizes');
			$desc = $this->Request->getPost('desc');
			$status = $this->Request->getPost('status');
			$level = $this->Request->getPost('level');
			$variant = $this->Request->getPost('variant');
			if($variant) $variant = 1;
			else $variant = 0;
			
			$group_merchandise = $name;
			if($this->Request->getPost('group_merchandise_pick')!='') $group_merchandise = $this->Request->getPost('group_merchandise_pick');
			if($this->Request->getPost('group_merchandise_create')!='') $group_merchandise = $this->Request->getPost('group_merchandise_create');			
			
			// primary image
			$img_name = $_FILES['images']['name'];
			$img_loc = $_FILES['images']['tmp_name'];
			$img_type = $_FILES['images']['type'];
			$img_newname = "merch_".date('YmdHis');
			
			// thumbnail image
			$timg_name = $_FILES['timages']['name'];
			$timg_loc = $_FILES['timages']['tmp_name'];
			$timg_type = $_FILES['timages']['type'];
			$timg_newname = "thumb_".$img_newname;
			
			//declare extension primary image
			if($img_type=='image/jpeg'){$ext = '.jpg';}
			if($img_type=='image/png'){$ext = '.png';}
			if($img_type=='image/gif'){$ext = '.gif';}
			
			//declare extension thumbnail image
			if($timg_type=='image/jpeg'){$t_ext = '.jpg';}
			if($timg_type=='image/png'){$t_ext = '.png';}
			if($timg_type=='image/gif'){$t_ext = '.gif';}
			
			// new file
			$newfile = $img_newname.$ext; // new file name for primary image
			$thumbfile = $timg_newname.$t_ext; // new file name for thumbnail image
			$folder = "../public_html/img/merchandise/";
			//echo $newfile;
			
			$form = array("name"=>$name,"amount"=>$amount,"prizes"=>$prize);
			$this->View->assign('form',$form);
			
			if($name == '' && $amount == '' && $prizes == '' && $desc == '' && $img_name == '' && $timg_name == ''){
				$err = 'Please Complete the form!';
			}else{
				if(!is_numeric($amount)){
					$err = "Amount must be a numeric!";
					//return false;
				}
				elseif(!is_numeric($prize)){
					$err = "Prizes must be a numeric!";
					//return false;
				}
				elseif($ext!='.jpg' && $ext!='.png' && $ext!='.gif'){
					$err = "Invalid file type! (Allowed: *.jpg, *.gif, *.png)";//return false;
				}
				elseif($t_ext!='.jpg' && $t_ext!='.png' && $t_ext!='.gif'){
					$err = "Invalid file type! (Allowed: *.jpg, *.gif, *.png)";//return false;
				}
				else{
					global $ENGINE_PATH;
					/* include_once $ENGINE_PATH."Utility/Thumbnail.php";	
					$thumb 	= new Thumbnail(); */
					if(move_uploaded_file($img_loc,$folder.$newfile)){
						if(move_uploaded_file($timg_loc,$folder.$thumbfile)){
							$data['name'] = $name;
							$data['amount'] = $amount;
							$data['prizes'] = $prize;
							$data['desc'] = $desc;
							$data['img'] = $newfile;
							$data['status'] = $status;
							$data['level'] = $level;
							$data['group_merchandise'] = $group_merchandise;
							$data['variant'] = $variant;
							
							$q	= $this->model->addMerchandise($data);
							if($q){
								$msg = "Success add new merchandise!";
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
							return $this->View->showMessage($msg, "index.php?s=merchandise");
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
		return $this->View->toString(APPLICATION."/admin/merchandise/merch_add.html");
	}
	
	function editMerch(){
		$id = $this->Request->getParam('id');
		$list = $this->model->getMerchandiseByID($id);
		$form = array("name"=>$list['item_name'],"amount"=>$list['amount'],"prizes"=>$list['prize'],"desc"=>$list['description'],"img"=>$list['img'],"timg"=>"thumb_".$list['img'],"status"=>$list['n_status'],"level"=>$list['level'],"group_merchandise"=>$list['group_merchandise'],"variant"=>$list['variant']);
		$save = $this->Request->getPost('save');
		
		$getGroupMerchandise = $this->model->getGroupMerchandise();
		$this->View->assign('getGroupMerchandise',$getGroupMerchandise);
		
		if($save==1){
			$name = $this->Request->getPost('name');
			$amount = 0;
			if($this->Request->getPost('amount')) $amount = $this->Request->getPost('amount');
			$prize =0;
			if($this->Request->getPost('prizes')) $prize = $this->Request->getPost('prizes');
			$desc = $this->Request->getPost('desc');
			$status = $this->Request->getPost('status');
			$level = $this->Request->getPost('level');
			$variant = $this->Request->getPost('variant');
			if($variant) $variant = 1;
			else $variant = 0;
			// print_r($this->Request->peek());exit;
			$group_merchandise = $name;
			if($this->Request->getPost('group_merchandise_pick')!='') $group_merchandise = $this->Request->getPost('group_merchandise_pick');
			if($this->Request->getPost('group_merchandise_create')!='') $group_merchandise = $this->Request->getPost('group_merchandise_create');			
			//echo $newfile;
			$form = array("name"=>$name,"amount"=>$amount,"prizes"=>$prize,"desc"=>$desc,"img"=>$list['img'],"timg"=>"thumb_".$list['img'],"status"=>$list['n_status'],"level"=>$level,"group_merchandise"=>$group_merchandise,"variant"=>$variant);
			
			if($name == '' && $amount == '' && $prizes == '' && $desc == ''){
				$err = 'Please Complete the form!';
			}else{
				if(!is_numeric($amount)){
					$err = "Amount must be a numeric!";
					
					//return false;
				}
				elseif(!is_numeric($prize)){
					$err = "Prizes must be a numeric!";
					//return false;
				}
				else{
					if(empty($_FILES['images']['name']) && empty($_FILES['timages']['name'])){
						$data['name'] = $name;
						$data['amount'] = $amount;
						$data['prize'] = $prize;
						$data['desc'] = $desc;
						$data['status'] = $status;
						$data['level'] = $level;
						$data['group_merchandise'] = $group_merchandise;
						$data['variant'] = $variant;
						
						
						$q	= $this->model->editMerchandise($id,$data,0);
						if($q){
								$msg = "Success edit merchandise!";
						}
						else{
							$err = mysql_error();
							$err = "failed upload image! $err";
						}
						return $this->View->showMessage($msg, "index.php?s=merchandise");
						
					}else{
						// primary images data
						$img_name = $_FILES['images']['name'];
						$img_loc = $_FILES['images']['tmp_name'];
						$img_type = $_FILES['images']['type'];
						$img_newname = "merch_".date('YmdHis');
						
						// thumbnail images data
						$timg_name = $_FILES['timages']['name'];
						$timg_loc = $_FILES['timages']['tmp_name'];
						$timg_type = $_FILES['timages']['type'];
						$timg_newname = "thumb_".$img_newname;
						
						//declare extension
						if($img_type=='image/jpeg'){$ext = '.jpg';}
						if($img_type=='image/png'){$ext = '.png';}
						if($img_type=='image/gif'){$ext = '.gif';}
						//declare thumbnail extension
						if($timg_type=='image/jpeg'){$t_ext = '.jpg';}
						if($timg_type=='image/png'){$t_ext = '.png';}
						if($timg_type=='image/gif'){$t_ext = '.gif';}
						
						$newfile = $img_newname.$ext; // new images name
						$thumbfile = $timg_newname.$t_ext; // new thumbnail name
						
						if($ext!='.jpg' && $ext!='.png' && $ext!='.gif'){
							$err = "Invalid file type! (Allowed: *.jpg, *.gif, *.png)";//return false;
						}elseif($t_ext!='.jpg' && $t_ext!='.png' && $t_ext!='.gif'){
							$err = "Invalid file type! (Allowed: *.jpg, *.gif, *.png)";//return false;
						}else{
							/* global $ENGINE_PATH;
							include_once $ENGINE_PATH."Utility/Thumbnail.php";	
							$thumb 	= new Thumbnail(); */
							$folder = "../public_html/img/merchandise/";
							$nfolder = "public_html/img/merchandise/";
							if(move_uploaded_file($img_loc,$folder.$newfile)){
								if(move_uploaded_file($timg_loc,$folder.$thumbfile)){
									$data['name'] = $name;
									$data['amount'] = $amount;
									$data['prize'] = $prize;
									$data['desc'] = $desc;
									$data['img'] = $newfile;
									$data['status'] = $status;
									$data['level'] = $level;
									$data['group_merchandise'] = $group_merchandise;
									$data['variant'] = $variant;
										
									$q	= $this->model->editMerchandise($id,$data,1);
									if($q){
										$msg = "Success edit merchandise!";
										$this->trash->execute($folder,$list['img'],$nfolder); // copy file ke trash
										$this->trash->execute($folder,"thumb_".$list['img'],$nfolder); // copy file ke trash
										@unlink($folder.$list['img']);
										@unlink($folder."thumb_".$list['img']);
									}
									else{
										$err = mysql_error();
										$err = "failed upload image! $err";
									}
									return $this->View->showMessage($msg, "index.php?s=merchandise");
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
		$this->View->assign("base_url",BASEURL);
		$this->View->assign('form',$form);
		$this->View->assign('err',$err);
		return $this->View->toString(APPLICATION."/admin/merchandise/merch_edit.html");
	}
	
	function listMerch($req,$total_per_page=50){
	
		$start = $this->Request->getParam("st");
		$this->Request->getParam("q")  != '' ? $q = " && rr_merchandise.item_name LIKE '%".$this->Request->getParam("q")."%'" : $q = "";
		
		if($start==NULL){$start = 0;}
		$list=$this->model->getMerchandise($start,$total_per_page,$q);
		$total = $this->model->countAll($q);
	
		$n = 0; 		
		foreach($list as $val){
				
		$arrMerch[$val['group_merchandise']][$val['id']]['amount'] = $val['amount'];
		$arrMerch[$val['group_merchandise']][$val['id']]['prize'] = $val['prize'];
		$arrMerch[$val['group_merchandise']][$val['id']]['item_name'] = $val['item_name'];
		$arrMerch[$val['group_merchandise']][$val['id']]['description'] = $val['description'];
		$arrMerch[$val['group_merchandise']][$val['id']]['img'] = $val['img'];
		$arrMerch[$val['group_merchandise']][$val['id']]['created_date'] = $val['created_date'];
		$arrMerch[$val['group_merchandise']][$val['id']]['n_status'] = $val['n_status'];
		$arrMerch[$val['group_merchandise']][$val['id']]['level'] = $val['level'];
		$arrMerch[$val['group_merchandise']][$val['id']]['variant'] = $val['variant'];
		$arrMerch[$val['group_merchandise']][$val['id']]['display_item'] = $val['display_item'];
		$n++;
		}
		
		// print_r('<pre>');print_r($arrMerch);exit;
		$this->View->assign("q",$this->Request->getParam("q") );
		$this->View->assign("list",$arrMerch);
		$this->View->assign("base_url",BASEURL);
		
		$this->Paging = new Paginate();
		$page = $this->Paging->getAdminPaging($start,$total_per_page,$total,'index.php?s=merchandise&q='.$this->Request->getParam("q"));
		$this->View->assign("paging",$page);
		return $this->View->toString(APPLICATION."/admin/merchandise/merch_list.html");
	}
	
	function changeStatus(){
		$id = intval($this->Request->getParam('id'));
		$status = intval($this->Request->getParam('status'));
		if($id==null && $status==null){
			return $this->listMerch();
		}else{
			$r = $this->model->changeStatus($id,$status);
			if($r){
				sendRedirect('index.php?s=merchandise');exit;
			}else{
				sendRedirect('index.php?s=merchandise');exit;
			}
		}
	}
	
	function addstock(){
		$id = $this->Request->getParam('id');
		if($id){
		$save = $this->Request->getPost('save');
		$merch = $this->model->getMerchName($id);
		$this->View->assign('merch',$merch);
		
		if($save==1){
			$amount = $this->Request->getPost('amount');
			$prize = $this->Request->getPost('prizes');
			$date = $this->Request->getPost('datee');
			$date = date('Y-m-d', strtotime($date));
			// echo $date;exit;
			$status = $this->Request->getPost('status');
			if($amount=='' || $prize=='' || $date==''){
				$err = 'Please complete the form!';
			}elseif(!is_numeric($amount)){
				$err = 'Amount must be numeric!';
			}elseif(!is_numeric($prize)){
				$err = 'Prize must be numeric!';
			}else{
				$data = array("id"=>$id,"amount"=>$amount,"prize"=>$prize,"date"=>$date,"status"=>$status);
				// print_r($data);exit;
				$input = $this->model->InputStock($data);
				if($input){$err = 'Success input stock!';sendRedirect('index.php?s=merchandise&act=liststock&id='.$id);}
				else{$err = 'Sorry, failed to input stock! Try again later.';}
			}
		}
		$this->View->assign('form',$form);
		$this->View->assign('err',$err);
		$this->View->assign('id',$id);
		return $this->View->toString(APPLICATION."/admin/merchandise/add_stock.html");
		}else{
			sendRedirect('index.php?s=merchandise');exit;
		}
	}
	
	function editstock(){
		$id = $this->Request->getParam('id');
		$mid = $this->Request->getParam('mid');
		if($id!='' && $mid!=''){
		$save = $this->Request->getPost('save');
		$data = $this->model->getStock($id,$mid);
		// print_r($data);exit;
		$merch = $data['merch'];
		$amount = $data['amount'];
		$prize = $data['prize'];
		$date = date('m/d/Y',strtotime($data['date']));
		$status = $data['n_status'];
		if($save==1){
			$amount = $this->Request->getPost('amount');
			$prize = $this->Request->getPost('prizes');
			$date = $this->Request->getPost('datee');
			$date = date('Y-m-d', strtotime($date));
			$status = $this->Request->getPost('status');
			if($amount=='' || $prize=='' || $date==''){
				$err = 'Please complete the form!';
			}elseif(!is_numeric($amount)){
				$err = 'Amount must be numeric!';
			}elseif(!is_numeric($prize)){
				$err = 'Prize must be numeric!';
			}else{
				$datas = array("id"=>$id,"amount"=>$amount,"prize"=>$prize,"date"=>$date,"status"=>$status);
				$input = $this->model->EditStock($datas);
				if($input){$err = 'Success edit stock!';sendRedirect('index.php?s=merchandise&act=liststock&id='.$mid);}
				else{$err = 'Sorry, failed to edit stock! Try again later.';}
			}
		}

		$this->View->assign('merch',$merch);
		$this->View->assign('amount',$amount);
		$this->View->assign('prizes',$prize);
		$this->View->assign('date',$date);
		$this->View->assign('status',$status);
		$this->View->assign('err',$err);
		$this->View->assign('mid',$mid);
		return $this->View->toString(APPLICATION."/admin/merchandise/edit_stock.html");
		}else{
			sendRedirect('index.php?s=merchandise');exit;
		}
	}
	
	function liststock(){
		$id = $this->Request->getParam('id');
		$this->View->assign("base_url",BASEURL);
		if($id){
			$this->View->assign("id",$id);
			$start = $this->Request->getParam("st");
			if($start==NULL){$start = 0;}
			$list=$this->model->getMerchStock($start,10,$id);
			$total = $this->model->countAllStock($id);
			// print_r($list);exit;
			$this->View->assign("list",$list);
			$this->Paging = new Paginate();
			$page = $this->Paging->getAdminPaging($start,10,$total,'index.php?s=merchandise&act=liststock&id='.$id);
			$this->View->assign("paging",$page);
			return $this->View->toString(APPLICATION."/admin/merchandise/stock_list.html");
		}else{
			sendRedirect('index.php?s=merchandise');exit;
		}
	}
	
	function deletestock(){
		$id = $this->Request->getParam('id');
		$mid = $this->Request->getParam('mid');
		if($id!='' && $mid!=''){
			$delete = $this->model->DeleteStock($id,$mid);
			if($delete){
				sendRedirect('index.php?s=merchandise&act=liststock&id='.$mid);
			}else{
				sendRedirect('index.php?s=merchandise&act=liststock&id='.$mid);
			}
		}else{
			sendRedirect('index.php?s=merchandise');exit;
		}
	}
	
	function delete(){
		$id = intval($this->Request->getParam('id'));
		if($id==null){
			return $this->listMerch();
		}else{
			$i = "SELECT img FROM ".RedRushDB.".rr_merchandise WHERE id='".$id."'";
			$this->open(0);
			$img = $this->fetch($i);
			//print_r($img);exit;
			$this->close();
			$folder = "../public_html/img/merchandise/";
			$nfolder = "public_html/img/merchandise/";
			$this->trash->execute($folder,$img['img'],$nfolder); // copy file ke trash
			$this->trash->execute($folder,"thumb_".$img['img'],$nfolder); // copy file ke trash
			@unlink($folder.$img['img']);
			@unlink($folder.'thumb_'.$img['img']);
			
			$r = $this->model->deleteMerchandise($id);
			if($r){
				sendRedirect('index.php?s=merchandise');exit;
			}else{
				sendRedirect('index.php?s=merchandise');exit;
			}
		}
	}
	
	function listPurchase($req,$total_per_page=50){
		
		$start = $this->Request->getParam("st");
		$this->Request->getParam("q")  != '' ? $q = " && (m.item_name LIKE '%".$this->Request->getParam("q")."%' OR u.name LIKE '%".$this->Request->getParam("q")."%')" : $q = "";
		
		if($start==NULL){$start = 0;}
		$list=$this->model->getPurchaseList($start,$total_per_page,$q);
		$total = $this->model->countPurchase($q);
		
		$this->View->assign("q",$this->Request->getParam("q") );
		$this->View->assign("list",$list);
		$this->Paging = new Paginate();
		$page = $this->Paging->getAdminPaging($start,$total_per_page,$total,'index.php?s=merchandise&act=purchase-list&q='.$this->Request->getParam("q"));
		$this->View->assign("paging",$page);
		return $this->View->toString(APPLICATION."/admin/merchandise/purch_list.html");
	}
	
	function deletePurchase(){
		$id = intval($this->Request->getParam('id'));
		if($id==null){
			return $this->listPurchase();
		}else{
			$r = $this->model->deletePurchase($id);
			if($r){
				sendRedirect('index.php?s=merchandise&act=purchase-list');exit;
			}else{
				sendRedirect('index.php?s=merchandise&act=purchase-list');exit;
			}
		}
	}
	
	/* Change Purchase Status */
	function changePurchase(){
		$id = $this->Request->getPost('id');
		$stat = $this->Request->getPost('status');
		
		if($stat!='' && $id!=''){
			if($stat==0){
				$q = "UPDATE ".RedRushDB.".rr_purchase_merchandise SET n_status='0' WHERE id='".$id."';";
				$data= '<font color=gray>successfully pending.</font>';
			}
			elseif($stat==1){
				$q = "UPDATE ".RedRushDB.".rr_purchase_merchandise SET n_status='1' WHERE id='".$id."';";
				$data= '<font color=green>successfully approved.</foto>';
			}
			else{
				$q = "UPDATE ".RedRushDB.".rr_purchase_merchandise SET n_status='2' WHERE id='".$id."';";
				$data= '<font color=red>successfully rejected.</font>';
			}
			$this->open(0);
			$f = $this->query($q);
			$this->close();
			if($f){
				echo $data;
				exit;
			}
			else{
				$data = "<font color='red'>Failed 1!</font>";
				echo $data;
				exit;
			}
		}
		else{
			$data = "<font color='red'>Failed 2!</font>";
			echo $data;
			exit;
		}
	}
	
	function getPO($req=null,$create=false){
		$id = intval($req->getParam('id'));
		if($id){
			$data = $this->model->getPurchaseListByID($id);
			$this->View->assign('data',$data);
			$html = $this->View->toString(APPLICATION."/admin/merchandise/pdfPO.html");
			// print('<pre>');print_r($data);exit;
			return $this->pdf->writePDF("RedRush", 'PO_'.$data['purchase_merchandise_id'], 10, $html,"I");
		}else{
			echo '<script type="text/javascript">alert("Failed to create PDF! ID NULL.");</script>';
			sendRedirect('index.php?s=merchandise&act=purchase-list');
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
