<?php
global $APP_PATH;
require_once $APP_PATH."kana/helper/newsFeedHelper.php";
class RegisterHelper extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
	}
	function main(){
		$req = $this->Request;
		if($req->getParam('add') == 1){
			$_username = $req->getParam('email');
			$_password = $req->getParam('password');
			$_email = $req->getParam('email');
			$_name = $req->getParam('name');
			$_type = $req->getParam('type');
			$_status = $req->getParam('status');
			
			if( $_username != '' && $_password != '' && $_email != '' && $_name != '' && $_type != '' ){
				
				$salt = rand(1000,9999);
				$hash = sha1($_password.$_username.$salt);
				
				$this->open(0);
				$qry = "INSERT IGNORE INTO ".APPLICATION."_member 
							(name,email,username,password,type,n_status,register_date,salt,register_id) VALUES
							('$_name','$_email','$_username','$hash',0,0,NOW(),'$salt','".date('dmY')."');";
				
				if($this->query($qry)){
					
					// newsfeed
					$newsfeed = new newsFeedHelper();
					$uid = mysql_insert_id();
					$opt = array("name"=>$_name);
					$newsfeed->sendNF($uid,1,$opt);
					
					// message
					return $this->View->showMessage("Success","index.php");
					
				}else{
					
					//echo $qry.'<hr/>';
					
					//echo mysql_error();
					
					$this->assign('msg','Error, please try again!');
				
				}
				
				$this->close();
			
			}else{
				
				$this->assign('msg','Error, please fill all field!');
			
			}
		}
		return $this->out(APPLICATION . "/register.html");
	}
}