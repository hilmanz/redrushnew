<?php 
/**
* Class forumModel
* @author Irvan Fanani
* 24 mei 2011
*/
class forumModel extends SQLData
{
	function __construct(){ parent::SQLData();	}
	
	function getThreadList($start=0,$limit=99999999999999999){
		$que = "SELECT 
					t.id,
					t.title,
					m.small_img,
					IF(r.reply IS NULL, 0,r.reply) reply,
					IF(r.posted_date IS NULL, t.posted_date, r.posted_date) posted_date,
					IF(m2.id IS NULL, m.id, m2.id) user_id,
					IF(m2.name IS NULL, m.name, m2.name) name
				FROM 
					tbl_forum_topic t
					LEFT JOIN social_member m
					ON t.user_id=m.id
					LEFT JOIN (SELECT COUNT(id) reply, topic_id, posted_date, user_id FROM tbl_forum_reply WHERE n_status='1' GROUP BY topic_id ORDER BY posted_date DESC ) r
					ON t.id = r.topic_id
					LEFT JOIN social_member m2
					ON r.user_id=m2.id
				WHERE 
					t.n_status='1' 
				ORDER BY
					t.posted_date DESC,
					r.posted_date DESC
				LIMIT $start,$limit;";
		$this->open(0);
		$rs=$this->fetch($que,1);
		$this->close();
		return $rs;
	}
	function getAdminThreadList($start=0,$limit=99999999999999999){
		$que = "SELECT 
					t.id,
					t.title,
					t.content,
					IF(r.reply IS NULL, 0,r.reply) reply,
					t.posted_date,
					t.user_id,
					m.name,
					t.n_status
				FROM 
					tbl_forum_topic t
					LEFT JOIN social_member m
					ON t.user_id=m.id
					LEFT JOIN (SELECT COUNT(id) reply, topic_id, posted_date, user_id FROM tbl_forum_reply WHERE n_status='1' GROUP BY topic_id ORDER BY posted_date DESC ) r
					ON t.id = r.topic_id
					LEFT JOIN social_member m2
					ON r.user_id=m2.id
				WHERE 
					t.n_status <> '3' 
				ORDER BY
					t.posted_date DESC,
					r.posted_date DESC
				LIMIT $start,$limit;";
		$this->open(0);
		$rs=$this->fetch($que,1);
		$this->close();
		$num = count($rs);
		if($num>0){
			for($i=0;$i<$num;$i++){
				$rs[$i]['content'] = str_replace('src="admin/','src="',$rs[$i]['content']);
			}
		}
		return $rs;
	}
	function getAdminReplyList($thread_id=0,$start=0,$limit=99999999999999999){
		$thread_id = intval($thread_id);
		$que = "SELECT 
					t.*
				FROM 
					tbl_forum_reply t
				WHERE 
					t.n_status <> '3' && 
					t.topic_id=$thread_id 
				ORDER BY
					t.posted_date DESC
				LIMIT $start,$limit;";
		$this->open(0);
		$rs=$this->fetch($que,1);
		$this->close();
		$num = count($rs);
		if($num>0){
			for($i=0;$i<$num;$i++){
				$rs[$i]['content'] = str_replace('src="admin/','src="',$rs[$i]['content']);
			}
		}
		return $rs;
	}
	function getThreadPage($thread_id=0,$start=0,$limit=99999999999999999){
		$thread_id = intval($thread_id);
		$que = "SELECT 
					a.*,
					m.small_img,
					m.name
				FROM 
					(SELECT 
						title,
						0 topic_id,
						content,
						posted_date,
						user_id
					FROM 
						tbl_forum_topic
					WHERE 
						n_status='1' &&
						id=$thread_id
					UNION ALL 
					SELECT 
						title,
						topic_id,
						content,
						posted_date,
						user_id
					FROM 
						tbl_forum_reply
					WHERE 
						topic_id=$thread_id && n_status='1') a
					LEFT JOIN social_member m
					ON a.user_id=m.id
				ORDER BY
					posted_date ASC
					LIMIT $start,$limit;";
		$this->open(0);
		$rs=$this->fetch($que,1);
		$this->close();
		return $rs;
	}
	function getThreadTotal($status=1){
		$que = "SELECT
					count(*) total 
				FROM 
					tbl_forum_topic t
				WHERE 
					t.n_status='$status';";
		$this->open(0);
		$rs=$this->fetch($que);
		$this->close();
		return $rs['total'];
	}
	function getAdminThreadTotal(){
		$que = "SELECT
					count(*) total 
				FROM 
					tbl_forum_topic t
				WHERE 
					t.n_status<>'3';";
		$this->open(0);
		$rs=$this->fetch($que);
		$this->close();
		return $rs['total'];
	}
	function getAdminReplyTotal($thread_id=0){
		$thread_id=intval($thread_id);
		$que = "SELECT
					count(*) total 
				FROM 
					tbl_forum_reply t
				WHERE 
					t.n_status<>'3' && topic_id=$thread_id;";
		$this->open(0);
		$rs=$this->fetch($que);
		$this->close();
		return $rs['total'];
	}
	function getReplyTotal($thread_id=0,$status=1){
		$thread_id = intval($thread_id);
		$que = "SELECT 
					count(*) total
				FROM 
					(SELECT
						id 
						title,
						0,
						content,
						posted_date,
						user_id
					FROM 
						tbl_forum_topic
					WHERE 
						n_status='1' &&
						id=$thread_id
					UNION ALL 
					SELECT
						id 
						title,
						topic_id,
						content,
						posted_date,
						user_id
					FROM 
						tbl_forum_reply
					WHERE 
						topic_id=$thread_id) a
					LEFT JOIN social_member m
					ON a.user_id=m.id;";
		$this->open(0);
		$rs=$this->fetch($que);
		$this->close();
		return $rs['total'];
	}
	function addReply($thread_id=0,$content='',$user_id=0){
		$que = "INSERT INTO tbl_forum_reply 
				(topic_id,title,content,posted_date,user_id)
				VALUES
				($thread_id,'','".mysql_escape_string($content)."',NOW(),$user_id);
				";
		$this->open(0);
		$rs=$this->query($que);
		$this->close();
		return $rs;
	}
	function addThread($title='',$content='',$user_id=0){
		$content = stripslashes($content);
		$title = stripslashes($title);
		$que = "INSERT INTO tbl_forum_topic 
				(title,content,posted_date,user_id)
				VALUES
				('".mysql_escape_string($title)."','".mysql_escape_string($content)."',NOW(),$user_id);
				";
		$this->open(0);
		$rs=$this->query($que);
		$this->close();
		return $rs;
	}
}

?>
