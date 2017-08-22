<?php
/*
 *	Irvan Fanani
 *	17 Maret 2011
 */
include_once $ENGINE_PATH."Utility/Paginate.php";
class BA extends SQLData{

	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	
	function BA_Login(){
		
	}
	
	/** backend stuffs **/
	function admin(){
		$req = $this->Request;
		if($req->getParam('act') == 'setleader' ){
			return $this->BASetLeader($req);
		}else if($req->getParam('act') == 'setnoleader' ){
			return $this->BASetNoLeader($req);
		}else{
			return $this->BAList($req);
		}
	}
	
	function BASetNoLeader($req){
		$this->open(0);
		$id = $req->getParam("id");
		$qry = "DELETE FROM leader_ba_lookup WHERE leader_id=$id;";
		if( $this->query($qry) ){
			return $this->View->showMessage('Berhasil', "index.php?s=ba");
		}else{
			return $this->View->showMessage('Gagal', "index.php?s=ba");
		}
	}
	
	function BASetLeader($req){
		if( $req->getParam('cmd') == 'save'){
			$this->open(0);
			$id = $req->getParam("id");
			$qry = "INSERT INTO leader_ba_lookup 
						(leader_id,ba_id)
						VALUES
						";
			foreach( $_GET['member'] as $k => $v ){
				$qry .= "($id, $v),";
			}
			$qry = substr($qry, 0, -1) . ';';
			if( $this->query($qry) ){
				return $this->View->showMessage('Berhasil', "index.php?s=ba");
			}else{
				return $this->View->showMessage('Gagal', "index.php?s=ba");
			}
		}else{
			$this->open(0);
			$id = $req->getParam("id");
			$qry = "SELECT * FROM social_member WHERE id=$id";
			$r = $this->fetch($qry);
			$this->View->assign('id', $r['id']);
			$this->View->assign('username', $r['username']);
			$this->View->assign('name', $r['name']);
			$this->View->assign('regid', $r['register_id']);
			$this->View->assign('regdate', $r['register_date']);
			$this->View->assign('email', $r['email']);
			
			$qry = "SELECT 
							*
						FROM 
							social_member
						WHERE 
							id <> $id &&
							TYPE=1 &&
							id NOT IN ( SELECT DISTINCT(leader_id) FROM leader_ba_lookup );";
			$list = $this->fetch($qry, 1);
			$this->View->assign("ba",$list);
			
			$this->close();
			return $this->View->toString("BA/admin/setleader.html");
		}
	}
	
	function BAList($req){
		$this->open(0);
		$start = $req->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT count(*) total FROM social_member WHERE type=1");
		$total = $r['total'];
		
		$qry = "SELECT 
					m.*,
					b.leader_id,
					ba.leader_id leader 
					FROM 
					social_member m
					LEFT JOIN ( SELECT DISTINCT(leader_id) FROM leader_ba_lookup ) b
					ON m.id=b.leader_id
					LEFT JOIN ( SELECT DISTINCT(ba_id), leader_id FROM leader_ba_lookup ) ba
					ON m.id=ba.ba_id
					WHERE 
					m.type=1 GROUP BY id
					LIMIT $start,10;";
		$list = $this->fetch($qry, 1);
		$this->View->assign("list",$list);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, 10, $total, "?s=ba"));
		$this->close();
		return $this->View->toString("BA/admin/index.html");
	}
}