<?php
include_once "../engines/functions.php";
include_once "../config/config.inc.php";
function get($url){
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_TIMEOUT,15);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
	//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); 
	
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$response = curl_exec ($ch);
	$info = curl_getinfo($ch);
	curl_close ($ch);
	return $response;
}

$conn = mysql_connect($CONFIG['DATABASE'][0]['HOST'],$CONFIG['DATABASE'][0]['USERNAME'],$CONFIG['DATABASE'][0]['PASSWORD']);
if($_POST['form_id']==337687){
	$racing_msg = "";
	$circuit_id = $_POST['circuit_id'];
	$racing_msg.= "RACING SIMULATION : \n";
	
	$params = array();
	$params['service'] = "racing_service";
	$params['method'] = "get_circuit_detail";
	$params['circuit_id'] = $circuit_id;
	$url = $GAME_API."?".http_build_query($params);
	
	$racing_msg.= $url."\n";
	$response = json_decode(get($url));
	$data = $response->data;
	$racing_msg.="CIRCUIT : \n";
	$racing_msg.=$data->name."\n";
	$track_config = unserialize($data->track_config);
	foreach($track_config as $cfg=>$cfv){
		$racing_msg.=strtoupper($cfv)." - ";
	}
	$racing_msg.="\n-----------------------\n\n";
	
	
	$params = array();
	$params['service'] = "racing_service";
	$params['method'] = "get_session";
	$params['circuit_id'] = $circuit_id;
	$params['racer1'] = $_POST['player1'];
	$params['racer2'] = $_POST['player2'];
	
	
	
	$url = $GAME_API."?".http_build_query($params);
	$response = get($url);
	$obj = json_decode($response);
	$session_id = $obj->data;
	$racing_msg.= $response."\n";
	$racing_msg.= "retrieved session_id : ".$session_id."\n";
	
	$params = array();
	$params['service'] = "racing_service";
	$params['method'] = "race";
	$params['session_id'] = $session_id;
	$params['status'] = 1;
	$racing_msg.= "RACING SIMULATION : \n";
	$url = $GAME_API."?".http_build_query($params);
	$racing_msg.= $url."\n";
	$response = get($url);
	$result = json_decode($response);
	$racing_msg.="\n\n";
	
	foreach($result->data->txt as $feed){
		$racing_msg.= $feed."\n";
	}
	
	
	$racing_msg.= "\n";
}

$sql = "SELECT * FROM redrush.kana_member ORDER BY id ASC";
$q = mysql_query($sql,$conn);
//users
$users = array();
while($f=mysql_fetch_assoc($q)){
	$users[] = $f;
}
mysql_free_result($q);
//parts
$sql = "SELECT * FROM redrush_game.racing_circuit ORDER BY id ASC";
$q = mysql_query($sql,$conn);
$circuits = array();
while($f=mysql_fetch_assoc($q)){
	$circuits[] = $f;
}
mysql_free_result($q);

mysql_close($conn);
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Inventory Tool</title>
<link rel="stylesheet" type="text/css" href="view.css" media="all">
<script type="text/javascript" src="view.js"></script>

</head>
<body id="main_body" >
	
	<img id="top" src="top.png" alt="">
	<?php if($msg):?>
	<div style="border:1px solid #cc0000"><?php print $msg;?></div>
	<?php endif;?>
	<div id="form_container">
	
		<h1><a></a></h1>
		<form id="form_337687" class="appnitro"  method="post" action="">
					<div class="form_description">
			<h2>Racing Simulation</h2>
			<p>use this tool for simulating a race</p>
		</div>						
			<ul >
			
					<li id="li_1" >
		<label class="description" for="element_1">Player 1</label>
		<div>
			<select name="player1">
			<?php foreach($users as $user):?>
				<option value="<?=$user['id']?>"><?=$user['name']?></option>
			<?php endforeach;?>
			</select> 
		VS
		<label class="description" for="element_1">Player 2</label>
		
			<select name="player2">
			<?php foreach($users as $user):?>
				<option value="<?=$user['id']?>"><?=$user['name']?></option>
			<?php endforeach;?>
			</select> 
		</div> 
		</li>		
		<li id="li_2" >
		<label class="description" for="element_2">Circuit </label>
		<select name="circuit_id">
			<?php foreach($circuits as $circuit):?>
				<option value="<?=$circuit['id']?>"><?=$circuit['name']?></option>
			<?php endforeach;?>
			</select> 
		</li>
			<li class="buttons">
			    <input type="hidden" name="form_id" value="337687" />
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Race" />
			</li>
			</ul>
		</form>	
	</div>
	<img id="bottom" src="bottom.png" alt="">
	<textarea id="" cols="100" rows="100"><?=$racing_msg?></textarea>
	</body>
</html>