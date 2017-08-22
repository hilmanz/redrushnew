<?php
/*
 *	Irvan Fanani
 *	10 Augustus 2011
 */
global $ENGINE_PATH;
include_once $ENGINE_PATH."Admin/UserManager.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
class challenge extends SQLData{
	var $user;
	function __construct($req=null,$user=null){
		parent::SQLData();
		$this->Request = $req;
		$this->user = $user;
		$this->View = new BasicView();
	}
	
	function main(){
		$req=$this->Request;
		$stage = $req->getParam('stage');
		if($stage != ''){
			return $this->challengeStage($req,$stage);
		}elseif($req->getParam('submit') == '1'){
			return $this->challengeSubmit($req);
		}else{
			return $this->challengeList($req);
		}
	}
	
	function admin(){
		return $this->adminChallenge($req);
	}
	
	function setChallengeData(){
		$this->open(0);
		
		$qry = "SELECT * FROM tbl_challenge_submission WHERE user_id='".$this->user['id']."'";
		$cek = $this->fetch($qry,1);
		$num = count($cek);
		
		if($num<=0){
		
			$code = "111";
			$qry = "INSERT INTO tbl_challenge_submission 
							(user_id,challenge_id,submit_date,approval,approval_date,challenge_code)
							VALUES
							('".$this->user['id']."','1',NOW(),'0',NOW(),'$code');";
			
			$this->query($qry);
		
		}
		$this->close();
	}
	
	function checkBonusInfo(){
		$qrybonus1 = "SELECT * FROM  tbl_challenge_bonus WHERE user_id='".$this->user['id']."' && category='1';";
		$qrybonus2 = "SELECT * FROM  tbl_challenge_bonus WHERE user_id='".$this->user['id']."' && category='2';";
		
		$this->open(0);
		$bonus1 = $this->fetch($qrybonus1);
		$bonus2 = $this->fetch($qrybonus2);
		
		$show1 = $bonus1['show'] == '' ? 1 : intval($bonus1['show']);
		$show2 = $bonus2['show'] == '' ? 1 : intval($bonus2['show']);
		
		if($show1 == 0){
			$q1="UPDATE tbl_challenge_bonus SET `show`='1' WHERE user_id='".$this->user['id']."' && category='1';";
			$this->query($q1);
			//echo $q1;
			//echo mysql_error();
			//exit;
		}
		if($show2 == 0){
			$q1="UPDATE tbl_challenge_bonus SET `show`='1' WHERE user_id='".$this->user['id']."' && category='2';";
			$this->query($q1);
		}
		$this->close();
		
		return array("bonus1" => $show1, "bonus2" => $show2);
	
	}
	
	function challengeList($req){
		if( $this->isLeader() ){
			
			$this->setChallengeData();
			//$check = $this->checkChallengeBonus();
			//$this->View->assign('check',$check);
			
			$qry1 = "SELECT DISTINCT(challenge_id),user_id,approval FROM tbl_challenge_submission WHERE user_id='".$this->user['id']."' AND challenge_id=1;";
			$qry2 = "SELECT DISTINCT(challenge_id),user_id,approval FROM tbl_challenge_submission WHERE user_id='".$this->user['id']."' AND challenge_id=2;";
			$qry3 = "SELECT DISTINCT(challenge_id),user_id,approval FROM tbl_challenge_submission WHERE user_id='".$this->user['id']."' AND challenge_id=3;";
			$qry4 = "SELECT DISTINCT(challenge_id),user_id,approval FROM tbl_challenge_submission WHERE user_id='".$this->user['id']."' AND challenge_id=4;";
			$qry5 = "SELECT DISTINCT(challenge_id),user_id,approval FROM tbl_challenge_submission WHERE user_id='".$this->user['id']."' AND challenge_id=5;";
			$qry6 = "SELECT DISTINCT(challenge_id),user_id,approval FROM tbl_challenge_submission WHERE user_id='".$this->user['id']."' AND challenge_id=6;";
			$qry7 = "SELECT DISTINCT(challenge_id),user_id,approval FROM tbl_challenge_submission WHERE user_id='".$this->user['id']."' AND challenge_id=7;";
			$qry8 = "SELECT DISTINCT(challenge_id),user_id,approval FROM tbl_challenge_submission WHERE user_id='".$this->user['id']."' AND challenge_id=8;";
			
			$this->open(0);
			$rs1 = $this->fetch($qry1);
			$rs2= $this->fetch($qry2);
			$rs3 = $this->fetch($qry3);
			$rs4 = $this->fetch($qry4);
			$rs5 = $this->fetch($qry5);
			$rs6 = $this->fetch($qry6);
			$rs7 = $this->fetch($qry7);
			$rs8 = $this->fetch($qry8);
			
			$this->close();
			
			/*
			echo $qrybonus1;
			print_r($bonus1);
			print_r($bonus2);
			exit;
			*/
			
			$this->View->assign('ch1',$rs1);
			$this->View->assign('ch2',$rs2);
			$this->View->assign('ch3',$rs3);
			$this->View->assign('ch4',$rs4);
			$this->View->assign('ch5',$rs5);
			$this->View->assign('ch6',$rs6);
			$this->View->assign('ch7',$rs7);
			$this->View->assign('ch8',$rs8);
			
			//echo $qry8;exit;
			
			return $this->View->toString("challenge/list.html");
		}else{
			sendRedirect('index.php');
			die();
		}
	}
	
