<?php
// include_once "../../config/config.inc.php";
// include_once "../../engines/functions.php";

class dailyBrandPreference{

function generateBrand(){
// GLOBAL $CONFIG;
$host = 	'localhost';
$user = 	'marlboro_redrush';
$password = 'samrlbrxtr';
$db = 		'marlboro_redrush_2012';
$dbReport = 'marlboro_redrush_report_2012';

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

	$sql = "SELECT Brand1_ID, Brand2_ID, Brand3_ID,id,birthday FROM ".$db.".kana_member WHERE n_status=1";
	$arrAge = array(18,22,29);
	$brand1_18=0; $brand1_22=0;$brand1_29=0;
	$brand2_18=0; $brand2_22=0;$brand2_29=0;
	$brand3_18=0; $brand3_22=0;$brand3_29=0;
	$query =mysql_query($sql);
	while ($data = mysql_fetch_object($query )){
	// print_r($data->Brand1_ID);
	//convert bod ke age
	$age = $this->birthday($data->birthday);
	//buat range age 18 22 29'	
		if($age>=$arrAge[0] && $age<=($arrAge[1]-1))
		{
			if($data->Brand1_ID!='') $brand1_18++;
			if($data->Brand2_ID!='') $brand2_18++;
			if($data->Brand3_ID!='') $brand3_18++;
		}
		if($age>=$arrAge[1] && $age<=($arrAge[2]-1)){
			if($data->Brand1_ID!='') $brand1_22++;
			if($data->Brand2_ID!='') $brand2_22++;
			if($data->Brand3_ID!='') $brand3_22++;
		}
		if($age>=$arrAge[2]) {
			if($data->Brand1_ID!='') $brand1_29++;
			if($data->Brand2_ID!='') $brand2_29++;
			if($data->Brand3_ID!='') $brand3_29++;
		}	
	
	}
	$result=array(
	'18'=>array('brand1'=>$brand1_18,'brand2'=>$brand2_18,'brand3'=>$brand3_18),
	'22'=>array('brand1'=>$brand1_22,'brand2'=>$brand2_22,'brand3'=>$brand3_22),
	'29'=>array('brand1'=>$brand1_29,'brand2'=>$brand2_29,'brand3'=>$brand3_29)
	);
	
	print_r('<pre>');
	print_r($result);
	
	//truncate, import ke DB
	$sql = 'TRUNCATE TABLE '.$dbReport.'.rp_overall_brand_preference';
	mysql_query($sql);
	//insert data
	$sql = null;
	foreach($result as $key => $value){
	$sql = '
	INSERT IGNORE INTO '.$dbReport.'.rp_overall_brand_preference (age 	,Brand1_ID 	,Brand2_ID 	,Brand3_ID)
	VALUES
	('.$key.','.$value['brand1'].','.$value['brand2'].','.$value['brand3'].')
	';
	$inserted[] = mysql_query($sql);
	}
	print_r($inserted);
	exit;
	}
}

  function birthday ($birthday){
    list($year,$month,$day) = explode("-",$birthday);
    $year_diff  = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff   = date("d") - $day;
    if ($day_diff < 0 || $month_diff < 0)
      $year_diff--;
    return $year_diff;
  }


}


$class = new dailyBrandPreference;
$class->generateBrand();
die();

?>
