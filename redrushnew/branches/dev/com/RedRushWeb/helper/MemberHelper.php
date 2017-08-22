<?php

class MemberHelper extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";

	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
	}
	function update_login_time($user_id){
		$sql = "UPDATE kana_member SET last_login = NOW()
				WHERE id=".intval($user_id);
		
		$this->open(0);
		$rs = $this->query($sql);
		$this->close();
		return $rs;
	}
	/**
	 * sync mop data with our data
	 * @param object
	 * @return json
	 */
	function sync_mop($profile){
		
		$member = $this->getProfileByMop($profile["UserProfile"]["RegistrationID"]);
		if($member['n_status']==9){return null;}
		if($member['register_id']!=$profile["UserProfile"]["RegistrationID"]){
			
			//insert data
			if($this->insert_data_from_mop($profile)){
				$member = $this->getProfileByMop($profile["UserProfile"]["RegistrationID"]);
			}else{
				
				//print mysql_error();
			}
		}else{
			
			//Data di kana_member selalu update sesuai dengan MOP
			$this->update_data_from_mop($profile);
		}
		return json_encode($member);
	}
	
	function insert_data_from_mop($profile){
		
		
		$pro = $profile["UserProfile"];
		
		$birth = explode(' ',$pro['DateOfBirth']);
		$birthday = explode('/',$birth[0]);
		$birthday = $birthday[2].'-'.$birthday[0].'-'.$birthday[1]; 
		$streetName = is_array($pro['StreetName']) ? '' : $pro['StreetName'];
		$mobilePhone = is_array($pro['MobilePhone']) ? '' : $pro['MobilePhone'];		
		
		$avatar = ( strtolower($pro['Gender']) == 'm' )? 'images/avatar-man.jpg' : 'images/avatar-woman.jpg';
		
		//$pro['AVType'] = 0; //testing 
		$n_status = ($pro['AVType'] == 1 || $pro['AVType'] == 3)? 1 : 0;
		$verified = ($pro['AVType'] == 1 || $pro['AVType'] == 3)? 1 : 0;
		$last_login = '0000-00-00';
		$freeBadge = false;
		if($n_status == 1){
			$last_login = "NOW()";
			$freeBadge = true;
		}
		
		$email = $pro['Email'];
		
		$this->open(0);
		//check dulu apakah email ini sudah ada dan memiliki register id ?
		$sql = "SELECT * FROM kana_member WHERE email='{$email}' LIMIT 1";
		$row  = $this->fetch($sql);
		//print $row['email']." - ".$email."<br/>";
		//var_dump($row);
		
		
		if($row['email']==$email){
			
			//artinya data berasal dari ipad (mobile)
			if(strlen($row['register_id'])<=1&&intval($row['mobile_type'])!=1){
				if( intval($row['n_status']) == 0 && (intval($pro['AVType']) == 1 || intval($pro['AVType']) == 3) ){
					$n_status = 1;
					$verified = 1;
					$last_login = "NOW()";
				}else{
					$n_status=$row['n_status'];
					$verified=$row['verified'];
				}
				$sql = "UPDATE kana_member SET 
				name='".$pro['FirstName']."',
				last_name='".$pro['LastName']."',
				register_id='".$pro['RegistrationID']."',
				username='".$pro['Login']."',
				city='".$pro['CityID']."',
				sex='".$pro['Gender']."',
				birthday='".$birthday."',
				StreetName='".$streetName."',
				MobilePhone='".$mobilePhone."',
				last_login=$last_login,
				n_status='$n_status',
				verified='$verified',
				Brand1_ID='".@$pro['Brand1_ID']."',
				Brand2_ID='".@$pro['Brand2_ID']."',
				Brand3_ID='".@$pro['Brand3_ID']."'
				WHERE
				email='{$email}'";
				$rs = $this->query($sql);
				if(strlen($row['nickname'])==0){
					if(strlen($pro['OtherName'])>0){
						$nickname = $pro['OtherName'];
					}else{
						$nickname = $pro['FirstName'];
					}
					$this->generate_nickname($nickname,$email);
				}
				
			}else{
				
				//-->
				$sql = "INSERT IGNORE INTO 
				kana_member(register_id,name,nickname,email,register_date,username,city,sex,birthday,img,last_name,StreetName,MobilePhone,last_login,n_status,login_count,verified,Brand1_ID,Brand2_ID,Brand3_ID)
				VALUES ('".$pro['RegistrationID']."','".$pro['FirstName']."','','{$email}',NOW(),'".$pro['Login']."','".$pro['CityID']."','".$pro['Gender']."','".$birthday."','$avatar','".$pro['LastName']."','".$streetName."','".$mobilePhone."',$last_login,'$n_status','1','$verified','".@$pro['Brand1_ID']."','".@$pro['Brand2_ID']."','".@$pro['Brand3_ID']."');";
							
				$rs = $this->query($sql);
				if(strlen($pro['OtherName'])>0){
					$nickname = $pro['OtherName'];
				}else{
					$nickname = $pro['FirstName'];
				}
				$this->generate_nickname($nickname,$email);
			}
		}else{
			//-->
				$sql = "INSERT IGNORE INTO 
				kana_member(register_id,name,nickname,email,register_date,username,city,sex,birthday,img,last_name,StreetName,MobilePhone,last_login,n_status,login_count,verified,Brand1_ID,Brand2_ID,Brand3_ID)
				VALUES ('".$pro['RegistrationID']."','".$pro['FirstName']."','{$pro['OtherName']}','{$email}',NOW(),'".$pro['Login']."','".$pro['CityID']."','".$pro['Gender']."','".$birthday."','$avatar','".$pro['LastName']."','".$streetName."','".$mobilePhone."',$last_login,'$n_status','1','$verified','".@$pro['Brand1_ID']."','".@$pro['Brand2_ID']."','".@$pro['Brand3_ID']."');";
				
				$rs = $this->query($sql);
				if(strlen($pro['OtherName'])>0){
					$nickname = $pro['OtherName'];
				}else{
					$nickname = $pro['FirstName'];
				}
				$this->generate_nickname($nickname,$email);
		}
		$this->close();
		
		return $rs;
	}
	function generate_nickname($nickname,$email){
		//auto-generate nickname
		$ok = false;
		$n=1;
		$nn = $nickname;
		do{				
			$sql = "SELECT nickname FROM kana_member WHERE nickname='{$nn}' LIMIT 1";
			$row = $this->fetch($sql);
			if($row['nickname']!=$nn||strlen($row['nickname'])==0){
				$sql = "UPDATE kana_member SET nickname='{$nn}' WHERE email='{$email}'";
				$q = $this->query($sql);
				$ok=true;
				break;	
			}else{
				$nn=$nickname.$n;
				$n++;
			}
			if($n==100){
				//no nickname available
				break;
			}
		}while(!$ok);
			//-->
	}
	function update_data_from_mop($profile){
		$pro = $profile["UserProfile"];
		//var_dump($pro);
		
		$this->open(0);
		$sql = "SELECT name,n_status,last_login,login_count,verified,nickname FROM kana_member WHERE email='".$pro['Email']."' LIMIT 1;";
		$rs = $this->fetch($sql);
		
		
		if(strlen($rs['nickname'])==0){
			if(strlen($pro['OtherName'])>0){
				$nickname = $pro['OtherName'];
			}else{
				$nickname = $rs['name'];
			}
			$this->generate_nickname($nickname,$pro['Email']);
		}
		
		$this->close();
		
		$n_status = intval($rs['n_status']);
		$verified = $rs['verified'];
		$last_login = "'0000-00-00'";
		$login_count = intval($rs['login_count']) + 1;
		
		if( intval($rs['n_status']) == 0 && (intval($pro['AVType']) == 1 || intval($pro['AVType']) == 3) ){
			$n_status = 1;
			$verified = 1;
			$last_login = "NOW()";
			//print "yey";
		}else if($rs['n_status']==1){
			$verified=1;
			$n_status=1;
			$last_login = "NOW()";
			//print "mey";
		}
		//die();
		if(intval($rs['n_status']) == 1){
			$last_login = "NOW()";
		}
		
		$birth = explode(' ',$pro['DateOfBirth']);
		$birthday = explode('/',$birth[0]);
		$birthday = $birthday[2].'-'.$birthday[0].'-'.$birthday[1]; 
		$streetName = is_array($pro['StreetName']) ? '' : $pro['StreetName'];
		$mobilePhone = is_array($pro['MobilePhone']) ? '' : $pro['MobilePhone'];
		
		//test no verified member
		//$n_status = 0;
		
		$sql = "UPDATE kana_member SET 
				name='".$pro['FirstName']."',
				last_name='".$pro['LastName']."',
				register_id='".$pro['RegistrationID']."',
				username='".$pro['Login']."',
				city='".$pro['CityID']."',
				sex='".$pro['Gender']."',
				birthday='".$birthday."',
				StreetName='".$streetName."',
				MobilePhone='".$mobilePhone."',
				last_login=$last_login,
				n_status='$n_status',
				login_count='$login_count',
				verified='$verified',
				Brand1_ID='".@$pro['Brand1_ID']."',
				Brand2_ID='".@$pro['Brand2_ID']."',
				Brand3_ID='".@$pro['Brand3_ID']."'
				WHERE
				email='".$pro['Email']."'";
		
		$this->open(0);
		$rs = $this->query($sql);
		
		//echo $sql.'<hr/>';
		//echo mysql_error();exit;
		
		$this->close();
		return $rs;
	}
	
	function getProfileByMop($register_id){
		$this->open(0);
		$sql = "SELECT * FROM kana_member WHERE register_id='".$register_id."' LIMIT 1";
		$rs = $this->fetch($sql);
		
		$this->close();
		return $rs;
	}
	function getProfileByMopEmail($email){
		$email = mysql_escape_string($email);
		$this->open(0);
		$sql = "SELECT * FROM kana_member WHERE email='".$email."' LIMIT 1";
		$rs = $this->fetch($sql);
		$this->close();
		return $rs;
	}
	
	function birthday($birthday){
		$birth = explode(' ',$birthday);
		list($month,$day,$year) = explode("/",$birth[0]);
		$year_diff  = date("Y") - $year;
		$month_diff = date("m") - $month;
		$day_diff   = date("d") - $day;
		if ($day_diff < 0 || $month_diff < 0)
		  $year_diff--;
		return $year_diff;
	}
}
?>
