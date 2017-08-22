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
			
			$user = $this->getUserInfo();
			include_once "BAHelper.php";
			$helper = new BAHelper(null);
			if( $helper->checkIsLeader($user['id']) ){
				$this->assign('leader', 'yes');
			}
			
			$str = $this->run();
		}else{
			
			sendRedirect("login.php");
			die();
		}
		
		
		include_once "Widgets/WidgetBanner.php";
		$widget_banner = new WidgetBanner($this->Request);
		
		$this->assign('mainContent',$str);
		$this->assign('API_URL',$CONFIG['API_URL']);
		if($this->param('challenge')=="1"){
			$this->assign('HEADER_BANNER',$widget_banner->header_banner());
			$this->mainLayout('challenge/index.html');
		}else{
			$this->assign('HEADER_BANNER',$widget_banner->header_banner());
			$this->mainLayout('Social/index.html');
			//print urldecode64($this->session->get('mop_profile'));
		}
	}
	function page(){
		global $CONFIG;
		include_once "PageApp.php";
		$page = new PageApp($this->Request);
		if($this->GetSession()){
			$str = $page->run();
		}else{
			sendRedirect("login.php");
			die();
		}
		$this->assign('mainContent',$str);
		$this->mainLayout('Social/page_index.html');
	}
	
	function run(){
		global $APP_PATH;
		
		$left_column = true;//tampilkan kolom kiri
		$right_column = true;//tampilkan kolom kanan
		if($this->post('send_message')){
			$content = $this->SendMessage();
		}elseif($this->post('doreply')=="1"){
			return $this->ReplyInbox();	
		}else if($this->param('profile_pic')=="1"){
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
			//$content =  $this->Notifications();
			return $this->Notifications();
		}else if($this->param('profile')=="1"){
			//my profile pake halaman sendiri
			return $this->MyProfile();
		}else if($this->param('news')=="1"){
			$content =  $this->News();
		}else if($this->param('events')=="1"){
			$content =  $this->Events();
		}else if($this->param('gallery')=="1"||$this->param("view")=="gallery"||$this->param("pic")=="view" || $this->param("comment-gallery")=="gallery"){
			$content =  $this->Gallery();
			return $this->GalleryContent($content);
		}else if($this->param('top_performers')=="1"){
			return  $this->TopPerformers();
		}else if($this->param('download')=="1"){
			$content =  $this->Download();
		}else if($this->param("reject_friend")=="1"){
			$content = $this->RejectFriend();
		}else if($this->param('message')=="1"){
			return $this->Message();
		}else if($this->param('inbox')=="1"){
			return $this->Inbox();
		}else if($this->param('read_message')=="1"){
			return $this->ReadMessage();
		}else if($this->param('updates')=="1"){
			$content =  $this->NetworkUpdates();
		}else if($this->param('konfirmasi_ba')=="1"){
			return $this->KonfirmasiBA();
		}else if($this->param('friends')=="1"){
			return $this->friends();
		}elseif($this->param('reply')=="1"){
			return $this->showReplyInbox();
		}elseif($this->param('performance')=="1"){
			return $this->Performance();
		}elseif($this->param('forum')=="1"){
			return $this->forum();
		}elseif($this->param('challenge')=="1"){
			return $this->challenge();
		}else{	
			return $this->Home();
		}
		
		//kalau bukan halaman home.
		return $this->Content($content,$left_column,$right_column);
	}
	function Performance(){
		global $APP_PATH;
		
		include_once "Widgets/WidgetBanner.php";
		$widget_banner = new WidgetBanner($this->Request);
		$this->widgets['banner'] = $widget_banner;
		
		
		include_once $APP_PATH."BA/Performance.php";
		$performance = new Performance($this->Request);
		$user = $this->getUserInfo();
		
		return $performance->main(intval($this->Request->getParam('u')));
		
		
	}
	function ReadMessage(){
			global $APP_PATH;
		$this->assign('onnotif','1');
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
		
		
		
		include_once "MessageHelper.php";
		
		
		$message = new MessageHelper($this->Request);
		$profile = $this->getProfile();
	
		$this->widgets['my_wall'] = $message->read_message($profile->id,$this->param('id'));
		
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
		$widget_gallery->getListGallery(12);
		$this->assign("",$widget_gallery);
		if(is_array($this->widgets)){
			foreach($this->widgets as $name=>$val){
				$this->assign($name,$val);
			}
		}
		//--> Widgets end
		
		$this->View->assign("subtitle","INBOX");
		return $this->View->toString("Social/message.html");
	}
	function Inbox(){
			global $APP_PATH;
		$this->assign('onnotif','1');
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
		
	
		
		include_once "MessageHelper.php";
		$start = $this->param('st');
		
		$message = new MessageHelper($this->Request);
		$profile = $this->getProfile();
	
		$this->widgets['my_wall'] = $message->inbox($profile->id,$this->param('st'));
		
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
		$widget_gallery->getListGallery(12);
		$this->assign("",$widget_gallery);
		if(is_array($this->widgets)){
			foreach($this->widgets as $name=>$val){
				$this->assign($name,$val);
			}
		}
		//--> Widgets end
		
		$this->View->assign("subtitle","INBOX");
		return $this->View->toString("Social/message.html");
	}
	function SendMessage(){
		global $APP_PATH;
		$user = $this->getUserInfo();
		$profile = $this->getProfile();
		include_once "MessageHelper.php";
		
		$recipients = explode(",",$this->post('recipients'));
		
		$message = new MessageHelper($this->Request);
		
		foreach($recipients as $to_id){
			$message->send($profile->id,intval($to_id),$this->post('subject'),$this->post('message'));
		}
		$msg = "Your message has been sent successfully !";
		//
		
		return $this->View->showMessage($msg,"?profile=1");
	}
	
	function ReplyInbox(){
		global $APP_PATH;
		$user = $this->getUserInfo();
		$profile = $this->getProfile();
		include_once "MessageHelper.php";
		$from_id=$this->post("from_id");
		$to_id=$this->post("to_id");
		$recipients = $this->param('recipientss');
		
		$message = new MessageHelper($this->Request);
		
			$message->send($profile->id,intval($to_id),$this->post('subject'),$this->post('message'));
		$msg = "Your message reply has been sent successfully !";
		//
		return $this->View->showMessage($msg,"?profile=1");
	}
	function showReplyInbox(){
		global $APP_PATH;
		
		$id=$this->Request->getParam("id");
		$sender=$this->Request->getParam("sender");
		$namesender=$this->Request->getParam("namesender");
		$subject=$this->Request->getParam("subject");
		$isi=$this->Request->getParam("isi");
		
		$this->assign('onnotif','1');
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
		
		/*
		//WIDGETS
		include_once "Widgets/WidgetWall.php";
		$widget_wall = new WidgetWall($this->Request);
		$widget_wall->my_wall($u['id']);
		$this->widgets['my_wall'] = $widget_wall;
		*/
		
		include_once "MessageHelper.php";
		$start = $this->param('st');
		
		$message = new MessageHelper($this->Request);
		$profile = $this->getProfile();
		
		$this->widgets['my_wall'] = $message->reply_message($sender,$id);
		
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
		$widget_gallery->getListGallery(12);
		$this->assign("",$widget_gallery);
		if(is_array($this->widgets)){
			foreach($this->widgets as $name=>$val){
				$this->assign($name,$val);
			}
		}
		//--> Widgets end
		$this->View->assign("subtitle","Reply a Message");
		return $this->View->toString("Social/message.html");
		
	}
	
	function Message(){
		global $APP_PATH;
		$this->assign('onnotif','1');
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
		
		/*
		//WIDGETS
		include_once "Widgets/WidgetWall.php";
		$widget_wall = new WidgetWall($this->Request);
		$widget_wall->my_wall($u['id']);
		$this->widgets['my_wall'] = $widget_wall;
		*/
		
		include_once "MessageHelper.php";
		$start = $this->param('st');
		
		$message = new MessageHelper($this->Request);
		$profile = $this->getProfile();
		
		$this->widgets['my_wall'] = $message->send_form();
		
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
		$widget_gallery->getListGallery(12);
		$this->assign("",$widget_gallery);
		if(is_array($this->widgets)){
			foreach($this->widgets as $name=>$val){
				$this->assign($name,$val);
			}
		}
		//--> Widgets end
		
		$this->View->assign("subtitle","Send a Message");
		return $this->View->toString("Social/message.html");
	}
	function KonfirmasiBA(){
		$user = $this->getUserInfo();
		include_once "BAHelper.php";
		$helper = new BAHelper(null);	
		if( $this->param('act') == 'ajax' ){
			$events = $helper->getBaEvents( $this->param('id') );
			$json = array( 	'jumlah' => $events[0]['attendants'],
									'events' => $events );
			echo json_encode( $json ); 
			exit;
		}else if( $this->param('act') == 'save' ){
			$baid = $this->param('ba');
			$eventid = $this->param('event');
			$konfirmasi = $this->param('konfirmasi');
			$jumlah = $this->param('jumlah');
			
			if( $helper->confirmedEvents($baid,$eventid,$jumlah,$konfirmasi) ){
				return $this->View->showMessage("Berhasil konfirmasi event", "index.php?konfirmasi_ba=1");	
			}else{
				return $this->View->showMessage("Gagal konfirmasi event", "index.php?konfirmasi_ba=1");
			}
			
		}else{
			$ba = $helper->leaderGetBa( $user['id'] );
			$this->assign('ba', $ba);
			$events = $helper->getBaEvents( $ba[0]['id'] );
			$this->assign('events', $events);
			$this->assign('jumlah', $events[0]['attendants']);
			return $this->View->toString("Social/konfirmasi_ba.html");
		}
	}
	
	function GalleryContent($content){
		global $APP_PATH;
		include_once $APP_PATH."Gallery/Gallery.php";
		
		
		
		$req = $this->Request;
		$gallery = new Gallery($req);
		$this->View->assign('content',$content);
		
		
		
		if($req->getParam("album")!=NULL){
			$album_id = intval($req->getParam("album"));
			$this->assign("content_title",$gallery->getAlbumName($album_id));
		}
		if($req->getParam("insertAlbum")=="1"){
			$this->assign("content_title","Insert Album");
		}	
		if($req->getParam("insertpicgallery")=="1"){
			$this->assign("content_title","Insert New Picture");
		}
		if($req->getParam("getupdateAlbum")=="1"){
			$this->assign("content_title","Update Album");
		}
		if($req->getParam("getupdatepic")=="1"){
			$this->assign("content_title","Update Picture");
		}
		if($req->getParam("myalbum")=="1"){
			$this->assign("content_title","My Album");
		}
		
		//WIDGETS
		include_once "Widgets/WidgetEvent.php";
		$widget_event = new WidgetEvent($this->Request);
		$this->assign("acara_terkini",$widget_event);
		
		include_once "Widgets/WidgetBanner.php";
		$widget_banner = new WidgetBanner($this->Request);
		$this->assign("banner",$widget_banner);
		
		include_once "Widgets/WidgetBA.php";
		$widget_ba = new WidgetBA($this->Request);
		$widget_ba->getBACharts();
		$this->assign("ba_charts",$widget_ba);
		
		include_once "Widgets/WidgetGallery.php";
		$widget_gallery = new WidgetGallery($this->Request);
		$widget_gallery->getListGallery(12);
		$this->assign("photo_gallery",$widget_gallery);
		
		
		return $this->View->toString("Social/content_gallery.html");
	}
	function NetworkUpdates(){
		$req = $this->Request;
		
		
		$user = $this->getUserInfo();
		$this->assign('head_title','NETWORK UPDATES');
		
		$start = intval($req->getParam("st"));
		
		//updatenya 
		$updates = $this->news->getFeeds($user['id'],$start,20);
		$this->View->assign("list",$updates);
		
		//WIDGETS
		include_once "Widgets/WidgetEvent.php";
		$widget_event = new WidgetEvent($this->Request);
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
		$widget_gallery->getListGallery(12);
		$this->assign("photo_gallery",$widget_gallery);
		
		//--> END OF WIDGETS
		return $this->View->toString("Social/widget_news_feeds.html");
	}
	function TopPerformers(){
		include_once "BAHelper.php";
		
		$user = $this->getUserInfo();
		$start = intval($this->Request->getParam('st'));
		$helper = new BAHelper(null);
		
		$show = $this->param('show');
		
		if( $show == 'pl'){
			$lists = $helper->getTopPLList($user['id'],$start,12);
			$ba_list = $lists[0];
			$total_rows = $lists[1];
			$this->View->assign("is_pl",1);
			$this->View->assign("title",'Project Leaders');
		}elseif($show == 'ba'){
			$lists = $helper->getTopBAList($user['id'],$start,12);
			$ba_list = $lists[0];
			$total_rows = $lists[1];
			$this->View->assign("title",'Brand Ambasadors');
		}else{
			$lists = $helper->getTopPerformanceList($user['id'],$start,12);
			$ba_list = $lists[0];
			$total_rows = $lists[1];
			$this->View->assign("title",'Top Performers');
		}
		
		$this->View->assign("ba_list",$ba_list);
		
		//WIDGETS
		include_once "Widgets/WidgetEvent.php";
		$widget_event = new WidgetEvent($this->Request);
		$this->assign("acara_terkini",$widget_event);
		
		include_once "Widgets/WidgetBanner.php";
		$widget_banner = new WidgetBanner($this->Request);
		$this->assign("banner",$widget_banner);
		
		include_once "Widgets/WidgetGallery.php";
		$widget_gallery = new WidgetGallery($this->Request);
		$widget_gallery->getListGallery(12);
		$this->assign("photo_gallery",$widget_gallery);
		
		//--> END OF WIDGETS
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->generate($start, 12, $total_rows, "?top_performers=1"));
		
		return $this->View->toString("Social/daftar_top_performer.html");
	}
	
	function friends(){
		include_once "BAHelper.php";
		
		$u = $this->getUserInfo();
		$user = intval($this->Request->getParam('user'));
		$start = intval($this->Request->getParam('st'));
		$max = 12;
		
		$this->View->assign('onUser',$u['id']);
		
		$this->open(0);
		$que = "SELECT count(*) total FROM social_network n LEFT JOIN social_member m ON n.friend_id=m.id WHERE n.user_id=$user && n.is_visible=1;";
		$r = $this->fetch($que);
		$total = $r['total'];
		
		$que = "SELECT 
						n.*,
						m.*,
						u.friend_id
					FROM 
						social_network n
						LEFT JOIN social_member m
						ON n.friend_id=m.id
						LEFT JOIN (SELECT friend_id FROM social_network WHERE user_id=".$u['id'].") u
						ON m.id=u.friend_id 
					WHERE 
						n.user_id=$user && 
						n.is_visible=1
					LIMIT
					$start,$max";
		$ba_list = $this->fetch($que,1);
		
		$this->View->assign("ba_list",$ba_list);
		
		//WIDGETS
		include_once "Widgets/WidgetEvent.php";
		$widget_event = new WidgetEvent($this->Request);
		$this->assign("acara_terkini",$widget_event);
		
		include_once "Widgets/WidgetBanner.php";
		$widget_banner = new WidgetBanner($this->Request);
		$this->assign("banner",$widget_banner);
		
		include_once "Widgets/WidgetGallery.php";
		$widget_gallery = new WidgetGallery($this->Request);
		$widget_gallery->getListGallery(12);
		$this->assign("photo_gallery",$widget_gallery);
		
		//--> END OF WIDGETS
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->generate($start, $max, $total, "?friends=1&user=$user"));
		$this->close();
		return $this->View->toString("Social/friends.html");
	}
	
	function Home(){
		include_once "Widgets/WidgetBerita.php";
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
		
		$widget_network->getNetworkUpdates($user['id']);
		$this->assign("network_updates",$widget_network);
		
		include_once "Widgets/WidgetBanner.php";
		$widget_banner = new WidgetBanner($this->Request);
		$this->assign("banner",$widget_banner);
		
		include_once "Widgets/WidgetGallery.php";
		$widget_gallery = new WidgetGallery($this->Request);
		$widget_gallery->getGalleryUpdates();
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
		
		//echo "SELECT * FROM social_posts WHERE user_id=".$u['id']." ORDER BY post_time DESC limit 1;";
		$this->open(0);
		$la = $this->fetch("SELECT * FROM social_posts WHERE user_id=".$u['id']." ORDER BY post_time DESC limit 1;");
		$this->close();
		$this->assign("last_post",$la['post']);
		
		//WIDGETS
		include_once "Widgets/WidgetWall.php";
		$widget_wall = new WidgetWall($this->Request);
		$widget_wall->my_wall($u['id']);
		$this->widgets['my_wall'] = $widget_wall;
		
		include_once "Widgets/WidgetBA.php";
		$widget_my_network = new WidgetBA($this->Request);
		$widget_my_network->my_network($u['id'], $isMySelf, $u['name']);
		$this->widgets['my_network'] = $widget_my_network;
		
		//Performance Chart
		$widget_performance = new WidgetBA($this->Request);
		$widget_performance->getMyPerformance($u['id']);
		$this->widgets['my_performance'] = $widget_performance;
		
		
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
		$this->assign("",$widget_gallery);
		if(is_array($this->widgets)){
			foreach($this->widgets as $name=>$val){
				$this->assign($name,$val);
			}
		}
		//--> Widgets end
		
		
		return $this->View->toString("Social/my_profile.html");
	}
	function News(){
		$user = $this->getUserInfo();
		$this->assign('head_title','NEWS UPDATE');
		
		include_once "Widgets/WidgetBerita.php";
		$widget_berita = new WidgetBerita($this->Request, $user['id'], $user['type']);
		$widget_berita->getListNews();
		
		if( $this->param("id") != null ){
			$widget_berita->getNews( $this->param('id') );
		}
		if( $this->param("sid") != null && $this->param("act") == 'like' ){
			$widget_berita->likeNews( $this->param('sid') );
		}
		if( $this->param("sid") != null && $this->param("act") == 'unlike' ){
			$widget_berita->unlikeNews( $this->param('sid') );
		}
		if( $this->param("sid") != null && $this->param("act") == 'comment' ){
			$widget_berita->addComment( $this->param('sid'), $this->param('text') );
		}
		if( $this->param("sid") != null && $this->param("act") == 'hide' ){
			$widget_berita->hideComment( $this->param('sid') );
		}
		if( $this->param("sid") == null && $this->param("act") == 'add' ){
			$widget_berita->addEvent();
		}
		
		
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
		$widget_gallery->getListGallery(12);
		$this->assign("photo_gallery",$widget_gallery); 
		
		include_once "Widgets/WidgetDownload.php";
		$widget_download = new WidgetDownload($this->Request);
//		$widget_download->
		$this->assign("download", $widget_download);
		
		$content = $widget_berita;
		if( $this->param("id") == null ){
			include_once "Widgets/WidgetNetworkUpdates.php";
			$widget_network = new WidgetNetworkUpdates($this->Request);
			$widget_network->getNetworkUpdates($user['id']);
			$this->assign("network_updates",$widget_network);
			$content.=$widget_network;
		}
		
		return  $content;
	}
	function Events(){
	$user = $this->getUserInfo();
		
		include_once "Widgets/WidgetEvent.php";
		$widget_event = new WidgetEvent($this->Request, $user['id'], $user['type'] );
		//echo 'test <hr />';
		$widget_event->getListEvent();
		//echo 'test <hr />';
		if( $this->param("id") != null ){
			$widget_event->getEvent( $this->param('id') );
			//echo 'id null <hr />';
		}
		if( $this->param("sid") != null && $this->param("act") == 'like' ){
			$widget_event->likeEvent( $this->param('sid') );
			//echo 'id tidak null act like <hr />';
		}
		if( $this->param("sid") != null && $this->param("act") == 'unlike' ){
			$widget_event->unlikeEvent( $this->param('sid') );
		}
		if( $this->param("sid") != null && $this->param("act") == 'comment' ){
			$widget_event->addComment( $this->param('sid'), $this->param('text') );
		}
		if( $this->param("sid") != null && $this->param("act") == 'hide' ){
			$widget_event->hideComment( $this->param('sid') );
		}
		if( $this->param("sid") == null && $this->param("act") == 'add' ){
			$widget_event->addEvent();
		}
		//exit;
		
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
		$widget_gallery->getListGallery(12);
		$this->assign("photo_gallery",$widget_gallery);
		
		$content = $widget_event;
		
		return  $content;
	}
	function Gallery(){
		global $APP_PATH;
		$this->assign('head_title','GALLERY');
		
		include_once $APP_PATH."Gallery/Gallery.php";
		
		$gallery = new Gallery($this->Request);
		$user = $this->getUserInfo();
		$content = $gallery->main($user['id']);
		
		return $content;
	}
	function Download(){
		global $APP_PATH;
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
		$widget_gallery->getListGallery(12);
		$this->assign("photo_gallery",$widget_gallery);
		
		include_once $APP_PATH.'Download/Download.php';
		$user = $this->getUserInfo();
		$download = new Download($this->Request, $user['id']);
		$content = $download->main();
		return  $content;
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
		global $APP_PATH;
		$this->assign('onnotif','1');
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
		
		/*
		//WIDGETS
		include_once "Widgets/WidgetWall.php";
		$widget_wall = new WidgetWall($this->Request);
		$widget_wall->my_wall($u['id']);
		$this->widgets['my_wall'] = $widget_wall;
		*/
		
		include_once "NotificationHelper.php";
		$start = $this->param('st');
		
		$notify = new NotificationHelper($this->Request);
		$profile = $this->getProfile();
		if($this->param('clear')=="1"){
			$notify->reset($profile->id);
		}
		$this->widgets['my_wall'] = $notify->getNotifications($profile->id,$start,20);
		
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
		$widget_gallery->getListGallery(12);
		$this->assign("",$widget_gallery);
		if(is_array($this->widgets)){
			foreach($this->widgets as $name=>$val){
				$this->assign($name,$val);
			}
		}
		//--> Widgets end
		return $this->View->toString("Social/my_profile.html");
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
	function RejectFriend(){
		include_once "MemberHelper.php";
		$member = new MemberHelper(null);
		$friend_id = $this->param("user_id");
		$user_id = $this->param("friend_id"); //jgn bingung ya.. user id yang accept request adalah si friend.
		$friend = $member->getProfile($friend_id);
		$user = $member->getProfile($user_id);
		
		if($member->isFriend($user_id,$friend_id)){
			$this->assign("is_friend","1");
		}else{
			return $this->rejectNotification($user,$friend_id);
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
		return $this->View->showMessage($msg,"?top_performers=1");
	}
	function rejectNotification($profile,$friend_id){
		include_once "NotificationHelper.php";
		$notify = new NotificationHelper($this->Request);
		
		$msg = strip_tags($profile['name'])." has refused your request.";
		
		if($notify->send($friend_id, $msg, $options)){
			$msg = "The request has been rejected successfully !";
		}else{
			$msg = "Sorry, cannot send your request. Please try again later !";
		}
		return $this->View->showMessage($msg,"?top_performers=1");
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
		//if($mop_token==null){
			//$mop_token = $this->session->get('mop_token');
		///}
		if($this->session->get('mop_profile')==NULL){
			//kalo gak ada mop token.. maka redirect ke mop login page.
			//if(strlen($mop_token)==0){
			//	sendRedirect("login.php");
			//	die();
			//}else{
			$mop_profile = $this->mop->getProfile(null);
			if($mop_profile==""){
				return false;
			}
			//$this->session->set('mop_token',$mop_token);
			
			$profile = $this->sync_profile($mop_profile);
			
			if($profile==NULL){
				return false;
			}
			$o_profile = json_decode($profile);
			$this->UpdateLoginTime($o_profile->id);
			$this->session->set('mop_profile',urlencode64($profile));
			$_SESSION['usesstoken'] = urlencode64($o_profile->id);
			
			global $tracker;
			$tracker->doTrack(1,$o_profile->id, 2, 1, 'LOGIN');
			return true;
			//}
		}else{
			return true;
		}
	}
	function UpdateLoginTime($user_id){
		include_once "MemberHelper.php";
		$member = new MemberHelper(null);
		
		$rs =  $member->update_login_time($user_id);
		return $rs;
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
	
	function forum(){
		global $APP_PATH;
		include_once $APP_PATH."forum/forum.php";
		
		$user = $this->getUserInfo();
		
		$req = $this->Request;
		$forum = new forum($req,$user);
		return $forum->main();
	}
	
	function challenge(){
		global $APP_PATH;
		include_once $APP_PATH."challenge/challenge.php";
		$user = $this->getUserInfo();
		$req = $this->Request;
		$challenge = new challenge($req,$user);
		$check = $challenge->checkChallengeBonus();
		$bonus = $challenge->checkBonusInfo();
		$this->assign('check',$check);
		$this->assign('bonus',$bonus);
		return $challenge->main();
	}
	
}
?>
