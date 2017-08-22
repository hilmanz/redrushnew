<?php
include_once "../../config/config.inc.php";
include_once "../../engines/functions.php";

class qrcode{
private $curl;
private $api_url = 'http://rrservice.marlboro.co.id/';
function __construct(){
include_once "curl_class.php";
$this->curl = new curl_class();
}

function add_all_qr_user(){
		$params = array();
		$params['service'] = "qrcode_service";
		$params['method']  = "add_all_qrcode_user";
		$params['process'] = true;
		$url = $this->api_url."index.php?".http_build_query($params);
		$resp_txt = $this->curl->get($url);
		print_r(($resp_txt));
	
	}



}


$class = new qrcode;
$class->add_all_qr_user();
die();

?>
