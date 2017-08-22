<?php
include_once "../config/config.inc.php";
$conn = mysql_connect($CONFIG['DATABASE'][0]['HOST'],$CONFIG['DATABASE'][0]['USERNAME'],$CONFIG['DATABASE'][0]['PASSWORD']);
if($_POST['name']!=null){
	
	$sql = "INSERT INTO redrush.kana_member 
	(register_id, name, email, register_date)
	VALUES
	('".$_POST['register_id']."','".$_POST['name']."','".$_POST['email']."',NOW())";
	$q = mysql_query($sql,$conn);
	
	if($q){
		$sql = "INSERT IGNORE INTO redrush_game.racing_level(user_id,level)
				VALUES(".$_POST['register_id'].",1)";
		$q = mysql_query($sql,$conn);
		$msg = "a player created successfully !";
	}
	
}else if($_POST['update']==1){
	$sql = "UPDATE redrush_game.racing_level SET level=".$_POST['level']." WHERE user_id=".$_POST['user_id']."";
	$q = mysql_query($sql,$conn);
}
$sql = "SELECT * FROM redrush.kana_member a INNER JOIN redrush_game.racing_level b
 		ON a.register_id = b.user_id";

$players = array();
$q = mysql_query($sql,$conn);
while($f=mysql_fetch_assoc($q)){
	$players[] = $f;
}
mysql_free_result($q);
mysql_close($conn);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Player Tool</title>
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
			<h2>Create Player Tool</h2>
			<p>tool for creating dummy player</p>
		</div>						
			<ul >
			
					<li id="li_1" >
		<label class="description" for="element_1">Name </label>
		<div>
			<input id="element_1" name="name" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_2" >
		<label class="description" for="element_2">Register_id </label>
		<div>
			<input id="element_2" name="register_id" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_3" >
		<label class="description" for="element_3">email </label>
		<div>
			<input id="element_3" name="email" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="337687" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>	
		<table width="100%" border="1">
		<tr style="font-weight:bold">
		<td>Name</td><td>Level</td><td>Edit</td>
		</tr>
		
		<?php if(is_array($players)):foreach($players as $player):?>
		<tr>
		<form action="create_player.php" method="POST">
		<td><strong><?=$player['name']?></strong></td><td><input type="text" name='level' value="<?=$player['level']?>"/></td><td><input type="submit" name="btn" value="Update"/></td>
		<input type="hidden" name="user_id" value="<?=$player['register_id']?>"/>
		<input type="hidden" name="update" value="1"/>
		</form>
		</tr>
		
		<?php endforeach;endif;?>
	</table>	
	</div>
	<img id="bottom" src="bottom.png" alt="">
	</body>
</html>