	function challengeStage($req,$stage=''){
		if( $this->isLeader() ){
			$this->View->assign('stage',$stage);
			return $this->View->toString("challenge/stage.html");
		}else{
			sendRedirect('index.php');
			die();
		}
	}
	
	function challengeSubmit($req){
		if( $this->isLeader() ){
			
			//echo "hsadhaskdhsalkhdlas";exit;
							
							$stage = intval($req->getParam('st'));
							$code = $req->getParam('code');
							
							$this->open(0);
							//cek code nya
							$qry = "SELECT * FROM tbl_challenge_code WHERE code='$code' LIMIT 1;";
							//echo $qry;exit;
							$rs = $this->fetch($qry);
							if( intval($rs['id']) > 0 ){
								
								$qry = "SELECT * FROM tbl_challenge_submission WHERE challenge_code='$code' AND user_id='".$this->user['id']."' LIMIT 1;";
								//echo $qry;
								//exit;
								$r = $this->fetch($qry);
								if( intval($r['id']) > 0 ){
									//code salah
									return $this->View->showMessage('Your code is invalid','index.php?challenge=1');
								}else{
									$qry = "INSERT INTO tbl_challenge_submission 
												(user_id,challenge_id,submit_date,approval,approval_date,challenge_code)
												VALUES
												('".$this->user['id']."','".$rs['challenge_id']."',NOW(),'0',NOW(),'$code');";
												
												//echo $qry;exit;
												
									if( $this->query($qry) ){
										
										$qry = "UPDATE tbl_challenge_submission SET approval='1', approval_date=NOW() WHERE challenge_id='".$stage."' AND user_id='".$this->user['id']."'";
										$this->query($qry);
										
										$q="SELECT * FROM tbl_challenge_submission s LEFT JOIN tbl_challenge_code c ON c.challenge_id=s.challenge_id WHERE s.challenge_id='$stage' AND s.user_id='".$this->user['id']."';";
										$r=$this->fetch($q);
										
										$bonus = array("bonus1" => 1, "bonus2" => 1);
										
										if($r['challenge_category'] == 1 || $r['challenge_category'] == 2){
											//echo 'masuk';exit;
											$this->setBonusInfo($r['challenge_category']);
											$bonus = $this->checkBonusInfo();
										}
										$this->View->assign('bonus',$bonus);
										
										return $this->View->showMessage('Submit code success!','index.php?challenge=1#'.$rs['challenge_id']);
									}else{
										return $this->View->showMessage('Submit code failed!','index.php?challenge=1#'.$stage);
									}
								}
								
							}else{
								//code salah
								return $this->View->showMessage('Your code is invalid.','index.php?challenge=1#'.$stage);
							}
							$this->close();
							//return $this->View->toString("challenge/stage.html");
			
		}else{
			sendRedirect('index.php');
			die();
		}
	}
	
