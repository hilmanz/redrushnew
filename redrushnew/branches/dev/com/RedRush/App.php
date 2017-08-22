<?php
/**
 * API
 */
global $APP_PATH;
class RedRushAPI extends API{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $loginHelper; 
	var $user = array();
	
	function __construct($req){
		API::__construct($req);
	}
	
	function setVar(){
		$this->loginHelper = new loginHelper();
		$this->user = $this->loginHelper->getProfile();
	}
	
	
	/*
	 *	Mengatur setiap paramater di alihkan ke class yang mengaturnya
	 *
	 *	Urutan paramater:
	 *	- page			(nama class) 
	 *	- act				(nama method)
	 *	- optional		(paramater selanjutnya optional, tergantung kebutuhan)
	 */
	function run(){
		global $APP_PATH,$CONFIG;
		$service = $this->_request('service');
		$method = $this->_request('method');
		if($service!=''){
			require_once 'modules/'. $this->clean($service).'.php';
			
			if(class_exists($service)){
				$obj = new $service($this->Request);
				if($method=="test"&&$CONFIG['DEVELOPMENT']){
					$str = $obj->execute('test');
					return $str;
				}else if($method!="test"){
					$str = $obj->execute($method);
					$obj = null;
					return $str;
				}else{
					return $this->toJson(0,'Invalid Method',null);
				}
			}else{
				return $this->toJson(405,'failed to instantiate the object',null);
			}
		}else{
			return $this->toJson(404,'Service not found',null);
		}
	}
}
?>