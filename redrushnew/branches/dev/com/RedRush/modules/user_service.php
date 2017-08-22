<?php


include_once $APP_PATH."RedRush/models/user_model.php";
class user_service extends API_Module{
	protected $model;
	
	
	function init(){
		$this->model = new user_model();
		
	}
	
	function first_login_got_point(){
		$user_id = $this->_request('user_id');
		$this->model->open(0);
		$is_first_login = $this->model->is_first_login($user_id);
		$message = $this->model->add_point_first_login($user_id,$is_first_login['mobile_type']);
		$this->model->close();
		return $this->toJson(1,'first_login_got_point',$message);
	}
	
	function get_user_notification(){
	$user_id = $this->_request('user_id');
	$total = $this->_request('total');
	$this->model->open(0);
	$get_user_notification = $this->model->get_user_notification($user_id,$total);
	$this->model->close();
	return $this->toJson(1,'get_user_notification',$get_user_notification);
	}
	
	function get_all_user_notification(){
	$total = $this->_request('total');
	$this->model->open(0);
	$get_all_user_notification = $this->model->get_all_user_notification($total);
	$this->model->close();
	return $this->toJson(1,'get_all_user_notification',$get_all_user_notification);
	}
		
	
	function find_player(){
	$search = $this->_request('search');
	$this->model->open(0);
	$find_player = $this->model->find_player($search);
	$this->model->close();
	return $this->toJson(1,'find_player',$find_player);
	}
	
	function get_recent_activity(){
	$user_id = $this->_request('user_id');
	$total = $this->_request('total');
	$this->model->open(0);
	$get_user_notification = $this->model->get_user_notification($user_id,$total);
	$this->model->close();
	return $this->toJson(1,'get_user_notification',$get_user_notification);
	}
	
	function send_user_notification_email(){
	$user_id = $this->_request('user_id');
	$opponent_id = $this->_request('opponent_id');
	$winner = $this->_request('winner');
	$report = $this->_request('report');
	$circuit = $this->_request('circuit');
	// $this->model->open(0);
	//$send_user_notification = $this->model->send_user_notification($user_id,$opponent_id,$winner,$report,$circuit);
	// $this->model->close();
	$send_user_notification = NULL;
	// print_r($send_user_notification);exit;
	return $this->toJson(1,'send_user_notification',$send_user_notification);
	}
	
	function un_notification_mail(){
	$user_id = $this->_request('user_id');
	$token = $this->_request('token');
	$web_token = sha1($user_id.sha1(date('Y')));
	// if($token!=$web_token) return  $this->toJson(1,'un_mail_notification','You dont have authorization for this services');
	$this->model->open(0);
	$un_mail_notification = $this->model->un_mail_notification($user_id,$token);
	$this->model->close();
	// print_r($send_user_notification);exit;
	sendRedirect(BASEURL);
	exit;
	}
	
	function change_profile(){
	$user_id =  $this->_request('user_id');
	$nickname = $this->_request('nickname');
	$image = $this->_request('image');
	$small_image = $this->_request('small_image');
	
	$this->model->open(0);
	$change_profile = $this->model->change_profile($user_id,$nickname,$image,$small_image);
	$this->model->close();
	return $this->toJson(1,'change_profile',$change_profile);
	
	}
	
	
	function get_race_report_notification(){
	$report_id =  $this->_request('report_id');
	$this->model->open(0);
	$get_race_report_notification = $this->model->get_race_report_notification($report_id);
	$this->model->close();
	// print_r( $get_race_report_notification);
	return $this->toJson(1,'get_race_report_notification',$get_race_report_notification);
	}
		
	function get_message_inbox_from_admin(){
	$user_id =  $this->_request('user_id');
	$this->model->open(0);
	$get_message_inbox_from_admin = $this->model->get_message_inbox_from_admin($user_id);
	$this->model->close();
	return $this->toJson(1,'get_message_inbox_from_admin',$get_message_inbox_from_admin);
	}
}
?>
