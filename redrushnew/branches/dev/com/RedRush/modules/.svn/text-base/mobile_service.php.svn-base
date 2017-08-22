<?php


include_once $APP_PATH."RedRush/models/mobile_model.php";
class mobile_service extends API_Module{
	protected $model;
	//default
	protected $api_key = 'redrush_mobile';
	protected $secret_key = 'r3drush_s3cr3t_k3y';	
	
	function init(){
		$this->model = new mobile_model();
	
	}
	
	function check_access_token($method,$access_token){
	
		if(! $access_token)return false;
		$this->model->open(0);
		$access_token_web = $this->model->get_access_token();
		$this->model->close();
		
		$this->secret_key = sha1($access_token_web['secret_key']);
		$this->api_key = $access_token_web['api_key'];
		$web_access_token = sha1($method.$this->api_key.$this->secret_key.(date('YmdH')));
		// print_r( $access_token.'|'.$web_access_token.'|'.$method.'|'.$this->secret_key.'|'.$this->api_key.'|'.date('YmdH') );exit;
		if($access_token==$web_access_token) return true;
		return false;
	}
	
	function get_user_id_from_register_id($internal=false){
	// print_r(  sha1('get_user_id_from_register_id'.$this->api_key.(sha1($this->secret_key)).(date('YmdH'))));exit;
	$register_id = $this->_request('register_id');

	if($internal==false){
		$access_token = $this->_request('access_token');
	if(! $this->check_access_token('get_user_id_from_register_id',$access_token)) return json_encode(array("message","you don't have authorization for this web services"));
	}
	$this->model->open(0);
	$get_user_id_from_register_id = $this->model->get_user_id_from_register_id($register_id);
	$this->model->close();
	if($get_user_id_from_register_id)	return json_encode(array('user_id',$get_user_id_from_register_id));
	return json_encode(array(null));
	}
	
	function get_user_id_from_qr_code($internal=false){
	// print_r(  sha1('get_user_id_from_register_id'.$this->api_key.(sha1($this->secret_key)).(date('YmdH'))));exit;
	$qrcode = $this->_request('qrcode');

	if($internal==false){
		$access_token = $this->_request('access_token');
	if(! $this->check_access_token('get_user_id_from_register_id',$access_token)) return json_encode(array("message","you don't have authorization for this web services"));
	}
	$this->model->open(0);
	$get_user_id_from_qr_code = $this->model->get_user_id_from_qr_code($qrcode);
	$this->model->close();
	if($get_user_id_from_qr_code)	return json_encode(array('user_id',$get_user_id_from_qr_code));
	return json_encode(array(null));
	}
	
	function user_login(){
	// print_r(  sha1('user_login'.$this->api_key.sha1($this->secret_key)));exit;
		$username = $this->_request('username');
		$password = $this->_request('password');
		$access_token = $this->_request('access_token');
		
		if(! $this->check_access_token('user_login',$access_token)) return json_encode(array("message","you don't have authorization for this web services"));
		
		if($username !='' && $password!=''){
		$this->model->open(0);
		$user_login = $this->model->user_login($username,$password,$access_token);
		$this->model->close();
		}
		return json_encode($user_login);
	}
	
	function user_profile(){
		
		$user_id = $this->_request('userid');
		$access_token = $this->_request('access_token');
		if(! $this->check_access_token('user_profile',$access_token)) return json_encode(array("message","you don't have authorization for this web services"));
		
		if($user_id !=''){
		$this->model->open(0);
		$get_profile_player = array( 'profile' => $this->model->get_profile_player($user_id));
		$this->model->close();
		}
		return json_encode($get_profile_player);
	}
	
	function search_profile(){
		// print_r(  sha1('search_profile'.$this->api_key.(sha1($this->secret_key)).(date('YmdH'))));exit;
		$user_id = $this->_request('searchtxt');
		$access_token = $this->_request('access_token');
		if(! $this->check_access_token('search_profile',$access_token)) return json_encode(array("message","you don't have authorization for this web services"));
		
		if($user_id !=''){
		$this->model->open(0);
		$search_profile_player = array( 'profile' => $this->model->search_profile_player($user_id));
		$this->model->close();
		}
		return json_encode($search_profile_player);
// print_r($search_profile_player);
	}
	
