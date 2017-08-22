<?php
class App extends Application{
	
	var $Request;
	
	var $View;
	
	var $_mainLayout="";
	
	var $loginHelper; 
	
	var $user = array();
	
	var $_widgetList = array();
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
	}
	
	function setVar(){
		
		global $CONFIG;
		
		if( $CONFIG['MOP'] ){
			include_once "helper/loginHelper.php";
			$this->loginHelper = new loginHelper();
			$this->user = $this->loginHelper->getProfile();
		}else{
			include_once "helper/loginHelper.php";
			$this->loginHelper = new loginHelper();
			$this->user = $this->loginHelper->getProfile();
		}
	}
	
	function main(){
		/* 
		 * Babar 12/01/12 
		 * User name, username, user id & user Login status
		 * Di assign ke template master
		 */
		include_once "helper/loginHelper.php";
		$this->loginHelper = new loginHelper();
		
		$this->login = $this->loginHelper->checkLogin();
		$this->user = $this->loginHelper->getProfile();
		
		$userid = $this->user->id;
		$user_name = $this->user->name;
		$username = $this->user->username;
		
		$str = $this->run();
		
		//$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
		//$this->assign('header',$this->View->toString(APPLICATION . "/header.html"));
		//$this->assign('footer',$this->View->toString(APPLICATION . "/footer.html"));
		$this->assign('isLogin',$this->login);
		$this->assign('user_name',$user_name);
		$this->assign('user_id',$userid);
		$this->assign('username',$username);
		$this->assign('mainContent',$str);
		$this->mainLayout(APPLICATION . '/master.html');		
	}
	
	/*
	 *	Mengatur setiap paramater di alihkan ke class yang mengaturnya
	 *
	 *	Urutan paramater:
	 *	- page			(nama class) 
	 *	- act			(nama method)
	 *	- optional	(paramater selanjutnya optional, tergantung kebutuhan)
	 */
	function run(){
		//print_r($_SERVER);exit;
		global $APP_PATH;
		$page = strtolower($this->Request->getParam('page'));
		$act = strtolower($this->Request->getParam('act'));
		
		// Activity Tracking
		$this->activityTrack();
		
		if( $page != '' )
		{
			
			//Check Page to DB
			$qry = "SELECT * FROM gm_pages p LEFT JOIN gm_page_content c ON p.page_id=c.page_id WHERE p.page_status='active' AND p.page_request='".mysql_escape_string($page)."' LIMIT 1;";
			$this->open(0);
			$rs = $this->fetch($qry);
			$this->close();
			
			//CHECK IF PAGE NEED LOGIN
			if( $rs['page_login'] == 'yes'){
				
				if( ! $this->loginHelper->checkLogin() ){
				
					sendRedirect('index.php?page=login');
					
					exit;
				
				}
				
			}
			
			//Set widget list
			$this->_widgetList = unserialize($rs['page_widgets']);
			
			//	STATIC PAGE
			if( $rs['page_type'] == 'static' )
			{
				if( is_file( '../templates/'.APPLICATION.'/static/'. $rs['page_template'] . '.html' ) ){
					$this->View->assign('title', $rs['content_title']);
					$this->View->assign('text', $rs['content_text']);
					return $this->contentString('/static/'.$rs['page_template'].'.html');
				}else{
					sendRedirect("index.php");
					die();
				}
			}
			// MODULE PAGE
			elseif($rs['page_type'] == 'module')
			{
				
				$module = str_replace('module_','',$rs['page_name']);
				if( is_file( '../com/'.APPLICATION.'/modules/'. $module.'.mod.php') ){
					require_once 'modules/'. $module.'.mod.php';
					$content = new $module($this->Request);
					
					if( $act != '' ){
						if( method_exists($content, $act) ){
							return $content->$act();
						}else{
							return $content->home();
						}
					}else{
						return $content->home();
					}
				}else{
					sendRedirect("index.php");
					die();
				}
			}
			else
			{
				sendRedirect("index.php");
				die();
			}
			
		}
		else
		{
			require_once 'modules/home.mod.php';
			
			$content = new home($this->Request);
			
			return $content->main();
		
		}
		
		
		
	}
	
	function birthday($birthday){
		$birth = explode(' ',$birthday);
		list($year,$month,$day) = explode("-",$birth[0]);
		$year_diff  = date("Y") - $year;
		$month_diff = date("m") - $month;
		$day_diff   = date("d") - $day;
		if ($day_diff < 0 || $month_diff < 0)
		  $year_diff--;
		return $year_diff;
	}
	
	function contentString($template=null,$module=false){
		
		$this->setWidgets($module);
		
		return $this->View->toString( APPLICATION . '/' . $template );
	
	}
	
	function setWidgets($module=false){
		
		global $APP_PATH;
		
		if( $module ){
			
			$page = strtolower($this->Request->getParam('page'));
			$act = strtolower($this->Request->getParam('act'));
			
			// Fixing menampilkan widget di home
			if($page=='' && $act==''){
				$page='home';
			}
			
			//Check Page to DB
			$qry = "SELECT * FROM gm_pages p LEFT JOIN gm_page_content c ON p.page_id=c.page_id WHERE p.page_status='active' AND p.page_request='".mysql_escape_string($page)."' LIMIT 1;";
			$this->open(0);
			$rs = $this->fetch($qry);
			$this->close();
			$this->_widgetList = unserialize($rs['page_widgets']);
			
		}
		
		foreach($this->_widgetList as $w){
			if( is_file($APP_PATH . APPLICATION . '/widgets/' . $w . '.widget.php') ){
				require_once 'widgets/' . $w . '.widget.php';
				$widget = new $w($this->Request,$this->user);
				$this->View->assign('widget_' . $w, $widget->show());
			}
		}
		
	}
	
	// Babar 10/01/12 -> Activity Tracking
	function activityTrack($ket='',$uid=''){
		//echo "test";exit;
		if($uid==''){
		$userid = strip_tags($this->user->id);
		}
		else {$userid=$uid;}
		$p 		= strtolower($this->Request->getParam('page'));
		$a 		= strtolower($this->Request->getParam('act'));
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
