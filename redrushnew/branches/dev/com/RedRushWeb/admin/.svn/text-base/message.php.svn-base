<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class message extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
	}
	function admin(){
		$act = $this->Request->getParam('act');
		if( $act == 'send' ){
			return $this->send();
		}elseif( $act == 'mass' ){
			return $this->mass();
		}elseif( $act == 'sendmass' ){
			return $this->sendMass();
		}else{
			return $this->searchEmail();
		}
	}
	function searchEmail(){
		$_email = $_GET['email'];
		if($_email != ''){
			$qry = "SELECT * FROM kana_member WHERE email LIKE '%$_email%';";
			$rs = $this->fetch($qry,1);
			$this->View->assign('rs',$rs);
		}
		return $this->View->toString("RedRushWeb/admin/message/message-search-email.html");
	}
	function mass(){
		return $this->View->toString("RedRushWeb/admin/message/message-send-all.html");
	}
	function send(){
		$_send = $_GET['send'];
		$_id = $_GET['id'];
		if($_send == 1){
			$_id = $_GET['id'];
			$_subject = $_GET['subject'];
			$_text = $_GET['text'];
			
			if($_id != '' && $_subject != '' && $_text != ''){		
				$qry = "INSERT INTO ".DB_PREFIX."_message (message_to,message_from,message_date,message_subject,message_text)
							VALUES
							('$_id','0',NOW(),'$_subject','$_text');";
				if($this->query($qry)){
					return $this->View->showMessage("Send Success","index.php?s=message");
				}else{
					return $this->View->showMessage("Send Failed","index.php?s=message");
				}
			}else{
				return $this->View->showMessage("Complete Form Please!","index.php?s=message");
			}
		}
		$this->View->assign('id',$_id);
		return $this->View->toString("RedRushWeb/admin/message/message-send.html");
	}
	
	/* Send mass message to user
	 *@author: babar 17/01/2012
	 */
	function sendMass(){
		/* Get Post Parameter */
		$subject = $this->Request->getPost('subject');
		$text = $this->Request->getPost('text');
		if(!empty($subject) && !empty($text)){
		  /* Get all user data */
		  $q = "SELECT id, name FROM kana_member";
		  $this->open(0);
		  $user = $this->fetch($q,1);
		  $this->close();	
		  foreach($user as $u){
			  $this->open(0);
			  $qry = "INSERT INTO ".DB_PREFIX."_message (message_to,message_from,message_date,message_subject,message_text)
							VALUES
							('".$u['id']."','0',NOW(),'".$subject."','".$text."');";
			  $this->query($qry);
			  $this->close();
		  }
		   return $this->View->showMessage("Send Success!","index.php?s=message&act=mass");
		}
		else{
			return $this->View->showMessage("Complete Form Please!","index.php?s=message&act=mass");	
		}
	}
}