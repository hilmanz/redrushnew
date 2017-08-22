<?php
/* @author : Babar
3/5/2012 */
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
include_once APP_PATH.APPLICATION."/models/ReportingModel.php";
include_once APP_PATH.APPLICATION."/helper/trashHelper.php";
include_once APP_PATH.APPLICATION."/helper/ReadCSV.php";
class reporting extends SQLData{
	var $model;
	var $trash;
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->model = new ReportingModel();
		$this->trash = new trashHelper();
	}
	function admin(){
		$act = $this->Request->getParam('act');
		if( $act == 'email-compare' ){
			return $this->EmailCompare();
		}elseif( $act == 'gen-email-compare' ){
			return $this->GenerateEmailCompare();
		}elseif( $act == 'qr-to-email' ){
			return $this->GenerateQREmail();
		}elseif( $act == 'upload-chart' ){
			return $this->ChartManagement();
		}else{
			sendRedirect('index.php');
		}
	}
	
	function EmailCompare(){
		$list = $this->model->getWeekList();
		
		$save = $this->Request->getPost('save');
		
		if($save==1){
			// var_dump($_FILES['csv']);exit;
			$week = $this->Request->getPost('week');
			$csv = $_FILES['csv']['name'];
			$type = $_FILES['csv']['type'];
			$tmp = $_FILES['csv']['tmp_name'];
			$arrType = array('text/comma-separated-values' ,'application/csv','text/csv');
			if($week==''){
				$err = 'Please input week!';
			}elseif(!is_numeric($week)){
				$err = 'Week must be numeric value!';
			}elseif(! in_array($type,$arrType)){
				$err = 'Please upload CSV (separated by comma (,)) file!';
			}else{
				// print_r(BASEURL.'report/week_'.$week."_".$csv);exit;
				if(move_uploaded_file($tmp,'/home/marlboro/public_html/report/week_'.$week."_".$csv)){
					$baca = ReadCSV('/home/marlboro/public_html/report/week_'.$week."_".$csv);
					$sukses = 0; $gagal = 0;
					foreach($baca as $b){
						if($this->model->insertTemp($b[0],$week)){
							$sukses = $sukses+1;
						}else{
							$gagal = $gagal +1;
						}
					}
					// print '<pre>';print_r($baca);exit;
					$list = $this->model->getWeekList();
					$week = '';
					$err = 'Import data => Success : '.$sukses.', Failed : '.$gagal;
				}else{
					$err = 'Failed move upload file! Please try again later.';
				}
			}
		}
		$this->View->assign('week',$week);
		$this->View->assign('err',$err);
		$this->View->assign('list',$list);
		return $this->View->toString(APPLICATION."/admin/reporting/email_compare.html");
	}
	
	
	function GenerateEmailCompare(){
		set_time_limit(0);
		$id = $this->Request->getParam('id');
		if($id==''){
			sendRedirect('index.php');
		}else{
			$list = $this->model->generate($id);
			$n = 0;
			foreach($list as $l){
				$data[$n]['email'] = $l['email'];
				
				if($l['level1']!=NULL){
					$data[$n]['level'] = $l['level1'];
				}else{
					if($l['level2']!=NULL){
						$data[$n]['level'] = $l['level2'];
					}else{
						if($l['level3']!=NULL){
							$data[$n]['level'] = $l['level3'];
						}else{
							$data[$n]['level'] = 1;
						}
					}
				}
				
				if($l['newEmail']==NULL && $l['OldEmail']==NULL){$data[$n]['level'] =0;}
				
				$data[$n]['newEmail'] = $l['newEmail'];
				$data[$n]['OldEmail'] = $l['OldEmail'];
				$n++;
			}
			// print '<pre>';
			// print_r($data);exit;
			$file = "week_".$id."_email_comparison.csv";
			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=".$file);
			$content = "EMAIL,LEVEL,NEWUSER,OLDUSER\n";
			foreach($data as $d){
				$content .= $d['email'].",".$d['level'].",".$d['newEmail'].",".$d['OldEmail']."\n";
			}

			echo $content;
			exit;
		
		}
	}
	
	function GenerateQREmail(){
		set_time_limit(0);
		$save = $this->Request->getPost('save');
		
		if($save==1){
			$csv = $_FILES['csv']['name'];
			$type = $_FILES['csv']['type'];
			$tmp = $_FILES['csv']['tmp_name'];
			$arrType = array('text/comma-separated-values' ,'application/csv','text/csv');
			// print_r($_FILES['csv']);exit;
			if(! in_array($type,$arrType)){
				$err = 'Please upload CSV (separated by comma (,)) file!';
			}else{
				$baca = ReadCSV($tmp);
				// print '<pre>';
				// print_r($baca);exit;
				foreach($baca as $b){
				
					$dt = $this->model->QRtoEmail($b[0]);
					 // print_r($dt);
					$data[] = $dt[0];
				}
				// exit;
				$file = "QRtoEmail_".date('dmYHis').".csv";
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=".$file);
				$content = "QRCODE,EMAIL\n";
				foreach($data as $d){
					$content .= $d['QRCode'].",".$d['Email']."\n";
				}

				echo $content;
				exit;
				// sendRedirect('index.php?s=reporting&act=qr-to-email');
			}
		}
	
		$this->View->assign('err',$err);
		return $this->View->toString(APPLICATION."/admin/reporting/qr_to_email.html");
	}
	
	
	function ChartManagement(){
		
		
		$save = $this->Request->getPost('save');
		$err = $this->Request->getParam('stat');
		$weekOnList = $this->Request->getParam('weeklist');
		if($weekOnList==0 || $weekOnList=='')$weekOnList =1;
		$list = $this->model->listChartData($weekOnList);
		if($save==1){
	
			$week = $this->Request->getPost('week');
			$typeChart = $this->Request->getPost('category');
			$img = $_FILES['img']['name'];
			$type = $_FILES['img']['type'];
			$tmp = $_FILES['img']['tmp_name'];
			$ext = explode('/',$type);
			// print_r($_POST);exit;
			$arrType = array('image/jpeg','image/gif','image/png','image/bmp','image/jpg');
			if($week==''){
				$err = 'Please input week!';
			}elseif(!is_numeric($week)){
				$err = 'Week must be numeric value!';
			}elseif(! in_array($type,$arrType)){
				$err = 'Please upload Image Chart file!';
			}else{
			$nuFile = 'week_'.$week."_".$typeChart."_".$img;
			$nuPath = '../public_html/report/chart/';
			// echo $nuFile;exit;
				if(move_uploaded_file($tmp,$nuPath.$nuFile)){
					$data['img']=$nuFile;
					$data['week']=$week;
					$data['type']=$typeChart;
					$inserted = $this->model->insertChartData($data);
					
					if($inserted )$err = 'Success : transfer '.$nuFile.'';
					else $err = 'Success : transfer '.$nuFile.', but cant record to table';
					
				}else{
					$err = 'Failed move upload file! Please try again later.';
				}
				header('location: index.php?s=reporting&act=upload-chart&stat='.$err);
				
			}
		}
		
			foreach($list as $key => $val){
				$list[$key]['type']= $this->checkTypeChart($val['type']);
			}
		$this->View->assign('weeklist',$weekOnList);
		$this->View->assign('week',$week);
		$this->View->assign('err',$err);
		$this->View->assign('list',$list);
		return $this->View->toString(APPLICATION."/admin/reporting/upload_chart.html");
	}
	
	function checkTypeChart($type='registration'){
	
				$data['registration'] = 'Registration Chart';	
				$data['registrationProgress'] = 'Total Registration Progress Chart';
				$data['RegistrationProgressSBA'] = 'Total Registration Progress BY SBA Chart';	
				$data['RegistrationProgressDST'] = 'Total Registration Progress BY DST Chart';
				$data['ProgramWeekProgress'] = 'Program Weekly Progress Chart';
				$data['DSTPerformance'] = 'DST Performance Chart';
				$data['RedrushTruckPerformance'] = 'Redrush Truck Performance Chart';	
				$data['RedrushFlashMOPPerformance'] = 'Redrush FlashMOB Performance Chart';
				$data['RedrushNightPerformance'] = 'Redrush Night Performance Chart';
				$data['AgeDistribution'] = 'AGE Distribution Chart';
			
			return $data[$type];
	}
}