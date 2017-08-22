<?php
	/* READ CSV FUNCTION */
	 function ReadCSV($csv){
		$arrResult = array();
		$handle = fopen($csv, "r");
		if( $handle ) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$arrResult[] = $data;
		}
		fclose($handle);
		}
		return $arrResult;
	}
?>