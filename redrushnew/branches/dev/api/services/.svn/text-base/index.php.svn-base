<?php
// error_reporting(E_ALL);
//with no error handler
include_once "../../config/config.inc.php";
include_once "../../engines/functions.php";

// $usesClass = $_REQUEST['service'];
if(isset($_REQUEST['method']))$method = $_REQUEST['method'];
else $method = 'userLogin';

?>
<html>

<body style="margin: 0pt auto; width: 1000px; padding: 10px; font-family: verdana; font-size: 15px;">
<div >
<form action="index.php" method='GET'>
<select name="method" onchange="this.form.submit();">
<option value='userLogin' <?php if($method=='userLogin') echo 'selected' ;?> >Login User</option>
<option value='getProfile' <?php if($method=='getProfile') echo 'selected' ;?>   >Get Profile User</option>
<option value='searchProfile' <?php if($method=='searchProfile') echo 'selected' ;?>   >Search Profile User</option>
<option value='newsFeed' <?php if($method=='newsFeed') echo 'selected' ;?> >News Feeds</option>
<option value='userCarAvatar' <?php if($method=='userCarAvatar') echo 'selected' ; ?>  >User Car Avatar</option>
<option value='userTimeline' <?php if($method=='userTimeline') echo 'selected' ; ?>  >User Timeline</option>
<option value='suggestOpponent' <?php if($method=='suggestOpponent') echo 'selected' ;?>   >Sugest Opponent</option>
<option value='searchOpponent' <?php if($method=='searchOpponent') echo 'selected' ;?>   >Search Opponent</option>
<option value='getRaceDialog' <?php if($method=='getRaceDialog') echo 'selected' ;?>   >Challenge</option>
</select><br><br>
 <?php if($method=='userLogin'){ ?>
	<input type='text' name='access_token' value='<?php echo sha1('user_login'.'redrush_mobile'.sha1('r3drush_s3cr3t_k3y').(date('YmdH'))); ?>'>
	USERNAME : <input type='text' name='username' value='<?php echo $_REQUEST['username']?>'> 
	PASSWORD : <input type='text' name='password' value='<?php echo $_REQUEST['password']?>'> <br>
	
 <?php }   ?>
  <?php if($method=='getProfile'){ ?>
  	<input type='text' name='access_token' value='<?php echo sha1('user_profile'.'redrush_mobile'.sha1('r3drush_s3cr3t_k3y').(date('YmdH'))); ?>'>
	USER ID : <input type='text' name='userid' value='<?php echo $_REQUEST['userid']?>'> <br>
 <?php }   ?>
 <?php if($method=='searchProfile'){ ?>
 	<input type='text' name='access_token' value='<?php echo sha1('search_profile'.'redrush_mobile'.sha1('r3drush_s3cr3t_k3y').(date('YmdH'))); ?>'>
	Search User ID /Email : <input type='text' name='searchtxt' value='<?php echo $_REQUEST['searchtxt']?>'> <br>
 <?php }   ?>
  <?php if($method=='newsFeed'){ ?>
  	<input type='text' name='access_token' value='<?php echo sha1('news_feed'.'redrush_mobile'.sha1('r3drush_s3cr3t_k3y').(date('YmdH'))); ?>'>
	PAGE : <input type='text' name='page' value='<?php echo $_REQUEST['page']?>'> <br>
 <?php }   ?>
  <?php if($method=='userCarAvatar'){ ?>
  	<input type='text' name='access_token' value='<?php echo sha1('car_avatar'.'redrush_mobile'.sha1('r3drush_s3cr3t_k3y').(date('YmdH'))); ?>'>
	USER ID : <input type='text' name='userid' value='<?php echo $_REQUEST['userid']?>'> <br>
 <?php }   ?>
 <?php if($method=='userTimeline'){ ?>
 	<input type='text' name='access_token' value='<?php echo sha1('user_timeline'.'redrush_mobile'.sha1('r3drush_s3cr3t_k3y').(date('YmdH'))); ?>'>
	USER ID : <input type='text' name='userid' value='<?php echo $_GET['userid']?>'> 
	PAGE : <input type='text' name='page' value='<?php echo $_GET['page']?>'> <br>
 <?php }   ?>
  <?php if($method=='suggestOpponent'){ ?>
  	<input type='text' name='access_token' value='<?php echo sha1('suggest_opponent'.'redrush_mobile'.sha1('r3drush_s3cr3t_k3y').(date('YmdH'))); ?>'>
	USER ID : <input type='text' name='userid' value='<?php echo $_REQUEST['userid']?>'>  <br>
 <?php }   ?>
  <?php if($method=='searchOpponent'){ ?>
   	<input type='text' name='access_token' value='<?php echo sha1('search_opponent'.'redrush_mobile'.sha1('r3drush_s3cr3t_k3y').(date('YmdH'))); ?>'>
	SEARCH OPPONENT : <input type='text' name='searchtxt' value='<?php echo $_REQUEST['searchtxt']?>'> 
	USER ID : <input type='text' name='userid' value='<?php echo $_REQUEST['userid']?>'> 
	PAGE : <input type='text' name='page' value='<?php echo $_REQUEST['page']?>'> <br>
 <?php }   ?>
  <?php if($method=='getRaceDialog'){ ?>
   	<input type='text' name='access_token' value='<?php echo sha1('get_race_dialog'.'redrush_mobile'.sha1('r3drush_s3cr3t_k3y').(date('YmdH'))); ?>'>
	USER ID : <input type='text' name='userid' value='<?php echo $_REQUEST['userid']?>'>  
	OPPONENT ID : <input type='text' name='opponentid' value='<?php echo $_REQUEST['opponentid']?>'>  <br>
 <?php }   ?>
   <?php if($method!=''){ ?>
		<br><input type='submit' value='PROCESS'>
 <?php }   ?>
</form>


<?php

// require_once $usesClass.'.php';
require_once 'mobile.php';
$class = new mobile;
$class->$method();

?>

</div>
</body>

</html>