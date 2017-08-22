<?php
// include_once "../../config/config.inc.php";
// include_once "../../engines/functions.php";

class imageGenerator{

function generateThisImage(){
// GLOBAL $CONFIG;
$host = 	'localhost';
$user = 	'marlboro_redrush';
$password = 'samrlbrxtr';
$db = 		'marlboro_redrush_2012';

$conn = mysql_connect($host,$user,$password);

if($conn){
	print "OK\n";
	
}else{
	print $host.$user.$password.$db;
	print "gagal konek\n";
}

if($conn){
	print 'masuk';
	mysql_select_db($db);

	
	include_once "/home/marlboro/engines/Utility/Thumbnail.php";	
	if(file_exists("/home/marlboro/engines/Utility/Thumbnail.php")) print 'ada file';
	else print 'file ga ada';
	$thumb 	= new Thumbnail();

	$sql = "SELECT user_id,file FROM tbl_temp_image_processing WHERE n_status=0 AND file <> '' LIMIT 1;";
	
	$data = mysql_fetch_object(mysql_query($sql));
	print $data->user_id;
	if(! $data) {echo 'data habis' ;exit;}
	$user_id = $data->user_id;
	$dataImage= $data->file;
	$newfile = $dataImage; // new file name for primary image
	$sourceFolder = '../../public_html/contents/avatar/small/';
	// new file
	
	$sql = "UPDATE tbl_temp_image_processing SET n_status=1 WHERE user_id=".$user_id." and file='".$dataImage."'";
	// print $sql;
	$updateData = mysql_query($sql);
	
	//small
	$thumbfile = $newfile; // new file name for thumbnail image
	$destinationFolder = "../../public_html/contents/avatar/small_avatar/";
	$thumb->createThumbnail($sourceFolder.$newfile,$destinationFolder.$thumbfile,50,50);
	//medium
	$thumbfile = $newfile; // new file name for thumbnail image
	$destinationFolder = "../../public_html/contents/avatar/medium/";
	$thumb->createThumbnail($sourceFolder.$newfile,$destinationFolder.$thumbfile,150,150);
	//big
	$thumbfile = $newfile; // new file name for thumbnail image
	$destinationFolder = "../../public_html/contents/avatar/big/";
	$thumb->createThumbnail($sourceFolder.$newfile,$destinationFolder.$thumbfile,200,200);
	//profile
	$thumbfile = $newfile; // new file name for thumbnail image
	$destinationFolder = "../../public_html/contents/avatar/profile/";
	$thumb->createThumbnail($sourceFolder.$newfile,$destinationFolder.$thumbfile,141,140);
	
	// $sql = "UPDATE tbl_temp_image_processing SET n_status=1 WHERE user_id=".$user_id." LIMIT 1";
	// $data = mysql_query($sql);
	if($updateData) echo 'success
	';
	exit;
	}
}



}


$class = new imageGenerator;
$class->generateThisImage();
die();

?>
