<?php


include_once $APP_PATH."RedRush/models/qrcode_model.php";
class qrcode_service extends API_Module{
	protected $model;
	
	
	function init(){
		$this->model = new qrcode_model();
		
	}
	
	function add_qrcode_user(){
	$user_id = $this->_request('user_id');
	$this->model->open(0);
	$add_qrcode_user = $this->model->add_qrcode_user($user_id);
	$this->model->close();
	return $this->toJson(1,'add_qrcode_user',$add_qrcode_user);
	}
	
	function add_all_qrcode_user(){
	$process = $this->_request('process');
	$this->model->open(0);
	$add_qrcode_all_user = $this->model->add_qrcode_all_user($process);
	$this->model->close();
	return $this->toJson(1,'add_qrcode_all_user',$add_qrcode_all_user);
	}
	
	
	
}
?>