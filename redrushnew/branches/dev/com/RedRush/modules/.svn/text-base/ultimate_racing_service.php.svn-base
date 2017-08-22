<?php


include_once $APP_PATH."RedRush/models/ultimate_racing_model.php";
class ultimate_racing_service extends API_Module{
	protected $model;
	private $state1;
	private $state2;
	private $state=0;
	private $lead = 0; //current lead
	private $pos1; //position marker for player 1
	private $pos2; //position marker for player 2
	
	private $arr_use_equal = array();
	private $arr_use_crash = array();
	private $arr_use_attempt = array();
	private $arr_use_dominating = array();
	private $arr_use_leading = array();
	private $arr_use_takeover = array();
	
	function init(){
		$this->model = new ultimate_racing_model();
		$this->pos1 = 1;
		$this->pos2 = 1;
	}
	function create_circuit_test(){
		$circuit_name = "Longroad";
		$circuit_config = array('ls','lc','ss','ls','ls','lc','ss','sc','ls','ls','sc','ss','ss');
		$circuit_desc = "good acceleration is a great advantage in these circuit.";
		
		$this->model->open(0);
		$rs = $this->model->add_circuit($circuit_name,$circuit_config,$circuit_desc);
		$this->model->close();
		return "OK\n";
	}
	
	function reset(){
		$this->pos1=1;
		$this->pos2=1;
		$this->lead=1;
	}
	/**
	 * 
	 * @param $winner current leading player
	 * @param $loser current trailing player 
	 * @param $section circuit section
	 * @param $event_type 0-> start , 1-> equal, 2-> accident, 3-> y tried to beat x but x survives, 4-> finish,5->take over, 6->leading
	 */
	function get_template($winner,$loser,$section,$event_type=1){
		switch($event_type){
			case 1:
				if($winner==$loser){
					$txt = $this->template_race_equal($winner,$loser,$section);
				}else{
					$txt = $this->template_race_attempt($winner,$loser,$section);
				}
			break;
			case 2:
				$txt = $this->template_race_take_over($winner,$loser,$section);
			break;
			case 3:
				$txt = $this->template_race_leading($winner,$loser,$section);
			break;
			case 4:
				$txt = $this->template_race_dominate($winner,$loser,$section);
			break;
			case 5:
				$txt = $this->template_race_accident($winner,$loser,$section);
			break;
			case 6:
				$txt = $this->template_race_finish($winner,$loser,$section);
			break;
			default:
				$txt = $this->template_race_begin($winner,$loser,$section);
			break;
		}
		return $txt;
	}
	
