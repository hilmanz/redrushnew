<?php
	class popupModel extends SQLData{
		function __construct(){ parent::SQLData();	}
		
				
		function getPopup(){
		
		$this->open(0);
		//get popup
		//tbl_pop_up_redrush 
		//	main_message 	sub_message 	startDate 	endDate 	n_status
		$sql="SELECT * FROM tbl_pop_up_redrush WHERE n_status=1 and startDate<=NOW() AND endDate >=NOW() LIMIT 1";
		$popUpData =  $this->fetch($sql);
		$this->close();
		return $popUpData;
		
		}
	
		
		
	}
?>