	function checkChallengeBonus(){
		$lamp = 0;
		$trade = 0;
		$finish = 0;
		$challenge_total = 8;
		
		$this->open(0);
		$qry1 = "SELECT 
	DISTINCT(s.challenge_id),
	c.challenge_category 
FROM 
	tbl_challenge_submission s
	LEFT JOIN tbl_challenge_code c
	ON s.challenge_code=c.code
WHERE 
	s.approval=1 AND
	c.challenge_category=1 AND
	s.user_id='".$this->user['id']."';";
	$qry2 = "SELECT 
	DISTINCT(s.challenge_id),
	c.challenge_category 
FROM 
	tbl_challenge_submission s
	LEFT JOIN tbl_challenge_code c
	ON s.challenge_code=c.code
WHERE 
	s.approval=1 AND
	c.challenge_category=2 AND
	s.user_id='".$this->user['id']."';";

	$qry3 = "SELECT COUNT(*) total FROM tbl_challenge_submission WHERE user_id='".$this->user['id']."' AND approval='1';";
	
	//echo $qry1.'<br />';
	//echo $qry2.'<br />';
	//echo $qry3.'<br />';
	//exit;
	
		$rs1 = $this->fetch($qry1,1);
		$num1 = count($rs1);
		if( $num1 >= 2){
			$lamp = 1;
		}
		
		$rs2 = $this->fetch($qry2,1);
		$num2 = count($rs2);
		//echo $num2;exit;
		if( $num2 >= 2){
			$trade = 1;
		}
		
		$rs3 = $this->fetch($qry3);
		$total = intval($rs3['total']);
		
		if( $challenge_total <= $total ){
			$finish = 1;
		}
		
		$this->close();
		
		return array('lamp'=>$lamp,'trade'=>$trade,'finish'=>$finish);
	}
	
	function isLeader(){
		$username = $this->user['username'];
		$qry = "SELECT * FROM leader_ba_lookup a LEFT JOIN social_member b ON a.leader_id=b.id WHERE b.username='$username' LIMIT 1;";
		//echo $qry;exit;
		$this->open(0);
		$rs = $this->fetch($qry);
		$this->close();
		//echo $rs['id'];exit;
		if( intval($rs['id']) > 0){
			return true;
		}
	}

	function adminChallenge(){
		$qry = "SELECT 
						c.*,
						m.name 
					FROM 
						tbl_challenge_submission c 
						LEFT JOIN tbl_challenge_submission n 
						ON c.user_id=n.user_id AND c.challenge_id < n.challenge_id 
						LEFT JOIN social_member m
						ON c.user_id=m.id
					WHERE 
						n.challenge_id IS NULL; ";
		$list = $this->fetch($qry,1);
		$this->View->assign('list',$list);
		return $this->View->toString("challenge/admin/list.html");
	}
	
	function setBonusInfo($bonus){
		$this->open(0);
			
		$qry = "SELECT COUNT(*) total FROM tbl_challenge_bonus WHERE category=$bonus && user_id='".$this->user['id']."';";
		$rs=$this->fetch($qry);
		
		if($rs['total'] <= 0){
			
			$qry="SELECT 
							DISTINCT(s.challenge_id),
							c.challenge_category 
						FROM 
							tbl_challenge_submission s
							LEFT JOIN tbl_challenge_code c
							ON s.challenge_code=c.code
						WHERE 
							s.approval=1 AND
							c.challenge_category=$bonus AND
							s.user_id='".$this->user['id']."';";
			$rs = $this->fetch($qry,1);
			/*
			echo 'masuk ';
			echo $qry;
			print_r($rs);
			exit;
			*/
			$num = count($rs);
			
			if($num >= 2){
				//echo 'masuk';exit;
				$qry="INSERT INTO tbl_challenge_bonus (user_id,category) VALUES ('".$this->user['id']."','$bonus');";
				$this->query($qry);
			}
		}
		$this->close();
	}
}
