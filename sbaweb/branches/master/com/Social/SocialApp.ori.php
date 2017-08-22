<?php
include_once "MOPHelper.php";
include_once "SessionHelper.php";
include_once "NewsHelper.php";
class SocialApp extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $mop;
	var $session;
	var $news;
	var $widgets;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->mop = new MOPHelper(null);
		$this->session = new SessionHelper('SocialNetwork');
		$this->news = new NewsHelper($req);
	}
	/**
	 * 
	 * @todo tolong di tweak lagi expired_timenya.
	 */
	function main(){
		global $CONFIG;
		if($this->GetSession()){
			$str = $this->run();
		}else{
			sendRedirect($CONFIG['MOP_URL']);
			die();
		}
		$this->assign('mainContent',$str);
		$this->assign('API_URL',$CONFIG['API_URL']);
		$this->mainLayout('Social/index.html');
		//print urldecode64($this->session->get('mop_profile'));
	}
	function page(){
		global $CONFIG;
		include_once "PageApp.php";
		$page = new PageApp($this->Request);
		if($this->GetSession()){
			$str = $page->run();
		}else{
			sendRedirect($CONFIG['MOP_URL']);
			die();
		}
		$this->assign('mainContent',$str);
		$this->mainLayout('Social/page_index.html');
	}
	
	function run(){
		$left_column = true;//tampilkan kolom kiri
		$right_column = true;//tampilkan kolom kanan
		if($this->param('profile_pic')=="1"){
			$content = $this->ProfilePic();
		}else if($this->param('invite')=="1"){
			$content= $this->Invite();
		}else if($this->param('members')=="1"){
			$content =  $this->Members();
			$left_column = false; //sembunyiin kolom kiri
		}else if($this->param('add')=="1"){
			$content =  $this->AddFriend();
		}else if($this->param("confirm_friend")=="1"){
			$content =  $this->ConfirmFriend();
		}else if($this->param('remove')=="1"){
			$content =  $this->RemoveFriend();
		}else if($this->param('notification')=="1"){
			$content =  $this->Notifications();
		}else if($this->param('profile')=="1"){
			//my profile pake halaman sendiri
			return $this->MyProfile();
		}else if($this->param('news')=="1"){
			$content =  $this->News();
		}else if($this->param('events')=="1"){
			$content =  $this->Events();
		}else if($this->param('gallery')=="1"){
			$content =  $this->Gallery();
		}else if($this->param('download')=="1"){
			$content =  $this->Download();
		}else{
			
			return $this->Home();
		}
		
		//kalau bukan halaman home.
		return $this->Content($content,$left_column,$right_column);
	}
	function Home(){
		include_once "Widgets/WidgetBerita.php";
		
		
		//$profile = $this->getProfile();
		/*$user['name'] = $profile->name;
		$user['img'] = $profile->img;
		$user['id'] = $profile->id;*/
		if($this->param('profile_id')!=NULL){
			$user = $this->getOtherUserInfo($this->param('profile_id'));
			$isMySelf = 0;
		}else{
			$user = $this->getUserInfo();
			$isMySelf = 1;
		}
		$signed_request = urlencode64(serialize(array("id"=>$user['id'],"register_id"=>$user['register_id'],"expired_time"=>time()+(60*60))));
		$this->assign('signed_request',$signed_request);
		$this->assign("user_id",$user['id']);
		$this->assign("CONTENT",$this->out('Social/feeds.html'));
		$this->assign("user",$user);
		$this->assign("isMySelf",$isMySelf);
		$this->assign("bookmark",$this->getBookmarks($user['id']));
		
		
		
		//WIDGETS
		$widget_berita = new WidgetBerita($this->Request);
		$this->assign("berita_terbaru",$widget_berita);
		
		include_once "Widgets/WidgetBA.php";
		$widget_ba = new WidgetBA($this->Request);
		$widget_ba->getTopPerformer();
		$this->assign("top_performers",$widget_ba);
		
		$widget_city = new WidgetBA($this->Request);
		$widget_city->getTopCity();
		$this->assign("top_city",$widget_city);
		
		$widget_tevent = new WidgetBA($this->Request);
		$widget_tevent->getTopEvent();
		$this->assign("top_event",$widget_tevent);
		
		include_once "Widgets/WidgetEvent.php";
		$widget_event = new WidgetEvent($this->Request);
		$this->assign("acara_terkini",$widget_event);
		
		include_once "Widgets/WidgetNetworkUpdates.php";
		$widget_network = new WidgetNetworkUpdates($this->Request);
		$this->assign("network_updates",$widget_network);
		
		include_once "Widgets/WidgetBanner.php";
		$widget_banner = new WidgetBanner($this->Request);
		$this->assign("banner",$widget_banner);
		
		include_once "Widgets/WidgetGallery.php";
		$widget_gallery = new WidgetGallery($this->Request);
		$this->assign("gallery_updates",$widget_gallery);
		
		//--> Widgets end
		
		
		$str = $this->out("Social/home.html");
		return $str;
	}
	function MyProfile(){
		global $APP_PATH;
		$this->assign('head_title','MY PROFILE');
		if($this->param('profile_id')!=NULL){
			$u = $this->getOtherUserInfo($this->param('profile_id'));
			$isMySelf = 0;
		}else{
			$u = $this->getUserInfo();
			$isMySelf = 1;
		}
		$user = $this->getUserInfo();
		$signed_request = urlencode64(serialize(array("id"=>$user['id'],"register_id"=>$user['register_id'],"expired_time"=>time()+(60*60))));
		$this->assign('signed_request',$signed_request);
		$this->assign("user_id",$user['id']);
		$this->assign("CONTENT",$this->out('Social/feeds.html'));
		$this->assign("user",$u);
		$this->assign("isMySelf",$isMySelf);
		$this->assign("bookmark",$this->getBookmarks($user['id']));
		
		//BA Performance
		include_once "BAHelper.php";
		$ba = new BAHelper(null);
		
		$ba_performance = $ba->getPerformance($u['id']);
		
		$this->assign("ba",$ba_performance);
		
		//WIDGETS
		include_once "Widgets/WidgetWall.php";
		$widget_wall = new WidgetWall($this->Request);
		$widget_wall->my_wall($u['id']);
		$this->widgets['my_wall'] = $widget_wall;
		
		include_once "Widgets/WidgetBA.php";
		$widget_my_network = new WidgetBA($this->Request);
		$widget_my_network->my_network($u['id']);
		$this->widgets['my_network'] = $widget_my_network;
		
		
		include_once "Widgets/WidgetEvent.php";
		$widget_event = new WidgetEvent($this->Request);
		$widget_event->getLatestEvent();
		$this->widgets['acara_terkini'] = $widget_event;
		
		include_once "Widgets/WidgetBanner.php";
		$widget_banner = new WidgetBanner($this->Request);
		$this->widgets['banner'] = $widget_banner;
		
		include_once "Widgets/WidgetGallery.php";
		$widget_gallery = new WidgetGallery($this->Request);
		$widget_gallery->getListGallery();
		//$this->assign("photo_gallery",$widget_gallery);
		if(is_array($this->widgets)){
			foreach($this->widgets as $name=>$val){
				$this->assign($name,$val);
			}
		}
		//--> Widgets end
		
		
		return $this->View->toString("Social/my_profile.html");
	}
	function News(){
		$this->assign('head_title','NEWS');
		
		include_once "Widgets/WidgetEvent.php";
		$widget_event = new WidgetEvent($this->Request);
		$widget_event->getLatestEvent();
		$this->widgets['acara_terkini'] = $widget_event;
		
		include_once "Widgets/WidgetBanner.php";
		$widget_banner = new WidgetBanner($this->Request);
		$this->widgets['banner'] = $widget_banner;
		
		include_once "Widgets/WidgetBA.php";
		$widget_ba = new WidgetBA($this->Request);
		$widget_ba->getBACharts();
		$this->assign("ba_charts",$widget_ba);
		
		include_once "Widgets/WidgetGallery.php";
		$widget_gallery = new WidgetGallery($this->Request);
		$widget_gallery->getListGallery();
		$this->assign("photo_gallery",$widget_gallery);
		
		include_once "Widgets/WidgetBerita.php";
		$widget_berita = new WidgetBerita($this->Request);
		$content = $widget_berita; 
		
		if( $this->param("id") == null ){
			include_once "Widgets/WidgetNewsFeed.php";
			$widget_nf = new WidgetNewsFeed($this->Request);
			$content .= $widget_nf;
		}
		
		return  $content;
	}
	function Events(){
		$this->assign('head_title','EVENTS');
		$this->widgets['acara_terkini'] = "";
		
		include_once "Widgets/WidgetBanner.php";
		$widget_banner = new WidgetBanner($this->Request);
		$this->widgets['banner'] = $widget_banner;
		
		include_once "Widgets/WidgetBA.php";
		$widget_ba = new WidgetBA($this->Request);
		$widget_ba->getBACharts();
		$this->assign("ba_charts",$widget_ba);
		
		include_once "Widgets/WidgetGallery.php";
		$widget_gallery = new WidgetGallery($this->Request);
		$widget_gallery->getListGallery();
		$this->assign("photo_gallery",$widget_gallery);
		
		include_once "Widgets/WidgetEvent.php";
		$widget_event = new WidgetEvent($this->Request);
		$widget_event->getListEvent();
		
		if( $this->param("id") != null ){
			$widget_event->getEvent( $this->param('id') );
		}
		
		$content = $widget_event;
		
		return  $content;
	}
	function Gallery(){
		$this->assign('head_title','GALLERY');
		
		include_once "Widgets/WidgetBA.php";
		$widget_ba = new WidgetBA($this->Request);
		$widget_ba->getBACharts();
		$this->assign("ba_charts",$widget_ba);
		
		include_once "Widgets/WidgetGallery.php";
		$widget_gallery = new WidgetGallery($this->Request);
		$widget_gallery->getEventGallery();
		
		if( $this->param('album') != null && $this->param('id') == null){
			$widget_gallery->getAlbumGallery();
		}else if( $this->param('album') != null && $this->param('id') != null){
			$widget_gallery->getPhotoGallery( $this->param('id') );
		}
		
		$content = $widget_gallery;
		return $content;
	}
	function Download(){
		$this->assign('head_title','DOWNLOAD');
		
		include_once "Widgets/WidgetEvent.php";
		$widget_event = new WidgetEvent($this->Request);
		$widget_event->getLatestEvent();
		$this->widgets['acara_terkini'] = $widget_event;
		
		include_once "Widgets/WidgetBanner.php";
		$widget_banner = new WidgetBanner($this->Request);
		$this->widgets['banner'] = $widget_banner;
		
		include_once "Widgets/WidgetBA.php";
		$widget_ba = new WidgetBA($this->Request);
		$widget_ba->getBACharts();
		$this->assign("ba_charts",$widget_ba);
		
		include_once "Widgets/WidgetGallery.php";
		$widget_gallery = new WidgetGallery($this->Request);
		$widget_gallery->getListGallery();
		$this->assign("photo_gallery",$widget_gallery);
		
		include_once 'DownloadPage.php';
		$download = new DownloadPage($this->Request);
		
		return  $download;
	}
	function Content($html,$left_column,$right_column){
		
		//assign contentnya
		$this->assign("content",$html);
		
		//init
		$user = $this->getUserInfo();
		$signed_request = urlencode64(serialize(array("id"=>$user['id'],"register_id"=>$user['register_id'],"expired_time"=>time()+(60*60))));
		$this->assign('signed_request',$signed_request);
		$this->assign("user_id",$user['id']);
		$this->assign("user",$user);
		
		//end of init
		
		//Setting kolom
		$this->assign("left_column",$left_column);
		$this->assign("right_column",$right_column);
		
		//WIDGETS
		
		/*
		$this->assign("ba_charts","ba_charts");
		$this->assign("acara_terkini","Acara Terkini");
		$this->assign("banner","Banner");
		$this->assign("photo_gallery","thumbnail gallery disini");
		*/
		if(is_array($this->widgets)){
			foreach($this->widgets as $name=>$val){
				$this->assign($name,$val);
			}
		}
		//--> Widgets end
		
		
		
		//kelar.
		//output
		return $this->View->toString("Social/content.html");
		
	}
	function Notifications(){
		include_once "NotificationHelper.php";
		$start = $this->param('st');
		
		$notify = new NotificationHelper($this->Request);
		$profile = $this->getProfile();
		if($this->param('clear')=="1"){
			
			$notify->reset($profile->id);
		}
		return $notify->getNotifications($profile->id,$start,20);
	}
	function Invite(){
		return "Invite";
	}
	function AddFriend(){
		include_once "MemberHelper.php";
		$profile = $this->getProfile();
		$member = new MemberHelper(null);
		$friend_id = $this->param('u');
		$friend = $member->getProfile($friend_id);
		if($member->isFriend($profile->id,$friend_id)){
			$this->assign("is_friend","1");
		}else{
			//$ok = $member->AddFriend($profile->id,$friend_id);
			//$this->assign("approve",$ok);
			return $this->sendAddFriendNotification($profile,$friend_id);
		}
		$this->assign("friend_name",$friend['name']);
		return $this->out("Social/add_friend_result.html");
	}
	function ConfirmFriend(){
		include_once "MemberHelper.php";
		$member = new MemberHelper(null);
		$friend_id = $this->param("user_id");
		$user_id = $this->param("friend_id"); //jgn bingung ya.. user id yang accept request adalah si friend.
		$friend = $member->getProfile($friend_id);
		$user = $member->getProfile($user_id);
		if($member->isFriend($user_id,$friend_id)){
			$this->assign("is_friend","1");
		}else{
			$ok = $member->AddFriend($user_id,$friend_id);
			$this->assign("approve",$ok);
			if($ok){
				//announce
				$msg = "<a href='users.php?u=".$friend['id']."'>".$friend['name']."</a> and "."<a href='users.php?u=".$user['id']."'>".$user['name']."<a/> now is a friend";
				$this->news->send($user_id,$msg);
				$this->news->send($friend_id,$msg);
			}
		}
		
		$this->assign("friend_name",$friend['name']);
		return $this->out("Social/add_friend_result.html");
	}
	function sendAddFriendNotification($profile,$friend_id){
		include_once "NotificationHelper.php";
		$notify = new NotificationHelper($this->Request);
		
		$msg = $profile->name." wants to add u as a friend.";
		$options = array(
						array("label"=>"Yes","uri"=>"index.php?confirm_friend=1&user_id=".$profile->id."&friend_id=".$friend_id),
						array("label"=>"No","uri"=>"index.php?reject_friend=1&user_id=".$profile->id."&friend_id=".$friend_id)
					);
		
		if($notify->send($friend_id, $msg, $options)){
			$msg = "Your friend request has been sent successfully !";
		}else{
			$msg = "Sorry, cannot send your request. Please try again later !";
		}
		return $this->View->showMessage($msg,"?members=1");
	}
	function RemoveFriend(){
		include_once "MemberHelper.php";
		$profile = $this->getProfile();
		$member = new MemberHelper(null);
		$friend_id = $this->param('u');
		$friend = $member->getProfile($friend_id);
		if($member->isFriend($profile->id,$friend_id)){
			$ok = $member->RemoveFriend($profile->id,$friend_id);
		}
		$this->assign("friend_name",$friend['name']);
		return $this->out("Social/remove_friend_result.html");
	}
	function Members(){
		include_once "MemberHelper.php";
		$profile = $this->getProfile();
		$member = new MemberHelper(null);
		$start = $this->param("st");
		if($start==NULL||!eregi("([0-9]+)",$start)){
			$start=0;
		}
		return $member->MemberList($profile->id,$start,50);
	}
	function ProfilePic(){
		include_once "ProfilePicture.php";
		$user = $this->getUserInfo();
		$pic = new ProfilePicture($this->Request);
		$pic->run($user,$this->news);
		return $pic;
	}
	function getUserInfo(){
		//always get the latest data
		include_once "MemberHelper.php";
		$profile = $this->getProfile();
		
		$member = new MemberHelper(null);
		return $member->getProfile($profile->id);
	}
	function getOtherUserInfo($id){
		include_once "MemberHelper.php";
		$member = new MemberHelper(null);
		$profile = $this->getProfile();
		settype($id,'integer');
		
		$user = $member->getProfile($id);
		
		if($member->isFriend($profile->id, $id)){
			$user['is_friend'] = "1";
		}
		return $user;
	}
	
	function getBookmarks($user_id){
		include_once "MemberHelper.php";
		
		$member = new MemberHelper(null);
		return $member->getBookmarks($user_id);
	}
	function addBookmark($user_id,$name,$url){
		include_once "MemberHelper.php";
		$member = new MemberHelper(null);
		return $member->addBookmark($user_id, $name, $url);
	}
	function getProfile(){
		return json_decode(urldecode64($this->session->get('mop_profile')));
	}
	function GetSession(){
		global $CONFIG;
		$mop_token = $this->param('id');
		if($mop_token==null){
			$mop_token = $this->session->get('mop_token');
		}
		if($this->session->get('mop_profile')==NULL){
			//kalo gak ada mop token.. maka redirect ke mop login page.
			if(strlen($mop_token)==0){
				sendRedirect($CONFIG['MOP_URL']);
				die();
			}else{
				$mop_profile = json_decode($this->mop->call('GetProfile', array("id"=>$mop_token)));
				if($mop_profile==""){
					return false;
				}
				$this->session->set('mop_token',$mop_token);
				
				$profile = $this->sync_profile($mop_profile);
				if($profile==NULL){
					return false;
				}
				$this->session->set('mop_profile',urlencode64($profile));
				return true;
			}
		}else{
			return true;
		}
	}
	function sync_profile($mop_profile){
		include_once "MemberHelper.php";
		$member = new MemberHelper(null);
		
		$rs =  $member->sync_mop($mop_profile);
		return $rs;
	}
	
	function admin(){
		include_once "SocialAppAdmin.php";
		$app = new SocialAppAdmin($this->req);
	}
	/*
	function __toString(){
		return $this->out($this->_mainLayout);
	}
	*/
}
?>