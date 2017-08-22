<?php


include_once $APP_PATH."RedRush/models/racing_model.php";
class parts_service extends API_Module{
	protected $model;

	function init(){
		$this->model = new racing_model();
	}
	
	function get_part(){
	$level_user = $this->_request('level_user');
	$this->model->open(0);
	$part = $this->model->get_inventory_part($level_user);
	$this->model->close();
	return $this->toJson(1,'part',$part);
	}
	
	function get_own_part(){
	$user_id = $this->_request('user_id');
	$part_id = $this->_request('part_id');
	$this->model->open(0);
	$own_part = $this->model->get_own_part($user_id,$part_id);
	$this->model->close();
	return $this->toJson(1,'own_part',$own_part);
	}
	
	function purchase_part(){
	$user_id = $this->_request('user_id');
	$part_id = $this->_request('part_id');
	$this->model->open(0);
	$purchase = $this->model->purchase_part($user_id,$part_id);
	$this->model->close();
	return $this->toJson(1,'purchase',$purchase);
	}
	
	function get_part_by_id(){
	$part_id = $this->_request('part_id');
	$this->model->open(0);
	$get_part_by_id = $this->model->get_part_id($part_id);
	$this->model->close();
	return $this->toJson(1,'get_part_by_id',$get_part_by_id);
	}
	
	function cek_winning_ultimate_car(){
	$user_id = $this->_request('user_id');
	$this->model->open(0);
	$cek_winning_ultimate_car = $this->model->cek_winning_ultimate_car($user_id);
	$this->model->close();
	return $this->toJson(1,'cek_winning_ultimate_car',$cek_winning_ultimate_car);
	}
			
}
?>