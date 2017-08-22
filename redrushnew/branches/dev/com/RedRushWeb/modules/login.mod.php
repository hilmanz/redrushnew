<?php
	/*
	 *Login Module CLass
	 *Babar 17/01/2012
	 */
	 global $APP_PATH;
	 require_once $APP_PATH.APPLICATION."/helper/SessionHelper.php";
	 class login extends App{
		  var $Request;
		  var $View;
		  var $session;
		  var $login = false;
		  
		  function __construct($req){
			  $this->Request = $req;
			  $this->View = new BasicView();
			  $this->setVar();
			  $this->session = new SessionHelper(APPLICATION.'_Session');
		
			  if( $this->session->get('user') ){
			      $this->login = true;
		      }
		  }
		  
		  function home(){
		  	return $this->contentString("/login.html",true);
		  }
		  
		  function checkLogin(){
			return $this->login;
		  }
		  
		  function loginSession(){
			  $ok = false;
			  
			  if($_POST['login']){
				  if($this->goLogin()){
					  sendRedirect('index.php');
					  die();
				  }
				  if(!$ok){
					  $this->assign("login_error","1");
				  }
			  }
			  return $this->contentString('/login.html');
		  }
		  function goLogin(){
			  $username = trim(strtolower($_POST['username']));
			  $password = trim($_POST['password']);
			 
			  $this->open(0);
			  
			  $sql = "SELECT * FROM kana_member WHERE n_status=1 AND username='".$username."' LIMIT 1";
			  $rs = $this->fetch($sql);
			 
			 
			  $this->close();
			  
			  $hash = sha1($password.$username.$rs['salt']);
			  
			  /*
			  echo $sql.'<hr />';
			  echo $hash.' - '.$rs['password'];
			  exit;
			  */
			  
			  if($rs['username']==$username && $rs['password']==$hash){
				  $id = $rs['id'];
				  // Activity Tracking
				  $this->activityTrack("login",$id);
				  
				  //$rs['register_id']= $rs['id'];
				  //$rs['name'] = $rs['nama'];
				  //$_SESSION['mop'] = urlencode64(json_encode($rs));
				  $this->session->set('user',urlencode64(json_encode($rs)));
				  
				  return true;
			  }
			  
		  }
		  
		  function getProfile(){
		
			  $user = json_decode(urldecode64($this->session->get('user')));
			  
			  return $user;
		  
		  }
		  
		  // Babar 10/01/12 -> Activity Tracking
		  function activityTrack($ket='',$uid=''){
			  //echo "test";exit;
			  if($uid==''){
			  $userid = strip_tags($this->user->id);
			  }
			  else {$userid=$uid;}
			  $p		= "login";
			  $url 	= $_SERVER['QUERY_STRING'];
			  $requri	= $_SERVER['REQUEST_URI'];
			  $ip		= $_SERVER['REMOTE_ADDR'];
			  $agent	= $_SERVER['HTTP_USER_AGENT'];
			  
			  if($userid!=0){
				  $q = "INSERT INTO ".DB_PREFIX."_activity (user_id, time, url, request_uri, page, action, ip, user_agent, keterangan)
						  VALUES ('".$userid."', NOW(), '".$url."', '".$requri."', '".$p."', '".$a."', '".$ip."', '".$agent."', '".$ket."')";
				  $this->open(0);
				  $this->query($q);
				  $this->close();
				  //echo mysql_error();exit;
			  }
		  }
	 }
	 
	 /* End of Login Module Class */