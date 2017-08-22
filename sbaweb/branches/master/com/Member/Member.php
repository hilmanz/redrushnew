<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class Member extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	function admin(){
		$req = $this->Request;
		if( $req->getParam('act') == 'add'){
			return $this->memberAdd($req);
		}elseif( $req->getParam('act') == 'edit'){
			return $this->memberEdit($req);
		}else{
			return $this->memberList($req);
		}
	}
	function memberList($req){
		$start = $req->getParam("st");
		if($start==NULL){$start = 0;}
		$total_per_page = 10;
		
		$qry = "SELECT COUNT(*) total FROM social_member";
		$total = $this->fetch($qry);
		$total = $total['total'];
		
		$qry = "SELECT id,name,username,email,IF(type=0,'Entourage',IF(type=1,'BA','Management')) type FROM social_member ORDER BY name LIMIT $start,$total_per_page";
		$list = $this->fetch($qry,1);
		
		$this->View->assign('list',$list);
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=member"));
		
		return $this->View->toString("member/admin/list.html");
	}
	function memberAdd($req){
		if($req->getParam('add') == 1){
			$_username = $req->getParam('username');
			$_password = $req->getParam('password');
			$_email = $req->getParam('email');
			$_name = $req->getParam('name');
			$_type = $req->getParam('type');
			$_status = $req->getParam('status');
			
			//echo $_username.' - '.$_password.' - '.$_email.' - '.$_name.' - '.$_type;
			//exit;
			
			if( $_username != '' && $_password != '' && $_email != '' && $_name != '' && $_type != '' ){
				
				$salt = rand(1000,9999);
				$hash = sha1($_password.$_username.$salt);
				
				$qry = "INSERT INTO dm_member (username,password,salt,email,nama,n_status,last_update) VALUES ('$_username','$hash','$salt','$_email','$_name','$_status',NOW())";
				
				if($this->query($qry)){
					$id = mysql_insert_id();
					$qry = "INSERT INTO social_member 
								(register_id,name,email,register_date,username,type,last_login)
								VALUES
								('$id','$_name','$_email',NOW(),'$_username','$_type',NOW())";
					if($this->query($qry)){
						return $this->View->showMessage("Success","?s=member");
					}else{
						$this->View->assign('msg','Error, please try again!');
						echo mysql_error();
						exit;
					}
				}else{
					$this->View->assign('msg','Error, please try again!');
					echo mysql_error();
					exit;
				}
				
			}else{
				$this->View->assign('msg','Error, please fill all field!');
			}
		}
		return $this->View->toString("member/admin/add.html");
	}
	function memberEdit($req){
		$id = intval($req->getParam('id'));
		$this->View->assign('id',$id);
		if($req->getParam('edit') == 1){
			$_regid = $req->getParam('regid');
			$_nama = $req->getParam('nama');
			$_panggilan = $req->getParam('panggilan');
			$_jenis_kelamin = $req->getParam('jenis_kelamin');
			$_tempat_lahir = $req->getParam('tempat_lahir');
			$_tanggal_lahir = $req->getParam('tanggal_lahir');
			$_no_id = $req->getParam('no_id');
			$_email = $req->getParam('email');
			$_kota = $req->getParam('kota');
			$_propinsi = $req->getParam('propinsi');
			$_merk_rokok_1 = $req->getParam('merk_rokok_1');
			$_merk_rokok_2 = $req->getParam('merk_rokok_2');
			$_acc_social_media = $req->getParam('acc_social_media');
			$_password = $req->getParam('password');
			$_type = $req->getParam('type');
			$_status = $req->getParam('status');
			
			if( $id > 0 && $_email != '' && $_nama != '' && $_type != '' && $_status != '' ){
				
				$qry = "UPDATE dm_member SET
							nama='$_nama',
							panggilan='$_panggilan',
							jenis_kelamin='$_jenis_kelamin',
							tempat_lahir='$_tempat_lahir',
							tgl_lahir='$_tanggal_lahir',
							no_id='$_no_id',
							email='$_email',
							kota='$_kota',
							provinsi='$_propinsi',
							merk_rokok_1='$_merk_rokok_1',
							merk_rokok_2='$_merk_rokok_2',
							acc_social_media='$_acc_social_media',
							n_status='$_status'";
				
				if($_password != ''){
					$qry2 = "SELECT dm.*,m.type FROM social_member m LEFT JOIN dm_member dm ON m.register_id=dm.id WHERE m.id=$id";
					$list = $this->fetch($qry2);
					$salt = $list['salt'];
					$hash = sha1($_password.$list['username'].$salt);
					$qry .= ", password='$hash'";
				}
				$qry .= " WHERE id='$_regid'";
				//echo $qry;exit;
				if($this->query($qry)){
					$qry = "UPDATE social_member SET
								name='$_nama',
								email='$_email',
								type='$_type'
								WHERE
								id='$id'";
					if($this->query($qry)){
						return $this->View->showMessage("Success","?s=member");
					}else{
						$this->View->assign('msg','Error, please try again!');
						echo mysql_error();
						exit;
					}
				}else{
					$this->View->assign('msg','Error, please try again!');
					echo mysql_error();
					exit;
				}
				
			}else{
				$this->View->assign('msg','Error, please fill all field!');
			}
		}
		
		$qry = "SELECT dm.*,m.type FROM social_member m LEFT JOIN dm_member dm ON m.register_id=dm.id WHERE m.id=$id";
		$list = $this->fetch($qry);
		//echo $qry;
		//echo mysql_error();exit;
		$this->View->assign('list',$list);
		
		return $this->View->toString("member/admin/edit.html");
	}
}