	function template_race_begin($winner,$loser,$section){
		include "../config/events_copy.php";
		$txt = $copy_begin[rand(0,sizeof($copy_begin)-1)];
		$copy_begin = null;
		$copy_finish = null;
		$copy_attempt = null;
		$copy_crash = null;
		$copy_dominating = null;
		$copy_leading = null;
		$copy_takeover = null;
		return $txt;
	}
	function template_race_finish($winner,$loser,$section){
		include "../config/events_copy.php";
		$txt = $copy_finish[rand(0,sizeof($copy_finish)-1)];
		$copy_begin = null;
		$copy_finish = null;
		$copy_attempt = null;
		$copy_crash = null;
		$copy_dominating = null;
		$copy_leading = null;
		$copy_takeover = null;
		return $txt;
	}
	function template_race_equal($winner,$loser,$section){
		include "../config/events_copy.php";
		
		
		$queue = rand(0,sizeof($copy_attempt)-1);
		if(sizeof($copy_attempt) == sizeof($this->arr_use_equal) ) $this->arr_use_equal = null;
		if(! in_array($queue,$this->arr_use_equal)) array_push($this->arr_use_equal,$queue);
		else {
		while(in_array($queue,$this->arr_use_equal)){
		$queue = rand(0,sizeof($copy_attempt)-1);
		}
		array_push($this->arr_use_equal,$queue);
		}
		$txt = $copy_attempt[$queue];
		$copy_begin = null;
		$copy_finish = null;
		$copy_attempt = null;
		$copy_crash = null;
		$copy_dominating = null;
		$copy_leading = null;
		$copy_takeover = null;
		return $txt;
	}
	function template_race_accident($winner,$loser,$section){
		include "../config/events_copy.php";
		
		$queue = rand(0,sizeof($copy_crash)-1);
		if(sizeof($copy_crash) == sizeof($this->arr_use_crash) ) $this->arr_use_crash = null;
		if(! in_array($queue,$this->arr_use_crash)) array_push($this->arr_use_crash,$queue);
		else {
		while(in_array($queue,$this->arr_use_crash)){
		$queue = rand(0,sizeof($copy_crash)-1);
		}
		array_push($this->arr_use_crash,$queue);
		}
		$txt = '<span style="color:red" class="accident_'.$loser.'">'.$copy_crash[$queue].'</span>';
		$copy_begin = null;
		$copy_finish = null;
		$copy_attempt = null;
		$copy_crash = null;
		$copy_dominating = null;
		$copy_leading = null;
		$copy_takeover = null;
		return $txt;
	}
	function template_race_attempt($winner,$loser,$section){
		include "../config/events_copy.php";
		
		$queue = rand(0,sizeof($copy_attempt)-1);
		if(sizeof($copy_attempt) == sizeof($this->arr_use_attempt) ) $this->arr_use_attempt = null;
		if(! in_array($queue,$this->arr_use_attempt)) array_push($this->arr_use_attempt,$queue);
		else {
		while(in_array($queue,$this->arr_use_attempt)){
		$queue = rand(0,sizeof($copy_attempt)-1);
		}
		array_push($this->arr_use_attempt,$queue);
		}
		$txt = $copy_attempt[$queue];
		
		$copy_begin = null;
		$copy_finish = null;
		$copy_attempt = null;
		$copy_crash = null;
		$copy_dominating = null;
		$copy_leading = null;
		$copy_takeover = null;
		return $txt;
	}
	function template_race_dominate($winner,$loser,$section){
		include "../config/events_copy.php";
	
		$queue = rand(0,sizeof($copy_dominating)-1);
		if(sizeof($copy_dominating) == sizeof($this->arr_use_dominating) ) $this->arr_use_dominating = null;
		if(! in_array($queue,$this->arr_use_dominating)) array_push($this->arr_use_dominating,$queue);
		else {
		while(in_array($queue,$this->arr_use_dominating)){
		$queue = rand(0,sizeof($copy_dominating)-1);
		}
		array_push($this->arr_use_dominating,$queue);
		}
		$blaze=rand(1,10);
		if($blaze>=7) $txt = '<span style="color:Yellow" class="dominate_'.$winner.'">'.$copy_dominating[$queue].'</span>';
		else  $txt = $copy_dominating[$queue];
		$copy_begin = null;
		$copy_finish = null;
		$copy_attempt = null;
		$copy_crash = null;
		$copy_dominating = null;
		$copy_leading = null;
		$copy_takeover = null;
		return $txt;
	}
	function template_race_leading($winner,$loser,$section){
		include "../config/events_copy.php";

			
		$queue = rand(0,sizeof($copy_leading)-1);
		if(sizeof($copy_leading) == sizeof($this->arr_use_leading) ) $this->arr_use_leading = null;
		if(! in_array($queue,$this->arr_use_leading)) array_push($this->arr_use_leading,$queue);
		else {
		while(in_array($queue,$this->arr_use_leading)){
		$queue = rand(0,sizeof($copy_leading)-1);
		}
		array_push($this->arr_use_leading,$queue);
		}
		$txt = $copy_leading[$queue];
		$copy_begin = null;
		$copy_finish = null;
		$copy_attempt = null;
		$copy_crash = null;
		$copy_dominating = null;
		$copy_leading = null;
		$copy_takeover = null;
		return $txt;
	}
	function template_race_take_over($winner,$loser,$section){
		include "../config/events_copy.php";
		
			
		$queue = rand(0,sizeof($copy_takeover)-1);
		if(sizeof($copy_takeover) == sizeof($this->arr_use_takeover) ) $this->arr_use_takeover = null;
		if(! in_array($queue,$this->arr_use_takeover)) array_push($this->arr_use_takeover,$queue);
		else {
		while(in_array($queue,$this->arr_use_takeover)){
		$queue = rand(0,sizeof($copy_takeover)-1);
		}
		array_push($this->arr_use_takeover,$queue);
		}
		
		$txt = '<span style="color:GreenYellow" class="takeover_'.$winner.'">'.$copy_takeover[$queue].'</span>';
		$copy_begin = null;
		$copy_finish = null;
		$copy_attempt = null;
		$copy_crash = null;
		$copy_dominating = null;
		$copy_leading = null;
		$copy_takeover = null;
		return $txt;
	}
	
