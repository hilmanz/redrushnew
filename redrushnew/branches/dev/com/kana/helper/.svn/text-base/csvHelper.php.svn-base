<?php
	/* Babar 11/01/12 */
	class csvHelper{
		function setName($name=''){
			if($name!=''){
				$filename = "report_".$name."_".date("YmdHis").".csv";
			}
			else{
				$filename = "report_".date("YmdHis").".csv";
			}
			return $filename;
		}
		
		function getCSV($filename,$str){
			header("Content-type: application/force-download");
			header("Content-Disposition: attachment; filename=\"".$filename."\"");
			header("Content-Length: ".strlen($str));
			print $str;
			die();
		}
	}
?>