	function news_feed(){
			
		$page = $this->_request('page');
		$access_token = $this->_request('access_token');
		// print_r(  sha1('news_feed'.$this->api_key.(sha1($this->secret_key)).(date('YmdH'))));exit;
		if(! $this->check_access_token('news_feed',$access_token)) return json_encode(array("message","you don't have authorization for this web services"));
				
		if($page !=''){
		$this->model->open(0);
		$get_news_feed = $this->model->get_news_feed($page,$access_token);
		$this->model->close();
		}
			return json_encode($get_news_feed);
	}
		
	function car_avatar(){
		// print_r(  sha1('car_avatar'.$this->api_key.sha1($this->secret_key)));exit;
		$userid = $this->_request('userid');
		$access_token = $this->_request('access_token');
		if(! $this->check_access_token('car_avatar',$access_token)) return json_encode(array("message","you don't have authorization for this web services"));
		
		if($userid !=''){
		$this->model->open(0);
		$get_user_car_avatar = $this->model->get_user_car_avatar($userid);
		$this->model->close();
		}
			return json_encode($get_user_car_avatar);
	}
	
	function user_timeline(){
		// print_r(  sha1('user_timeline'.$this->api_key.(sha1($this->secret_key)).(date('YmdH'))));exit;
		$userid = $this->_request('userid');
		$page = $this->_request('page');
		$access_token = $this->_request('access_token');
		if(! $this->check_access_token('user_timeline',$access_token)) return json_encode(array("message","you don't have authorization for this web services"));
		
		if($userid !=''){
		$this->model->open(0);
		$get_user_timeline = $this->model->get_user_timeline($userid,$page,$access_token);
		$this->model->close();
		}
		return json_encode($get_user_timeline);
	}
	
	function suggest_opponent(){
	
			// print_r(  sha1('suggest_opponent'.$this->api_key.sha1($this->secret_key)));exit;
		$userid = $this->_request('userid');
		$access_token = $this->_request('access_token');
		if(! $this->check_access_token('suggest_opponent',$access_token)) return  json_encode(array("message","you don't have authorization for this web services"));
		
		if($userid !=''){
			$this->model->open(0);
			
			$get_suggest_opponent = $this->model->get_suggest_opponent($userid,$access_token);
			
			$player = $this->model->get_player($userid);
			$this->model->close();
			
					
			//ultimate car
			// print_r('<pre>');	print_r($get_suggest_opponent);exit;
			require_once APP_PATH.APPLICATION."/helper/apiHelper.php";
			
			$API = new apiHelper();		
			
			$ultimateCar = json_decode($API->get_ultimate_car($userid,$player['level']));
			if($ultimateCar) {
				// print_r('<pre>');	
				
				$ultimateBattle['userid'] = $ultimateCar->ultimate_id;
				$ultimateBattle['fullname'] = $ultimateCar->name;
				$ultimateBattle['level'] = $ultimateCar->level;
				$ultimateBattle['ultimate'] = true;
				$n=0;
				foreach($get_suggest_opponent['rows'] as $val){
				$get_suggest_opponent['rows'][$n+1] = $val;
				$n++;
			}
			$get_suggest_opponent['rows'][0] = $ultimateBattle;
			// array_push($get_suggest_opponent['rows'],$ultimateBattle );
	
			// print_r($get_suggest_opponent);
			
			// exit;
			}
		
		}
		
		
		
		return json_encode($get_suggest_opponent);
	}
	
		
	function search_opponent(){
	// print_r(  sha1('search_opponent'.$this->api_key.sha1($this->secret_key)));exit;		
		$searchtxt = $this->_request('searchtxt');
		$page = $this->_request('page');
		$userid = $this->_request('userid');
		$access_token = $this->_request('access_token');
		if(! $this->check_access_token('search_opponent',$access_token)) return  json_encode(array("message","you don't have authorization for this web services"));
		
		if($searchtxt !=''){
		$this->model->open(0);
		$search_opponent = $this->model->search_opponent($userid,$searchtxt,$page,$access_token);
		$this->model->close();
		}
		return json_encode($search_opponent);
	}
	
