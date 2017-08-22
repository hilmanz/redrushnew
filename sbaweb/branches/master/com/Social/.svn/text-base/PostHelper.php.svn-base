<?php
include_once "NewsHelper.php";
class PostHelper extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $news;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->news = new NewsHelper($req);
	}
	function addPost($user_id,$text){
		$text = strip_tags($text);
		$user_id = strip_tags($user_id);
		
		if(eregi('([0-9]+)',$user_id)){
			$this->open(0);
			if($this->query("INSERT INTO social_posts(user_id,post,post_time,attachment)
						VALUES(".$user_id.",'".$text."',NOW(),'')")){
				$post_id = mysql_insert_id();
				$rs = $this->getPost($post_id,false);
			}
			$this->close();
			return $rs;
		}
		
	}
	function getPost($id,$auto=false){
		$sql = "SELECT *,b.name,b.img FROM social_posts a INNER JOIN social_member b ON a.user_id = b.id 
				WHERE a.id=".$id." LIMIT 1";
		
		if(!$auto){
			$rs = $this->fetch($sql);
		}else{
			$this->open(0);
			$rs = $this->fetch($sql);
			$this->close();
		}
		return $rs;
	}
	function getFeed($id,$last_id=0){
		
		$sql = "SELECT 
						*,a.id as post_id,
						b.name,
						b.img,
						c.reply
					FROM 
						social_posts a 
						INNER JOIN social_member b 
						ON a.user_id = b.id
						LEFT JOIN ( SELECT post_id, COUNT(id) reply FROM social_posts_reply GROUP BY post_id ) c
						ON a.id = c.post_id
					WHERE (a.user_id = ".$id." 
				OR a.user_id IN (SELECT friend_id FROM social_network WHERE user_id=".$id." AND is_visible=1)) 
				AND a.id > ".$last_id." ORDER BY a.id DESC LIMIT 10";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$n = sizeof($rs);
		for($i=0;$i<$n;$i++){
			$rs[$i]['like'] = number_format($this->total_like($rs[$i]['post_id']));
		}
		$this->close();
		
		return $rs;
	}
	function getReplies($post_id,$total){
		
		$sql = "SELECT *,a.id as reply_id,b.name,b.img FROM social_posts_reply a INNER JOIN social_member b ON a.user_id = b.id 
				WHERE a.post_id = ".$post_id." ORDER BY a.id DESC LIMIT 10";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	function total_like($post_id){
		$sql2 = "SELECT COUNT(post_id) as total FROM social_like WHERE post_id=".$post_id."";
		$this->open();
		$rs = $this->fetch($sql2);
		$this->close();
		return $rs['total'];
	}
	function like($user_id,$post_id){
		if(eregi("([0-9]+)",$user_id)&&eregi("([0-9]+)",$post_id)){
			$sql0 = "SELECT * FROM social_like WHERE user_id=".$user_id." AND post_id=".$post_id." LIMIT 1";
			$sql = "INSERT INTO social_like(user_id,post_id)
					VALUES(".$user_id.",".$post_id.")";
			$sql2 = "SELECT COUNT(post_id) as total FROM social_like WHERE post_id=".$post_id."";
			
			$this->open();
			$cek = $this->fetch($sql0);
			
			if($cek==NULL){
				$q = $this->query($sql);
				
				//cari nama user
				$u=$this->fetch("SELECT name FROM social_member WHERE id=$user_id;");
				$f=$this->fetch("SELECT p.post title,m.name,m.id fid   FROM social_posts p JOIN social_member m ON p.user_id=m.id WHERE p.id=$post_id;");			
				$msg = "<a href='index.php?profile=1&profile_id=". $user_id ."'>". $u['name'] ."</a> like <a href='index.php?profile=1&profile_id=". $f['fid'] ."'>". $f['name'] ."</a> status";
				$this->news->send($user_id,$msg);
				//print $sql;
			}
				$rs = $this->fetch($sql2);
			
			$this->close();
			return $rs['total'];
		}
		//return $q;
	}
	function reply($user_id,$post_id,$txt){
		$txt = strip_tags($txt);
		$txt = htmlentities($txt);
		if(eregi("([0-9]+)",$user_id)&&eregi("([0-9]+)",$post_id)){
			$sql = "INSERT INTO social_posts_reply(user_id,post_id,post,post_time)
					VALUES(".$user_id.",".$post_id.",'".$txt."',NOW())";
			$this->open();
			$q = $this->query($sql);
			$this->close();
			if($q){
				return 1;
			}
		}
	}
	function total_reply($post_id){
		if(eregi("([0-9]+)",$post_id)){
			$sql2 = "SELECT COUNT(post_id) as total FROM social_posts_reply WHERE post_id=".$post_id."";
			$this->open();
			$rs = $this->fetch($sql2);
			$this->close();
			return $rs['total'];
		}
		return 0;
	}
	function getMoreFeed($id,$start=0){
		$sql = "SELECT 
						*,
						a.id as post_id,
						b.name,
						b.img,
						c.reply
					FROM 
						social_posts a 
						INNER JOIN social_member b 
						ON a.user_id = b.id 
						LEFT JOIN ( SELECT post_id, COUNT(id) reply FROM social_posts_reply GROUP BY post_id ) c
						ON a.id = c.post_id
				WHERE (a.user_id = ".$id." 
				OR a.user_id IN (SELECT friend_id FROM social_network WHERE user_id=".$id." AND is_visible=1)) 
				ORDER BY a.id DESC LIMIT $start,10";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$n = sizeof($rs);
		for($i=0;$i<$n;$i++){
			$rs[$i]['like'] = number_format($this->total_like($rs[$i]['post_id']));
		}
		$this->close();
		$data = array('feed' => $rs, 'test' => '<script>alert("hehehe");</script>' );
		return json_encode( $data );
	}
}
?>