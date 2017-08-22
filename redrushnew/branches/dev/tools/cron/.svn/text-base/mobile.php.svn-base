<?php
include_once "../../config/config.inc.php";
include_once "../../engines/functions.php";

class mobile{
private $curl;
private $api_url = "http://rrservice.marlboro.co.id/";
protected $api_key = 'redrush_mobile';
protected $secret_key = 'r3drush_s3cr3t_k3y';
	
function __construct(){
include_once "curl_class.php";
$this->curl = new curl_class();
}

function getGameData(){
		$params = array();
		$params['service'] = "mobile_service";
		$params['method']  = "getGameData";
$url = $this->api_url."index.php?".http_build_query($params);
$resp_txt = $this->curl->get($url);
print_r(($resp_txt));


}

function getRegistrationData(){
		$params = array();
		$params['service'] = "mobile_service";
		$params['method']  = "getRegistrationData";
$url = $this->api_url."index.php?".http_build_query($params);
$resp_txt = $this->curl->get($url);
print_r(($resp_txt));


}

function distribution_point_on_mobile(){
		$params = array();
		$params['service'] = "mobile_service";
		$params['method']  = "distribution_point_on_mobile";
		$params['access_token'] =  sha1('distribution_point_on_mobile'.$this->api_key.(sha1($this->secret_key)).(date('YmdH')));
$url = $this->api_url."index.php?".http_build_query($params);
$resp_txt = $this->curl->get($url);
print_r(($resp_txt));


}




}


$class = new mobile;
// echo 'jalana';
$class->getGameData();
$class->getRegistrationData();
$class->distribution_point_on_mobile();
die();

?>
