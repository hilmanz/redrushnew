<?php
class ajax extends App{
	
	var $Request;
	
	var $View;
	

	function __construct($req){
		$this->Request = $req;
		$this->setVar();
				
	}
	
	function tlu001(){
	
			require_once APP_PATH.APPLICATION."/helper/activityReportHelper.php";
			$log = new activityReportHelper($this->Request,$this->user->id);
			$log->activityTime();
			die();			
	}
	
	// function paut001(){
	
	// require_once APP_PATH.APPLICATION."/helper/apiHelper.php";
	// $API = new apiHelper();
	// $data = json_decode($API->add_title_to_all_user(true));
	// die();	
	// }
	
	function qr001(){
	//use token sha1(date(Ymd.arukaterra))
	require_once APP_PATH.APPLICATION."/helper/apiHelper.php";
	$API = new apiHelper();
	$data = json_decode($API->add_all_qr_user(true));
	die();	
	
	}
	
	function ru001(){
	//use token sha1(date(Ymd.arukaterra))
	require_once APP_PATH.APPLICATION."/helper/apiHelper.php";
	$API = new apiHelper();
	$data = json_decode($API->add_user_rank(true));
	die();	
	}
	
	function inu001(){
	//use token sha1(date(Ymd.arukaterra))

	require_once APP_PATH.APPLICATION."/helper/apiHelper.php";
	$API = new apiHelper();
	
	//count notif
	$data = json_decode($API->get_user_notification($this->user->id,20));
	$count_not_read=0;

	foreach($data as $notification){

	if($notification->has_read==0) $count_not_read++;
	}
	
	//count message
	$data = json_decode($API->get_message_inbox_from_admin($this->user->id));
	foreach($data as $message){

	if($message->message_status=='0') $count_not_read++;
	}
	
	echo $count_not_read;

	die();	
	}
	
	
	function addProgressBar(){
	require_once APP_PATH.APPLICATION."/helper/apiHelper.php";
	$API = new apiHelper();
	// if(file_exists(APP_PATH.APPLICATION."/helper/apiHelper.php")) echo 'ada';
	$part_id = $this->Request->getPost('part_id');
	 // $part_id = 7;
	$data = $API->add_progress_bar($part_id);
	header('Content-type: application/json');
	echo $data;
	die();	
	
	}
	
	function cg001(){
		$q = "INSERT IGNORE tbl_deny_garage_notification (user_id) VALUES ({$this->user->id}) ;";
		$this->open(0);
		$this->query($q);
		$this->close();
		die();
	}
	
}

?>