<?php
class MOPHelper extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
	}
	function loginSession(){
		$ok = false;
		
		if($this->post('login')){
			if($this->login()){
				$ok=true;
				
				/*
				if( $this->isLeader() ){
					sendRedirect('index.php?challenge=1');
				}else{
				*/
					sendRedirect('index.php');
				//}
				die();
			}
			if(!$ok){
				
				$this->assign("login_error","1");
			}
		}
		return $this->out('Social/login.html');
	}
	function main(){
		$this->mainLayout('common/blank.html');
	}
	function call($methodName,$params){
		global $CONFIG;
		//$params = $methodName."=1&".$this->toQueryString($params);
		//print $params;
		$params[$methodName] = 1;
		$ch = curl_init();
		//curl_setopt($ch, CURLOPT_MUTE, 1);
		curl_setopt($ch, CURLOPT_URL, $CONFIG['MOP_URL']);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
	function login(){
		$username = trim(strtolower($this->post('username')));
		$password = trim($this->post('password'));
		
		$this->open(0);
		
		$sql = "SELECT * FROM dm_member WHERE n_status=1 AND username='".$username."' LIMIT 1";
		$rs = $this->fetch($sql);
		
		$this->close();
		
		$hash = sha1($password.$username.$rs['salt']);
		if($rs['username']==$username&&$rs['password']==$hash){
			$rs['register_id']= $rs['id'];
			$rs['name'] = $rs['nama'];
			$_SESSION['mop'] = urlencode64(json_encode($rs));
			
			return true;
		}
		
	}
	function getProfile($session_id){
		return json_decode(urldecode64($_SESSION['mop']));
		//return $this->call('GetProfile',array("id"=>$session_id));
	}
	function toQueryString($params){
		$str = "";
		$n=0;
		foreach($params as $name=>$val){
			if($n==0){
				$n=1;
			}else{
				$str.="&";
			}
			$str.=$name."=".$val;
		}
		return $str;
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
	function isLeader(){
		$username = trim(strtolower($this->post('username')));
		$password = trim($this->post('password'));
		
		$this->open(0);
		
		$sql = "SELECT * FROM leader_ba_lookup a LEFT JOIN social_member b ON a.leader_id=b.id WHERE b.username='$username' LIMIT 1;";
		//echo $sql;exit;
		$rs = $this->fetch($sql);
		
		$this->close();
		
		if( intval($rs['id']) > 0){
			return true;
		}
	}
}
?>