	function get_race_dialog(){
		// print_r(  sha1('get_race_dialog'.$this->api_key.(sha1($this->secret_key)).(date('YmdH'))));exit;
		//better be ketuker antara opponent sama user ID, gw otomatis swap variable nya
		$userid =$this->_request('userid');
		$opponentid =  $this->_request('opponentid');
		$access_token = $this->_request('access_token');
		if($userid==$opponentid) return  json_encode(array("message","you don't have authorization for this web services"));
		if(! $this->check_access_token('get_race_dialog',$access_token)) return  json_encode(array("message","you don't have authorization for this web services"));
		$this->model->open(0);
		$userLevel = $this->model->get_player($userid);
		$opponentLevel = $this->model->get_player($opponentid);
		$this->model->close();
		if($userid !='' && $opponentid!='' ){
		require_once APP_PATH.APPLICATION."/helper/apiHelper.php";
		$API = new apiHelper();		
		
		$csrf_token = sha1(date("YmdHis").rand(0,999));
		$csrf_token_sessid = sha1($csrf_token.$userid);
		$players = array("player1"=>$userid,"player2"=>$opponentid,'ctoken'=>$csrf_token);
		$racing_token = urlencode64(serialize($players));
		$players = null;	
		$racereport = $API->getRaceReport($racing_token);
		$racereport = json_decode($racereport);
		
		if($racereport->caps50>=1) {
				$race_dialog = null;
				$race_dialog['message'] = 'Sorry you have reached your race limit, come again tomorrow to race.';
				$race_dialog['caps50'] = true;
			return json_encode($race_dialog);	
			}
		if($racereport->caps50opponent>=1) {
				$race_dialog = null;
				$race_dialog['message'] = 'Sorry your contender have reached race limit, come again tomorrow to race.';
				$race_dialog['caps50opponent'] = true;
			return json_encode($race_dialog);
			}
		if($racereport->caps10>=1){
				$race_dialog = null;
				$this->model->open(0);
				$opponentName10 = $this->model->get_player($opponentid);
				$this->model->close();
				$race_dialog['message'] = 'You have already challenge '.$opponentName10['nickname'].' 10 times, Try Again tomorrow !';
				$race_dialog['caps10'] = true;
			return json_encode($race_dialog);				
			}
			
		// print_r($racereport);exit;
		$race_dialog['numrows'] = count($racereport->txt);
		foreach($racereport->txt as $key => $value){
			$data[$key]['order'] = intval($key)+1; 
			$data[$key]['content'] = $value; 
			
		}
		foreach($racereport->results  as $key => $value){
			$user1progValue += $value->user1_prog;
			$user2progValue += $value->user2_prog;
		}
		if(! $data) $race_dialog['rows'] = $racereport->txt;
		else {
		$race_dialog['rows'] = $data;
		
			// exit;
		$is_winner = 0;
			if($user1progValue>$user2progValue) $is_winner = 1;
			if($user1progValue==$user2progValue) $is_winner = 2;
		$sessionTokenRace = json_decode($API->addSessionRaceToken($opponentid));
		$winner = $opponentid;
	
		if($is_winner==1) {
		$sessionTokenRace = json_decode($API->addSessionRaceToken($userid));
		$winner = $userid;
		$gotPoint = 5;
		if($userLevel['level'] <= $opponentLevel['level'] ) $gotPoint = 10;
			if($racereport->caps50==0) {
				if($racereport->caps10==0){
				$gotPoint = $gotPoint*2;
				$API->addGameRacePoint($userid,$sessionTokenRace,$gotPoint);
				
				}
			}
		}else{
		$gotPoint = 5;
		if($opponentLevel['level'] <= $userLevel['level'] ) $gotPoint = 10;
			if($racereport->caps50opponent==0) {
				if($racereport->caps10==0){
				$gotPoint = $gotPoint*2;
				$API->addGameRacePoint($opponentid,$sessionTokenRace,$gotPoint);
				
				}
			}
		
		}
		$this->model->open(0);
		$dChamp = $this->model->get_player($winner);
		$this->model->close();
		$race_dialog['winner'] = $dChamp['nickname'].' is the winner';
		
		}
			
		
		}
		
	
		return json_encode($race_dialog);
	}
	
	
	//ultimate API
	function get_race_ultimate_dialog(){
		// print_r(  sha1('get_race_ultimate_dialog'.$this->api_key.(sha1($this->secret_key)).(date('YmdH'))));exit;
		$userid =$this->_request('userid');
		$opponentid =  $this->_request('opponentid');
		$access_token = $this->_request('access_token');
		if($userid==$opponentid) return  json_encode(array("message","you don't have authorization for this web services"));
		if(! $this->check_access_token('get_race_ultimate_dialog',$access_token)) return  json_encode(array("message","you don't have authorization for this web services token"));
		$this->model->open(0);
		$userLevel = $this->model->get_player($userid);
		$opponentLevel = $this->model->get_player($opponentid);
		$this->model->close();
		if($userid !='' && $opponentid!='' ){
		require_once APP_PATH.APPLICATION."/helper/apiHelper.php";
		$API = new apiHelper();		
		
		$csrf_token = sha1(date("YmdHis").rand(0,999));
		$csrf_token_sessid = sha1($csrf_token.$userid);
		$players = array("player1"=>$userid,"player2"=>$opponentid,'ctoken'=>$csrf_token);
		$racing_token = urlencode64(serialize($players));
		$players = null;	
		$racereport = $API->getRaceReport_ultimate($racing_token);
		$racereport = json_decode($racereport);
		
		// print_r($racereport);exit;
		$race_dialog['numrows'] = count($racereport->txt);
		foreach($racereport->txt as $key => $value){
			$data[$key]['order'] = intval($key)+1; 
			$data[$key]['content'] = $value; 
			
		}
		foreach($racereport->results  as $key => $value){
			$user1progValue += $value->user1_prog;
			$user2progValue += $value->user2_prog;
		}
		if(! $data) $race_dialog['rows'] = $racereport->txt;
		else {
		$race_dialog['rows'] = $data;
		
			// exit;
		$is_winner = 0;
			if($user1progValue>$user2progValue) $is_winner = 1;
			if($user1progValue==$user2progValue) $is_winner = 2;
		$sessionTokenRace = json_decode($API->addSessionRaceToken($opponentid));
		$winner = $opponentid;
	
		if($is_winner==1) {
		$sessionTokenRace = json_decode($API->addSessionRaceToken($userid));
		$winner = $userid;
		$gotPoint = 20;
		
		$API->addGameRacePoint($opponentid,$sessionTokenRace,$gotPoint);
			
		}
		
		$this->model->open(0);
		$dChamp = $this->model->get_player($userid);
		$this->model->close();	
		
		if($is_winner==1) $race_dialog['winner'] = $dChamp['nickname'].' is the winner';
		else  $race_dialog['winner'] = 'The Ultimate Car has outraced you in the challenge';
		
		}
			
		
		}
		
	
		return json_encode($race_dialog);
	}
	
