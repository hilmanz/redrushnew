<?php
	class PlayerModel extends SQLData{
		function __construct(){ parent::SQLData();	}
		
		function countAll($q=null){
			$q = "SELECT COUNT(*) total FROM ".RedRushDB.".kana_member
				WHERE 1 $q";
			$this->open(0);
			$r = $this->fetch($q);
			$this->close();
			return $r['total'];
		}
		
		function getPlayer($start=0,$total=1,$q=null){
			$q = "
				SELECT * FROM ".RedRushDB.".kana_member
				WHERE 1 $q 
				AND kana_member.register_id <> 0
				ORDER BY kana_member.name ASC 
				LIMIT ".$start.",".$total;
			$this->open(0);
			$r = $this->fetch($q,1);
			$this->close();
			return $r;
		}
		
		function getPlayerByID($id=null){
			if($id==null)return false;
			$q = "
				SELECT * FROM ".RedRushDB.".kana_member
				WHERE id=".$id.";";
			$this->open(0);
			$r = $this->fetch($q);
			$this->close();
			return $r;
		}
		
		function editPlayer($user_id,$image=null,$small_img=null){
			$this->open(0);
			$sql = "UPDATE ".RedRushDB.".kana_member SET img='".$image."',small_img='".$small_img."'";
			$sql.= " WHERE id=".$user_id;
			$data = $this->query($sql);
			// echo $sql;exit;
			$sql = "INSERT IGNORE INTO ".RedRushDB.".tbl_temp_image_processing
			(user_id,file) 
			VALUES 
			(".$user_id.",'".$image."')";
			// echo $sql;exit;
			$result = $this->query($sql);
			//if($result) return true;
			$this->close();
			return $result;
		}
		
		function deleteAvatarByID($id=null){
			if($id==null)return false;
			$q = "
				UPDATE ".RedRushDB.".kana_member SET img=NULL,small_img=NULL
				WHERE id=".$id.";";
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
		
		
	}
?>