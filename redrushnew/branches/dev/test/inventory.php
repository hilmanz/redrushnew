<?php
include_once "../config/config.inc.php";
$conn = mysql_connect($CONFIG['DATABASE'][0]['HOST'],$CONFIG['DATABASE'][0]['USERNAME'],$CONFIG['DATABASE'][0]['PASSWORD']);
if($_POST['form_id']==337687){
	$user_id = $_POST['user_id'];
	$sql = "DELETE FROM redrush_game.racing_user_inventory WHERE user_id=".$user_id."";
	if(mysql_query($sql,$conn)){
		$purchase_date = date("Y-m-d H:i:s");
		$purchase_date_ts = time();
		foreach($_POST['parts'] as $parts){
			$sql = "INSERT INTO redrush_game.racing_user_inventory 
					(user_id, parts_id, n_status, purchase_date, purchase_date_ts)
					VALUES
					(".$user_id.", ".$parts.", 1, '".$purchase_date."', ".$purchase_date_ts.")";
			$q = mysql_query($sql,$conn);
		}
		if($q){
			$msg = "Update Success";
		}else{
			$msg = "Update Failed";
		}
	}else{
		$msg = "Update Failed";
	}
}else if($_POST['update']==1){
	$id = intval($_POST['id']);
	$ls = intval($_POST['ls']);
	$ss = intval($_POST['ss']);
	$lc = intval($_POST['lc']);
	$sc = intval($_POST['sc']);
	$sql = "UPDATE redrush_game.racing_parts_inventory SET ls=".$ls.",ss=".$ss.",lc=".$lc.",sc=".$sc."
			WHERE id=".$id."";
	$q = mysql_query($sql,$conn);
	
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
$sql = "SELECT * FROM redrush_game.racing_parts_inventory ORDER BY id ASC";
$q = mysql_query($sql,$conn);
$parts = array();
while($f=mysql_fetch_assoc($q)){
	$parts[] = $f;
}
mysql_free_result($q);


if($_GET['user_id']){
	//user inventory
	$sql = "SELECT * FROM redrush_game.racing_user_inventory WHERE user_id=".$_GET['user_id']."";
	$q = mysql_query($sql,$conn);
	$inventory = array();
	while($f=mysql_fetch_assoc($q)){
		$inventory[] = $f;
	}
	mysql_free_result($q);
}

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
			<h2>Inventory Tool</h2>
			<p>use this tool for reconfiguring player's parts inventory</p>
		</div>						
			<ul >
			
					<li id="li_1" >
		<label class="description" for="element_1">Player </label>
	
		<div>
			<select name="user_id" id="user_id" onchange="refresh_parts();return false;">
			<option value="">Select a Player</option>
			<?php foreach($users as $user):?>
				<option value="<?=$user['id']?>" <?php if($_GET['user_id']==$user['id']):?>selected<?php endif;?>><?=$user['name']?></option>
			<?php endforeach;?>
			</select> 
		</div> 
		<script>
		function refresh_parts(){
			document.location="inventory.php?user_id="+document.getElementById('user_id').value;
		}
		</script>
		</li>		
		
		<li id="li_2" >
		<label class="description" for="element_2">Parts </label>
		<table width="100%" border="1" cellspacing="0">
		<tr style="font-weight:bold;"><td>Select</td><td>Part</td><td>Level</td><td>LS</td><td>SS</td><td>LC</td><td>SC</td></tr>
		
		<?php foreach($parts as $part):?>
		<?php 
			$part['is_used'] = 0;
			if(is_array($inventory)){
				foreach($inventory as $inv){
					if($part['id']==$inv['parts_id']){
						$part['is_used'] = 1;
						break;
					}
				}
			}
		?>
		<tr>
		
		<td>
			<input id="element_2" name="parts[]" class="element text medium" type="checkbox" maxlength="255" value="<?=$part['id']?>" <?php if($part['is_used']==1):?>checked<?php endif;?>/> 
		</td>
		<td><?=$part['name']?></td>
		<td><?=$part['level']?></td>
		<td><?=$part['ls']?></td>
		<td><?=$part['ss']?></td>
		<td><?=$part['lc']?></td>
		<td><?=$part['sc']?></td>
		
		
		</td>
		
		</tr>
		<?php endforeach;?>
		</table>
		</li>
			<li class="buttons">
			    <input type="hidden" name="form_id" value="337687" />
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>	
		<label class="description" for="element_2">Parts </label>
		<table width="100%" border="1" cellspacing="0">
		<tr style="font-weight:bold;"><td>Part</td><td>Level</td><td>LS</td><td>SS</td><td>LC</td><td>SC</td><td>Edit</td></tr>
		<?php foreach($parts as $part):?>
		<tr>
		<form action="inventory.php" method="POST">
		<td><?=$part['name']?></td>
		<td><?=$part['level']?></td>
		<td><input type="text" name="ls" value="<?=$part['ls']?>" size="3"/></td>
		<td><input type="text" name="ss" value="<?=$part['ss']?>" size="3"/></td>
		<td><input type="text" name="lc" value="<?=$part['lc']?>" size="3"/></td>
		<td><input type="text" name="sc" value="<?=$part['sc']?>" size="3"/></td>
		<td><input type="hidden" name="id" value="<?=$part['id']?>"/>
		<input type="hidden" name="update" value="1"/>
		<input type="submit" name="btn" value="Update"/>
		</td>
		</form> 
		</tr>
		<?php endforeach;?>
		</table>
	</div>
	<img id="bottom" src="bottom.png" alt="">
	</body>
</html>