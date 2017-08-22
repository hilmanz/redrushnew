<?php
class carHelper extends Application{

	var $Request;
	
	function __construct($req){
	$this->Request = $req;
	
	}
	
	
	function getCarData($userID){
		
		$sql = "SELECT * FROM tbl_car_data WHERE user_id=".$userID."";
		
		$this->open();
		$qData = $this->fetch($sql);
		$this->close();
		// print_r($qData['car_data']);exit;
		
		if($qData) $data = unserialize($qData['car_data']);
		else{
		$data = array(
				"body" => array ("type"=>"porche","color"=>"default"),
				"tire" =>  array ("type"=>"default","color"=>"default"),
				"tints" =>  array ("type"=>"default","color"=>"default"),
				"wing" =>  array ("type"=>"default","color"=>"default"),
				"decals"  => array ("type"=>"default","color"=>"default"),
				"hoods"  => array ("type"=>"default","color"=>"default")
				
		);
		}
		$identity = array('user_id'=>$userID);
		return json_encode(array('identity'=> $identity,'data' => $data));
	}
	
	
	function saveCarData(){

	$userID = $this->Request->getPost('userID');
	$body = $this->Request->getPost('bodyType');
	$bodyColor = $this->Request->getPost('bodyColor');
	$tire = $this->Request->getPost('tireType');
	$tireColor = $this->Request->getPost('tireColor');
	$tints = $this->Request->getPost('tintsType');
	$tintsColor = $this->Request->getPost('tintsColor') ;
	$wing = $this->Request->getPost('wingsType');
	$wingColor = $this->Request->getPost('wingsColor');
	$decals = $this->Request->getPost('decalsType');
	$decalsColor = $this->Request->getPost('decalsColor');
	$hoods = $this->Request->getPost('hoodsType');
	$hoodsColor = $this->Request->getPost('hoodsColor');
	
	$arrCarData = array(
				"body" =>  array ("type"=>$body,"color"=>$bodyColor),
				"tire"  => array ("type"=>$tire,"color"=>$tireColor),
				"tints"  => array ("type"=>$tints,"color"=>$tintsColor),
				"wings"  => array ("type"=>$wing,"color"=>$wingColor),
				"decals"  => array ("type"=>$decals,"color"=>$decalsColor),
				"hoods"  => array ("type"=>$hoods,"color"=>$hoodsColor)
	);
		
	$carData = serialize($arrCarData);
	
		$sql = "DELETE FROM tbl_car_data WHERE user_id=".$userID."";
		$this->open(0);
		$this->query($sql);
		$this->close();
		
		$sql = "INSERT INTO tbl_car_data (user_id,car_data) VALUES ($userID,'$carData')";
		$this->open(0);
		$this->query($sql);
		$this->close();
		
	// print_r($sql);
	// exit;
	
	// header('location:?page='.$this->Request->getParam('page').'');
	}
	
}
?>