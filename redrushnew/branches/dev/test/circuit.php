<?php
include_once "../config/config.inc.php";
$conn = mysql_connect($CONFIG['DATABASE'][0]['HOST'],$CONFIG['DATABASE'][0]['USERNAME'],$CONFIG['DATABASE'][0]['PASSWORD']);
if($_POST['form_id']==337687){
	$name = mysql_escape_string($_POST['name']);
	$brief = mysql_escape_string($_POST['brief']);
	$track_config = explode(",",mysql_escape_string($_POST['config']));
	foreach($track_config as $n=>$v){
	    $track_config[$n] = trim($v);
	}
	$sql = "INSERT INTO redrush_game.racing_circuit(name,track_config,brief,laps)
			VALUES('".$name."','".serialize($track_config)."','".$brief."',1)";
	$q = mysql_query($sql,$conn);
	if($q){
		$msg = "The circuit ".$name." is successfully created";
	}else{
		$msg = "Failed";
	}
}else if($_POST['update']==1){
	$_GET['edit']=0;
	$id = intval($_POST['id']);
	$name = mysql_escape_string($_POST['name']);
	$brief = mysql_escape_string($_POST['brief']);
	$track_config = mysql_escape_string(serialize(explode(",",mysql_escape_string($_POST['config']))));
	$sql = "UPDATE redrush_game.racing_circuit SET name='".$name."',brief='".$brief."',track_config='".$track_config."'
			WHERE id=".$id."";
	$q = mysql_query($sql,$conn);
	if($q){
		$msg = "The circuit ".$name." is updated successfully !";
	}else{
		$msg = "Failed";
	}
}else if($_REQUEST['edit']==1&&$_POST['update']!=1){
	$sql = "SELECT * FROM redrush_game.racing_circuit WHERE id=".intval($_REQUEST['id'])." LIMIT 1";
	$q = mysql_query($sql,$conn);
	$rs=mysql_fetch_assoc($q);
	mysql_free_result($q);
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
<title>Circuit Tool</title>
<link rel="stylesheet" type="text/css" href="view.css" media="all">
<script type="text/javascript" src="view.js"></script>

</head>
<body id="main_body" >
	
	<img id="top" src="top.png" alt="">
	<?php if($msg):?>
	<div style="border:1px solid #cc0000"><?php print $msg;?></div>
	<?php endif;?>
	<?php if($_GET['edit']==1):?>
	<div id="form_container">
	
		<h1><a></a></h1>
		<form id="form_337687" class="appnitro"  method="post" action="">
					<div class="form_description">
			<h2>Circuit Tool</h2>
			<p>use this tool to create / modify circuit</p>
		</div>	
		
		<h4>Edit Circuit</h4>				
			<ul >
			
					<li id="li_1" >
		<label class="description" for="element_1">Circuit Name</label>
		<div>
			<input type="text" name="name" value="<?=$rs['name']?>"/>
		</div> 
		</li>		
		<li id="li_2" >
		<label class="description" for="element_1">Brief</label>
		<div>
			<textarea name="brief" cols="50" rows="10"><?=stripslashes($rs['brief'])?></textarea>
		</div> 
		</li>
		<li id="li_2" >
		<label class="description" for="element_1">Tracks</label>
		<div>
			<textarea name="config" cols="50" rows="2"><?=implode(',',unserialize($rs['track_config']))?></textarea> *)separated by comma
		</div> 
		</li>
			<li class="buttons">
			    <input type="hidden" name="update" value="1"/>
			     <input type="hidden" name="id" value="<?=$rs['id']?>"/>
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Update"/>
			</li>
			</ul>
		</form>	
		

	</div>
	<?php else:?>
	<div id="form_container">
	
		<h1><a></a></h1>
		<form id="form_337687" class="appnitro"  method="post" action="">
					<div class="form_description">
			<h2>Circuit Tool</h2>
			<p>use this tool to create / modify circuit</p>
		</div>	
		<table width="100%" border="1">
		<tr style="font-weight:bold">
		<td>Name</td><td>configurations</td><td>Edit</td>
		</tr>
		
		<?php if(is_array($circuits)):foreach($circuits as $circuit):?>
		<tr>
		<td><strong><?=$circuit['name']?></strong><br/><?=$circuit['brief']?></td><td><?=implode(', ',unserialize($circuit['track_config']))?></td><td><a href="?edit=1&id=<?=$circuit['id']?>">Edit</a></td>
		</tr>
		<?php endforeach;endif;?>
	</table>	
		<h4>Create Circuit</h4>				
			<ul >
			
					<li id="li_1" >
		<label class="description" for="element_1">Circuit Name</label>
		<div>
			<input type="text" name="name" value=""/>
		</div> 
		</li>		
		<li id="li_2" >
		<label class="description" for="element_1">Brief</label>
		<div>
			<textarea name="brief" cols="50" rows="10"></textarea>
		</div> 
		</li>
		<li id="li_2" >
		<label class="description" for="element_1">Tracks</label>
		<div>
			<textarea name="config" cols="50" rows="2"></textarea> *)separated by comma
		</div> 
		</li>
			<li class="buttons">
			    <input type="hidden" name="form_id" value="337687" />
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Create"/>
			</li>
			</ul>
		</form>	
		

	</div>
	<?php endif;?>
	<img id="bottom" src="bottom.png" alt="">
	
	</body>
</html>
