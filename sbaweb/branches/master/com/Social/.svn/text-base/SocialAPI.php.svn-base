<?php
include_once "MOPHelper.php";
include_once "SessionHelper.php";
class SocialAPI extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $mop;
	var $session;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->mop = new MOPHelper(null);
		$this->session = new SessionHelper('SocialNetwork');
	}
	function isSignedRequestValid($user_id,$signed_request){
		$req = unserialize(urldecode64($signed_request));
		
		if($req['expired_time']>0&&$req['expired_time']>time()){
			if($user_id==$req['id']){
				return true;
			}
		}
	}
	function postFeed($user_id,$text,$signed_request){
		if($this->isSignedRequestValid($user_id,$signed_request)){
			include_once "PostHelper.php";
			$posts = new PostHelper($req);
			$rs = $posts->addPost($user_id, $text);
			$this->assign("post",array($rs));
			if($rs){
				global $tracker;
				$tracker->doTrack(1,$user_id, 0, 1, '');
			}
			return $this->out('Social/single_post.html');
		}else{
			return "";
		}
	}
	
	function Reply($user_id,$post_id,$txt,$signed_request){
		if($this->isSignedRequestValid($user_id,$signed_request)){
			include_once "PostHelper.php";
			$posts = new PostHelper($req);
			$rs = $posts->reply($user_id, $post_id,$txt);
			return $rs;
		}else{
			return "";
		}
	}
	function total_reply($post_id){
		include_once "PostHelper.php";
		$posts = new PostHelper($req);
		$rs = $posts->total_reply($post_id);
		return $rs;
	}
	function like($user_id,$post_id,$signed_request){
		global $tracker;
		if($this->isSignedRequestValid($user_id,$signed_request)){
			include_once "PostHelper.php";
			$posts = new PostHelper($req);
			$rs = $posts->like($user_id, $post_id);
			
			$tracker->doTrack(0,$user_id, 1, 1, "post_id_".$post_id);
			return $rs;
		}else{
			return "";
		}
	}
	function getFeed($user_id,$last_id){
		
		include_once "PostHelper.php";
		$posts = new PostHelper($req);
		settype($last_id,'integer');
		$rs = $posts->getFeed($user_id,$last_id);
		$this->assign("post",$rs);
		$html = $this->out('Social/single_post.html');
		return json_encode(array($html,$rs[0]['post_id']));
	}
	function getMoreFeed($user_id,$start){
		include_once "PostHelper.php";
		$posts = new PostHelper($req);
		settype($start,'integer');
		return $posts->getMoreFeed($user_id,$start);
	}
	function getNews($user_id,$last_id=0){
		include_once "NewsHelper.php";
		$posts = new NewsHelper($req);
		settype($last_id,'integer');
		$rs = $posts->getFeeds($user_id);
		$this->assign("list",$rs);
		$html = $this->out('Social/widget_news_feeds.html');
		return json_encode(array($html,$rs[0]['id']));
	}
	function getComments($post_id){
		
		include_once "PostHelper.php";
		$posts = new PostHelper($req);
		settype($post_id,'integer');
		$rs = $posts->getReplies($post_id,10);
		$this->assign("post",$rs);
		$html = $this->out('Social/single_reply.html');
		return json_encode(array($html,$rs[0]['post_id']));
	}
	function execute(){
		if($this->post()){
			
		}
	}
}
?>