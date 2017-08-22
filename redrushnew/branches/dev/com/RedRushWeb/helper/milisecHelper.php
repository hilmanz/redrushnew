<?php 

class milisecHelper{

	var $Request;
	
	function __construct($req=null){
	$this->Request = $req;
	
	}
	
	function formatMilliseconds($milliseconds) {
		$seconds = floor($milliseconds / 1000);
		$minutes = floor($seconds / 60);
		$hours = floor($minutes / 60);
		$milliseconds = $milliseconds % 1000;
		$seconds = $seconds % 60;
		$minutes = $minutes % 60;
	
		$format = '%02u:%02u:%02u';
		$time = sprintf($format, $hours, $minutes, $seconds, $milliseconds);
		return rtrim($time, '0');
	}
	
	function formatSeconds($seconds) {
		$minutes = floor($seconds / 60);
		$hours = floor($minutes / 60);
		$milliseconds = $milliseconds % 1000;
		$seconds = $seconds % 60;
		$minutes = $minutes % 60;
	
		$format = '%02u:%02u:%02u';
		$time = sprintf($format, $hours, $minutes, $seconds);
		return rtrim($time, '0');
	}
	
	
	
}	

?>

