<?php
	/*
	 * Model untuk function inbox di module user
	 * @Babar 26/03/2012
	 */
	class inboxModel extends SQLData{
		function __construct(){ parent::SQLData();	}
		
		function MessageStatus($msg_id){
			$q = "UPDATE ".DB_PREFIX."_message SET message_status='1'
					WHERE message_id='".$msg_id."'";
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
		
		function ReadMessage($user_id,$msg_id){
			$q = "SELECT * FROM ".DB_PREFIX."_message 
					WHERE message_to='".$user_id."' AND message_history='0' AND message_id='".$msg_id."' LIMIT 1";
			$this->open(0);
			$r = $this->fetch($q);
			$this->close();
			return $r;
		}
		
		
		function NotificationStatus($msg_id){
			$q = "UPDATE ".GameDB.".racing_history SET has_read=1
					WHERE id=".$msg_id."";
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
	}
?>