	function update_position($p1,$p2){
		if($p1>$p2){
			$this->pos1++;
			$this->pos2--;
		}else if($p2>$p1){
			$this->pos1--;
			$this->pos2++;
		}else{
			//do nothing
		}
	}
	/**
	 * to determined these conditions : 
	 * 1. attempt to take over the position
	 * 2. takeover
	 * 3. maintaining lead
	 * 4. failed to take over
	 */
	function get_position_status(){
		//print abs($this->pos1)."-".abs($this->pos2)." -> ";
		if($this->pos1>$this->pos2){
			if(abs(abs($this->pos1)-abs($this->pos2))>2){
				$this->lead=1;
				return 3;
			}else{
				if($this->lead==2){
					$this->lead=1;
					return 2;
				}else{
					$this->lead=1;
					return 4;
				}
			}
		}
		if($this->pos2>$this->pos1){
			if(abs(abs($this->pos1)-abs($this->pos2))>2){
				$this->lead=2;
				return 3;
			}else{
				if($this->lead==1){
					$this->lead=2;
					return 2;
				}else{
					$this->lead=2;
					return 4;
				}
			}
		}
		if($this->pos1==$this->pos2){
			
			return 1;
		}
	}
	/**
	 * get random circuit
	 */
	function get_circuit(){
		$this->model->open(0);
		$circuits = $this->model->get_circuits();
		$this->model->close();
		$n = sizeof($circuits);
		$t = rand(0,$n-1);
		
		$circuit_id = $circuits[$t]['id'];
		$status = 1;
		$msg = "get circuit";
		
		return $this->toJson($status,$msg,$circuit_id);
	}
	function calculate_result($session_id,$race){
		$results = $this->model->calculate_result($session_id,$race);
		return $results;
	}
	function generate_events($results,$session_id,$race){
		
		$racer1 = $this->model->get_user($race['racer1_id']);
		$racer2 = $this->model->get_ultimate_car_by_id($race['racer2_id']);//get user stat
		$player1 = $racer1['nickname'];
		$player2 = $racer2['name'];
	
		
		$this->reset();
		$final = sizeof($results)-1;
		
		$n_total = sizeof($results);
		
		$track = array("LS"=>"long way","SS"=>"short way","LC"=>"slow corner","SC"=>"fast corner");
		
		$str = "";
		$events_txt = array();
		$leads = array(0,0);
		foreach($results as $n=>$v){

			$p1 = $v['user1_prog'];
			$p2 = $v['user2_prog'];
			$crash1 = 0;
			$crash2 = 0;
			if($v['state']==4){
				if($p1<$p2){
					$crash1 = 1;
				}else{
					$crash2 = 1;
				}
			}
			//$crash1 = $v[2];
			//$crash2 = $v[3];
			
			if($n>0){
				$s1 = 0;
				$s2 = 0;
				if($v[0]>$progress[$n-1][0]){
					$s1=1;
				}
				if($v[1]>$progress[$n-1][1]){
					$s2=1;
				}
				
				$this->update_position($p1,$p2);
				$pos_status = $this->get_position_status();
				$pos_status.=" L:".$this->lead." ";
				
				if($p1>$p2){
					//$this->lead=1;
					$leads[0]++;	
				}else if($p2>$p1){
				//	$this->lead=2;
					$leads[1]++;	
				}else if($p1==$p2){
					$leads[0]++;
					$leads[1]++;
				}
				if($n==$final){
					if($this->lead==1){
						$str=$this->get_template($player1,$player2,$track[$v['track']],6);
					}else{
						$str=$this->get_template($player2,$player1,$track[$v['track']],6);
					}
				}else{
					if($crash1==1||$crash2==1){
						if($crash1==1){
							$str= $this->get_template($player2,$player1,$track[$v['track']],5);
						}else{
							$str= $this->get_template($player1,$player2,$track[$v['track']],5);
						}
					}else{
						if($this->pos1==$this->pos2){
							if($this->lead==1){
								$str= $this->get_template($player2,$player1,$track[$v['track']],$pos_status);
							}else if($this->lead==2){
								$str= $this->get_template($player1,$player2,$track[$v['track']],$pos_status);
							}
						}else{
							if($this->lead==1){
								$str= $this->get_template($player1,$player2,$track[$v['track']],$pos_status);
							}else if($this->lead==2){
								$str= $this->get_template($player2,$player1,$track[$v['track']],$pos_status);
							}else{
								//$str= $p1."v".$p2." ".$pos_status."\n";
								//print "\n";
								//print "xx-".$p1."v".$p2." ".$pos_status." - ".$s1."v".$s2." ".$this->get_template($player1,$player1,'Short Way',$pos_status)."\n";
							}
						}
					}
				}
			}else{
				$str= $this->get_template($player1,$player2,$track[$v['track']],7);
			}
			$events_txt[] = $str;
		}
		if($leads[0]>$leads[1]){
			$winner=$race['racer1_id'];
		}else if($leads[1]>$leads[0]){
			$winner=$race['racer2_id'];
		}else{
			$winner=0;
		}
		//$to_remove = ($n_total - 10);
		//if($to_remove>0){
		//	array_splice($events_txt,4,$to_remove);
	//	}
		return array('winner'=>$winner,'txt'=>$events_txt,'results'=>$results,'progress'=>$leads);
	}
	
