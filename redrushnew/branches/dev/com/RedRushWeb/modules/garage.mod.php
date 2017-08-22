<?php
class garage extends App{
	
	var $Request;
	
	var $View;
	
	var $API;
	
	var $CAR;
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		require_once APP_PATH.APPLICATION."/helper/apiHelper.php";
		$this->API = new apiHelper();
		
		require_once APP_PATH.APPLICATION."/helper/carHelper.php";
		$this->CAR = new carHelper($req);
		
	
		
	}
	
	function loading(){
		if( ! $this->loginHelper->checkLogin() ){
				
					sendRedirect('login.php');
					
					exit;
				
			}
		ob_start();
		$this->assign('loadIt','garage');
		$this->assign('targetIt','?page=garage');
		//img
				
		//img sparepart
		$imagesDir = 'img/sparepart/';
		$sparepart = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
		$this->assign('sparepart', $sparepart);
		
		//contents 
			//delivery
				//parts
				$imagesDir = 'contents/delivery/parts/';
				$parts = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
				$this->assign('parts', $parts);
			
			//carLeft
				//body
				$imagesDir = 'contents/carLeft/porche/body/';
				$body = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
				$this->assign('body', $body);
	
				//decals
				$imagesDir = 'contents/carLeft/porche/decals/';
				$decals = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
				$this->assign('decals', $decals);
				
				//hoods
				$imagesDir = 'contents/carLeft/porche/hoods/';
				$hoods = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
				$this->assign('hoods', $hoods);
				
				//tints
				$imagesDir = 'contents/carLeft/porche/tints/';
				$tints = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
				$this->assign('tints', $tints);
			
				//tire
				$imagesDir = 'contents/carLeft/porche/tire/';
				$tire = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
				$this->assign('tire', $tire);
				
				//wings
				$imagesDir = 'contents/carLeft/porche/wings/';
				$wings = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
				$this->assign('wings', $wings);
			
			//carRight
				//body
				$imagesDir = 'contents/carRight/porche/body/';
				$carRightbody = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
				$this->assign('carRightbody', $carRightbody);
	
				//decals
				$imagesDir = 'contents/carRight/porche/decals/';
				$carRightdecals = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
				$this->assign('carRightdecals', $carRightdecals);
				
				//hoods
				$imagesDir = 'contents/carRight/porche/hoods/';
				$carRighthoods = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
				$this->assign('carRighthoods', $carRighthoods);
				
				//tints
				$imagesDir = 'contents/carRight/porche/tints/';
				$carRighttints = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
				$this->assign('carRighttints', $carRighttints);
				
				
				//tire
				$imagesDir = 'contents/carRight/porche/tire/';
				$carRighttire = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
				$this->assign('carRighttire', $carRighttire);
				
				//wings
				$imagesDir = 'contents/carRight/porche/wings/';
				$carRightwings = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
				$this->assign('carRightwings', $carRightwings);	
				
	

		return $this->contentString("/loading.html",true);
	
	}
	
	
	function home(){
		
		//return $this->about;
		//$id = $this->Request->getParam('id');
		
		$rtoken = $this->Request->getParam('rtoken');
		// print_r($rtoken);
		if($rtoken==null){
	
				if( $this->Request->getParam('act')) {
				$act = $this->Request->getParam('act');
				return $this->$act;
				
				}else return $this->myGarage();
		}
		else{
			$players = unserialize(urldecode64($rtoken));
			if(intval($players['player2'])>0){
				
				return $this->otherGarage($players['player2']);
			}else{
				return $this->error_page();
			}
		}
	}
	
	function otherGarage($id){
		
		$userData = json_decode($this->API->getPlayerData($this->user->id));
		
		if($id==99999) return $this->ultimateGarage($this->user->id,$userData->level,$this->Request->getParam("rtoken"));
		$userData=null;
		// print_r($id);exit;
		$playerdata = $this->API->getPlayerData($id);
	
		$player = json_decode($playerdata);
			// print_r($player);exit;
		if($player->level=='') $player->level = 1;
		$pl['id'] = $player->id; 
		$pl['name'] = $player->name; 
		$pl['level'] = $player->level;
		$pl['title'] = $player->title;
		$pl['points'] = $player->points;
		$pl['races'] = $player->races;
		$pl['wins'] = $player->wins;
		$pl['small_img'] = $player->small_img;
		$pl['attributes']['speed'] = $player->attributes->speed;
		$pl['attributes']['handling'] = $player->attributes->handling;
		$pl['attributes']['acceleration'] = $player->attributes->acceleration;
		
		// print_r('<pre>');print_r($player);exit;
		$this->assign('rtoken',$this->Request->getParam("rtoken"));
		$this->assign('player',$pl);
		
		$carData = json_decode($this->CAR->getCarData($player->id));
		  // print_r('<pre>');print_r($carData);exit;
		$identity = $carData->identity;
		$data = $carData->data;
		foreach($identity as $key => $value){
		
		$this->assign($key,$value);
		}
		foreach($data as $key => $value){
		// $arrValue = array($value);
		$this->assign($key,array('type' => $value->type,'color' => $value->color));
			
		
		}
		$this->log('garage',$this->user->id);
		return $this->contentString("/garages.html",true);
	}
	
	function myGarage(){
		$player = $this->user;
		
		$playerdata = $this->API->getPlayerData($player->id);
		$playerdata = json_decode($playerdata);
		
		$racingPart = $this->API->getRacingPart($playerdata->level);
		$racingPart = json_decode($racingPart);
	
		foreach($racingPart as $n=>$v){
			$racingPart[$n]->ownPart = json_decode($this->API->getOwnPart($player->id,$racingPart[$n]->id));
		}
		// print_r('<pre>');
		foreach($racingPart as $n=>$v){
			$racingPartbyCategory[$v->category_item][] = $v;
		}
		$racingPart = null;
		// print_r('<pre>');	print_r($racingPartbyCategory);	exit;
		$this->assign('parts',$racingPartbyCategory);
	
		
		foreach($playerdata as $key => $value){
		$this->assign($key,$value);		
		}
		// print_r('<pre>');print_r($player);exit;
		$carData = json_decode($this->CAR->getCarData($player->id));
		  // print_r('<pre>');print_r($carData);exit;
		$identity = $carData->identity;
		$data = $carData->data;
		foreach($identity as $key => $value){
		
		$this->assign($key,$value);
		}
		foreach($data as $key => $value){
		// $arrValue = array($value);
		$this->assign($key,array('type' => $value->type,'color' => $value->color));
			
		
		}
		$this->log('page','garage');
			if($this->user->verified!='1') return $this->contentString("/not_verified_garage.html",true);
		$this->open(0);
		$qcek="SELECT COUNT(user_id) total FROM tbl_deny_garage_notification WHERE user_id={$this->user->id} LIMIT 1";
		// echo $qcek;exit;
		$total = $this->fetch($qcek);
		$total = $total['total'];
		$this->close();
		
		$cek_winning_ultimate_car = json_decode($this->API->cek_winning_ultimate_car($this->user->id));
		// print_r($cek_winning_ultimate_car);exit;
		$this->assign('cek_winning_ultimate_car',$cek_winning_ultimate_car);
		$this->assign('popup_notif',false); 
		// echo $total;exit;
		if($total == 0 ) $this->assign('popup_notif',true); 
		return $this->contentString("/mygarage.html",true);
		
	}
	
	function saveCar(){
		
		$this->log('customize_car', $this->Request->getPost('items'));	
		$this->CAR->saveCarData();
		
	}
	
	function ultimateGarage($id,$level,$token){
		
		$ultimateCar = json_decode($this->API->get_ultimate_car($id,$level));
		
		$pl['id'] = $ultimateCar->id; 
		$pl['name'] = $ultimateCar->name; 
		$pl['level'] = $ultimateCar->level;
		$pl['title'] = '';
		$pl['points'] = 0;
		$pl['races'] = '???';
		$pl['wins'] = '???';
		$pl['small_img'] = $ultimateCar->small_img;
		$pl['attributes']['speed'] = 0;
		$pl['attributes']['handling'] = 0;
		$pl['attributes']['acceleration'] = 0;
		
		// print_r('<pre>');print_r($player);exit;
		$this->assign('rtoken',$token);
		$this->assign('player',$pl);
		$this->assign('ultimate',true);
		// $carData = json_decode($this->CAR->getCarData($ultimateCar->id));
		  // print_r('<pre>');print_r($carData);exit;
		//$carData->data->body->color='ultimate';
		// $identity = $carData->identity;
		// $data = $carData->data;
		
		foreach($identity as $key => $value){
		
		$this->assign($key,$value);
		}
		$this->assign('body',array('type' => '','color' => 'ultimate'));
		
		// foreach($data as $key => $value){
		// $this->assign($key,array('type' => $value->type,'color' => $value->color));
		// }
		$this->log('garage',$this->user->id);
		return $this->contentString("/garages.html",true);
		// print_r($ultimateCar);exit;
	}

	function purchasePart(){
	$message ='';
	if($this->Request->getPost('part_id')){
		$player = $this->user;
		$playerdata = $this->API->getPlayerData($player->id);
		$playerdata = json_decode($playerdata);
		$part_id = $this->Request->getPost('part_id');
		// print_r($playerdata->id); exit;
		$purchasePart = json_decode($this->API->purchase_part($playerdata->id,$part_id));
		// print_r($purchasePart->result); exit;
		if($purchasePart->result==1){
		$this->log('purchase_parts',$part_id);
		$add_level_player =  json_decode($this->API->add_level_player($player->id,$part_id));
		$message .= $add_level_player->message.' <br>';
		}
	}
	$message .= $purchasePart->message;
	echo $message;
	if($add_level_player) echo '<script>window.location="?page=garage"</script>';
	die();

	}
	
	/**
	 * @todo
	 * pasang halaman error khusus garage disini.
	 */
	function error_page(){
		//do something here
	}
	

}

?>