	function distribution_point_on_mobile(){
		// print_r(  sha1('distribution_point_on_mobile'.$this->api_key.(sha1($this->secret_key)).(date('YmdH'))));exit;
		$access_token = $this->_request('access_token');
		if(! $this->check_access_token('distribution_point_on_mobile',$access_token)) return  json_encode(array("message","you don't have authorization for this web services"));
		
	
		$this->model->open(0);
		$distribution_point_on_mobile = $this->model->distribution_point_on_mobile();
		$this->model->close();
		// print_r($distribution_point_on_mobile);exit;
		return json_encode($distribution_point_on_mobile);
	}
	
	
	function getRegistrationData(){
	
	$this->model->open(0);
	$add_registration_ipad_data = $this->model->add_registration_ipad_data();
	$this->model->close();
		
	return json_encode($add_registration_ipad_data);
	}


	function getGameData(){

		$this->model->open(0);
		$add_game_ipad_data = $this->model->add_game_ipad_data();
		$this->model->close();
		return json_encode($add_game_ipad_data);
		
	}
	
	
	
	function fixingGameData(){

		$this->model->open(0);
		$add_game_ipad_data_fixing_email_error = $this->model->add_game_ipad_data_fixing_email_error();
		$this->model->close();
		return json_encode($add_game_ipad_data_fixing_email_error);
		
	}
	
	function sync_data(){
	// print_r(  sha1('sync_data'.$this->api_key.sha1($this->secret_key)));exit;		
		// $register_id,$firstname,$lastname,$nickname,$email,$avtype
		$register_id = $this->_request('register_id');
		$firstname = $this->_request('firstname');
		$lastname = $this->_request('lastname');
		$nickname = $this->_request('nickname');
		$email = $this->_request('email');
		$avtype = $this->_request('avtype');
		$access_token = $this->_request('access_token');
		
		if(! $this->check_access_token('sync_data',$access_token)) return  json_encode(array("message","you don't have authorization for this web services"));
		
		if($register_id !=''){
		$this->model->open(0);
		$sync_data = $this->model->sync_data($register_id,$firstname,$lastname,$nickname,$email,$avtype,$access_token);
		$this->model->close();
		}
		return json_encode($sync_data);
	}
	
}
?>