	function comparePosition($prog1,$prog2){
		if($prog1>$prog2){
			$this->state2 = 0;
			if($this->state1==0){
				$this->state1=1;
			}else if($this->state1==1){
				$this->state1=2;
			}
			return 2;
		}else if($prog2>$prog1){
			$this->state1 = 0;
			if($this->state2==0){
				$this->state2=1;
			}else if($this->state2==1){
				$this->state2=2;
			}
			return 3;
		}else{
			$this->state1=0;
			$this->state2=0;
			return 1;
		}
	}
	
	function get_circuit_detail(){
		$circuit_id = $this->_request('circuit_id');
		$this->model->open(0);
		$detail = $this->model->get_circuit_detail($circuit_id);
		$this->model->close();
		if($detail){
			return $this->toJson(1,'get_circuit_detail',$detail);
		}else{
			return $this->toJson(0,'get_circuit_detail',$circuit_id);
		}
	}
	
	function flag_session_and_history($session_id){
		$this->model->set_session_status($session_id,1);
	}
	function close_session(){
		$session_id = $this->_request('session_id');
		$status = $this->_request('status');
		$this->model->open(0);
		$rs = $this->model->set_session_status($session_id,$status);
		$this->model->close(0);
		if($rs){
			$status = 1;
			$msg = "Session closed";
		}else{
			$status = 0;
			$msg = "failed";
		}
		return $this->toJson($status,$msg,$status);
	}
	
	function get_session(){
		$racer1 = $this->_request('racer1');
		$racer2 = $this->_request('racer2');
		$circuit_id = $this->_request('circuit_id');
		$this->model->open(0);
		$session_id = $this->model->create_session($racer1,$racer2,$circuit_id);
		$this->model->close();
		if($session_id!=""){
			return $this->toJson(1,'game_session_id',$session_id);	
		}else{
			return $this->toJson(0,'creating session is failed',$session_id);
		}
	}
	function generate_report($events,$session_id,$race){
		return $this->model->generate_report($events,$session_id,$race);
	}
	function get_report(){
		$session_id = $this->_request('session_id');
		$this->model->open(0);
		$report = $this->model->get_report($session_id);
		$this->model->close();
		if(is_array($report)){
			$rs = $this->toJson(1,'get_report',$report);
		}else{
			$rs = $this->toJson(0,'get_report',$report);
		}
		return $rs;
	}
	/**
	 * 
	 */
	function race(){
		$session_id = $this->_request('session_id');
		$this->model->open(0);
		$race = $this->model->get_race_session($session_id);
		//print "circuit --> ".$race['circuit_id']."\n";
		$results = $this->calculate_result($session_id,$race);
		$events = $this->generate_events($results,$session_id,$race);
		//last_score :
		
		//foreach($events['txt'] as $ev){
			//print $ev."\n";
		//}
		$report_id = $this->generate_report($events,$session_id,$race);
		if($report_id>0){
			$this->update_history($report_id,$session_id,$race);
		}
		$this->flag_session_and_history($session_id);
		$this->model->close();
		$data = array("txt"=>$events['txt'],"winner"=>$events['winner'],'raw'=>$events['results'],'prog'=>$events['progress']);
		
		return $this->toJson(1,'race_result',$data);
	}
	function update_history($report_id,$session_id,$race){
		
	}
	function get_game_report($session_id){
		
	}
	function generate_session(){
		$user_id =  $this->_request('user_id');
		$game_id = $this->_request('game_id');
		$gameApiKey =  $this->_request('key');
		$this->model->open(0);
		if($this->model->apikey_valid($game_id,$gameApiKey)){
			$status=1;
			$session_id = $this->model->generate_session($user_id,$game_id,$gameApiKey);
			$msg = "get_session";
		}else{
			$status=0;
			$msg = "Invalid API Key";
		}
		$this->model->close();
		return $this->toJson($status,$msg,$session_id);
	}
	
	function save_score(){

		$user_id = $this->_request('user_id');
		$game_id = $this->_request('game_id');
		$score = $this->_request('score');
		$session_token = $this->_request('session_token');
		
		$this->model->open(0);
		$this->model->save_score($user_id,$game_id,$score,$session_token);
		$this->model->close();
		return true;
	}
	
	
		
}
?>