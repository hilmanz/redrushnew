<?php


include_once $APP_PATH."RedRush/models/racing_model.php";
class merchandise_service extends API_Module{
	protected $model;
		
	function init(){
		$this->model = new racing_model();
	
	}
	
	//merchandise
	function purchase_merchandise(){
		$user_id = $this->_request('user_id');
		$merchandise_id = $this->_request('merchandise_id');
		$data_personal['address']= $this->_request('address');
		$data_personal['phone']= $this->_request('phone');
		$data_personal['mobile']= $this->_request('mobile');
		$data_personal['city_name']= $this->_request('city_name');
		$data_personal['zip_code']= $this->_request('zip_code');
		$data_personal['variant']= $this->_request('variant');
		$this->model->open(0);
		$purchase_merchandise = $this->model->purchase_merchandise($user_id,$merchandise_id,$data_personal);
		$this->model->close();
		return $this->toJson(1,'purchase_merchandise',$purchase_merchandise);
	
	}
	
	function get_merchandise(){
				
		$this->model->open(0);
		$get_merchandise_item = $this->model->get_merchandise_item();
		$this->model->close();
		return $this->toJson(1,'get_merchandise_item',$get_merchandise_item);
	
	}
	
	function get_merchandiseByID(){
	
		$merchandise_id = $this->_request('merchandise_id');
		$this->model->open(0);
		$get_merchandise_item = $this->model->get_merchandise_item_by_id($merchandise_id);
		$this->model->close();
		return $this->toJson(1,'get_merchandise_item',$get_merchandise_item);
	
	}
	
	function get_own_merchandise(){
	$user_id = $this->_request('user_id');
	$merchandise_id = $this->_request('merchandise_id');
	$this->model->open(0);
	$own_merchandise = $this->model->get_own_merchandise($user_id,$merchandise_id);
	$this->model->close();
	return $this->toJson(1,'own_merchandise',$own_merchandise);
	}
	
}
?>