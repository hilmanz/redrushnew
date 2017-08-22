<?php


include_once $APP_PATH."RedRush/models/racing_model.php";
class user_rank_service extends API_Module{
	protected $model;
	
	
	function init(){
		$this->model = new racing_model();
		
	}
	//user title
	function add_title_user(){
	$user_id = $this->_request('user_id');
	$this->model->open(0);
	$add_title_user = $this->model->add_title_user($user_id);
	$this->model->close();
	return $this->toJson(1,'add_title_user',$add_title_user);
	}
	
	function get_title_user(){
	$user_id = $this->_request('user_id');
	$this->model->open(0);
	$get_title_user = $this->model->get_title_user($user_id);
	$this->model->close();
	return $this->toJson(1,'get_title_user',$get_title_user);
	}
	
	function add_title_to_all_user(){
	$process = $this->_request('process');
	$this->model->open(0);
	$add_title_to_all_user = $this->model->add_title_to_all_user($process);
	$this->model->close();
	return $this->toJson(1,'add_title_to_all_user',$add_title_to_all_user);
	}
	
	//top user
	function get_top_user(){
	$limit = $this->_request('limit');
	$this->model->open(0);
	$top_user = $this->model->get_top_user($limit);
	$this->model->close();
	return $this->toJson(1,'top_user',$top_user);
	}
	
	
	//level player
	function add_level_player(){
	$user_id = $this->_request('user_id');
	$part_id = $this->_request('part_id');
	$this->model->open(0);
	$level_player = $this->model->level_player($user_id,$part_id);
	$this->model->close();
	return $this->toJson(1,'level_player',$level_player);
	
	}
	
	function add_user_rank(){
	$process = $this->_request('process');
	$this->model->open(0);
	$add_rank_user = $this->model->add_rank_user($process);
	$this->model->close();
	return $this->toJson(1,'add_rank_user',$add_rank_user);
	}
	
	
}
?>