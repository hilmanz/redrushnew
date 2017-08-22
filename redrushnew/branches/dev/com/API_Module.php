<?php
/**
 * 
 * API Module base class
 * @author duf
 *
 */
class API_Module extends API{
	var $Request;
	var $View;
	var $_mainLayout="";
	function __construct($req){
		API::__construct($req);
		
		$this->init();
	}
	function init(){
		
	}
	function filter_inputs(){
		;
	}
	function execute($method){
		if(method_exists($this,$method)){
			return $this->$method();
		}else{
			return $this->toJson('405','Invalid method !');
		}
	}
}
?>