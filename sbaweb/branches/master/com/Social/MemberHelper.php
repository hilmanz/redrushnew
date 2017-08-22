<?php
class MemberHelper extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
	}
	function update_login_time($user_id){
		$sql = "UPDATE social_member SET last_login = NOW()
				WHERE id=".intval($user_id);
		
		$this->open(0);
		$rs = $this->query($sql);
		$this->close();
		return $rs;
	}
	/**
	 * sync mop data with our data
	 * @param object
	 * @return json
	 */
	function sync_mop($profile){
		$member = $this->getProfileByMop($profile->register_id);
		
		if($member['register_id']!=$profile->register_id){
			
			//insert data
			if($this->insert_data_from_mop($profile)){
				
				$member = $this->getProfileByMop($profile->register_id);
			}else{
				
				print mysql_error();
			}
		}
		return json_encode($member);
	}
	function insert_data_from_mop($profile){
		$sql = "INSERT INTO social_member(register_id,name,email,register_date,username)
				VALUES(".$profile->register_id.",'".$profile->name."','".$profile->email."',NOW(),'".$profile->username."')";
		
		$this->open(0);
		$rs = $this->query($sql);
		$this->close();
		return $rs;
	}
	function getProfileByMop($register_id){
		$this->open(0);
		$sql = "SELECT * FROM social_member WHERE register_id='".$register_id."' LIMIT 1";
		$rs = $this->fetch($sql);
		$this->close();
		return $rs;
	}
	function getProfile($id){
		$this->open(0);
		$sql = "SELECT * FROM social_member WHERE id='".$id."' LIMIT 1";
		$rs = $this->fetch($sql);
		
		$this->close();
		return $rs;
	}
	function MemberList($user_id,$start,$total=20){
		
		$this->mainLayout('Social/member_list.html');
		$sql = "SELECT a.*,c.user_id FROM social_member a 
				LEFT JOIN(
				SELECT b.user_id, b.friend_id AS fid FROM social_network b WHERE b.user_id = ".$user_id."
				) AS c
				ON a.id = c.fid
				WHERE a.id<>".$user_id;
		$sql2 = "SELECT COUNT(id) as total FROM social_member";
		$this->open();
		$list = $this->fetch($sql,1);
		$rs = $this->fetch($sql2);
		$this->close();
		//paging
		$paging = new Paginate();
		$this->assign("list",$list);
		
		$this->assign("pages",$paging->generate($start, $total, $rs['total']));
		//$this->getList("SELECT * FROM social_member WHERE id <> ".$user_id." ORDER BY name",$start,$total,"?members=1");
		return $this;
	}
	function AddFriend($user_id,$friend_id){
		$ok = false;
		if(eregi("([0-9]+)",$user_id)&&eregi("([0-9]+)",$friend_id)){
			$this->open();
			
				$sql = "INSERT IGNORE INTO social_network(user_id,friend_id) VALUES($user_id,$friend_id)";
				
				$q = $this->query($sql);
				if($q){
					$sql = "INSERT IGNORE INTO social_network(user_id,friend_id) VALUES($friend_id,$user_id)";
					$q = $this->query($sql);
					if($q){
						$ok = true;	
					}else{
						$sql = "DELETE FROM social_network WHERE (user_id=$user_id AND friend_id=$friend_id) OR (user_id=$friend_id AND friend_id=$user_id)";
						$this->query($sql);
					}
				}
			
			$this->close();
		}else{
			$ok = true;
		}
		
		return $ok;
	}
	function RemoveFriend($user_id,$friend_id){
		$this->open();
		$sql = "DELETE FROM social_network WHERE (user_id=$user_id AND friend_id=$friend_id) OR (user_id=$friend_id AND friend_id=$user_id)";
		$q = $this->query($sql);
		$this->close();
		return $q;
	}
	function isFriend($user_id,$friend_id){
		
		$sql = "SELECT * FROM social_network WHERE user_id = ".$user_id." AND friend_id=".$friend_id." LIMIT 1";
		if($user_id!=null||$user_id>0&&$friend_id>0){
			$this->open();
			$rs = $this->fetch($sql);
			$this->close();
			if($user_id==$rs['user_id']&&$friend_id==$rs['friend_id']){
				return true;
			}
		}
	}
	function getUserUrl($user_id,$name){
		return "<a href='users.php?u=".$user_id."'>".$name."</a>";
	}
	function getBookmarks($user_id){
		$sql = "SELECT * FROM social_bookmark WHERE user_id=".$user_id." LIMIT 30";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	function addBookmark($user_id,$name,$url){
		$sql = "INSERT INTO `sba`.`social_bookmark`
            (
             `user_id`,
             `bookmark_url`,
             `bookmark_name`)
			VALUES (
			        ".$user_id.",
			        '".$url."',
			        '".$name."')";
		$this->open(0);
		$rs = $this->query($sql);
		$this->close();
		return $rs;
	}
}
?>