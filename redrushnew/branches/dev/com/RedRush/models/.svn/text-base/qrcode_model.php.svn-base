<?php 
class qrcode_model extends SQLData{
	var $logs;
	var $debug;
	
	function __construct(){
		global $ENGINE_PATH;
		include_once $ENGINE_PATH."Utility/Debugger.php";
		parent::SQLData();
		$this->debug = new Debugger();
	}
	
	
	function get_all_qr_user_detail(){
	
		$sql = 	"
		SELECT * FROM ".RedRushDB.".kana_member km 
		WHERE not exists 
		( SELECT user_id FROM ".RedRushDB.".tbl_qr_code_user qr WHERE qr.user_id = km.id ) AND n_status=1";
	
		return $this->fetch($sql,1);
	}
	
	function get_qr_user_detail($user_id){
	
		$sql = "SELECT * FROM ".RedRushDB.".kana_member
				WHERE id=".$user_id." LIMIT 1";
		return $this->fetch($sql);
	}
	
	function city_code($city_id=NULL){
		if($city_id==NULL) $city_id=215;
		$sql = "SELECT code FROM ".RedRushDB.".tbl_qr_city
				WHERE city_id =".$city_id." LIMIT 1";
		return $this->fetch($sql);
	}
	
	function genQRCode($code){
	 
    //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = '../public_html/contents/qr_code/';
    // include "qrlib.php";    
	global $ENGINE_PATH;
    include_once $ENGINE_PATH."Utility/phpqrcode/qrlib.php";  
	
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))  mkdir($PNG_TEMP_DIR);
    
    
    $filename = $PNG_TEMP_DIR.'test.png';
    
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'L';
    $matrixPointSize = 4;
  
        //it's very important!
        if (trim($code) == '') die();
            
        // user data
		$file = md5($code.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        $filename = $PNG_TEMP_DIR.$file;
        QRcode::png($code, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
	return array('address'=>$file);
	}
	
	function checkHasQR($user_id){
	$sql = "SELECT count(user_id) as total FROM ".RedRushDB.".tbl_qr_code_user WHERE user_id=".$user_id."";
	$data =$this->fetch($sql);	
	return $data['total'];
	}

	
	function add_qrcode_user($user_id){
	//tbl_qr_code_user 	
	$hasQr = $this->checkHasQR($user_id);
	// return $hasQr;
	if($hasQr > 0) return false;
	$dataUser = $this->get_qr_user_detail($user_id);
	$cityCode = $this->city_code($dataUser['city']);
	$zeroLengthUserID = 7 - strlen($dataUser['id']);
	
	for($i=1;$i<=$zeroLengthUserID;$i++){
	$codeUserId .= '0';
	}$i=null;
	
	$code = $cityCode['code'].$codeUserId.$dataUser['id'];

	$dataQR = $this->genQRCode($code);

	// return $dataQR ;
	$sql = "INSERT INTO ".RedRushDB.".tbl_qr_code_user 
			(user_id,path_qr_code,code) VALUES
			(".$dataUser['id'].",'".$dataQR['address']."','".$code."' )
		
		";
	// return $sql;
	return $this->query($sql);	
	}
	
	
	function add_qrcode_all_user($process=false){
	if($process==false) return false;
	$arrProgQR = array();
	$data = $this->get_all_qr_user_detail();
	// return $data;
	foreach($data as $user){
	$arrProgQR[]= $this->add_qrcode_user($user['id']);
	}
	return $arrProgQR;
	}
	
	
}
?>
