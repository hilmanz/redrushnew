<?php
class ModelBanner extends SQLData{
	function __construct($req){
		
	}
	function getBanner(){
		global $tracker;
		$this->open(0);
		$sql="SELECT * FROM tbl_banner WHERE n_status=1 and position=1";	
		$rs=$this->fetch($sql,1);
		$user_id = urldecode64($_SESSION['usesstoken']);
		
		$num=count($rs);
		for($i=0;$i<$num;$i++){
			$params = $tracker->getTrackerCode(1,$user_id,1,1,$rs[$i]['url']);
			$rs[$i]['redirect_url'] = "redirect.php?t=".$params."&r=".urlencode64($rs[$i]['url']);
		}
		
		$this->close();
		return $rs;
	}
		
	function getBannerHeader(){
		global $tracker;
		$this->open(0);
		$sql="SELECT * FROM tbl_banner WHERE n_status=1 and position=0;";
		$rs=$this->fetch($sql,1);
		$user_id = urldecode64($_SESSION['usesstoken']);
		$num=count($rs);
		for($i=0;$i<$num;$i++){
			$params = $tracker->getTrackerCode(1,$user_id,1,1,$rs[$i]['url']);
			$rs[$i]['redirect_url'] = "redirect.php?t=".$params."&r=".urlencode64($rs[$i]['url']);
		}
		$this->close();
		return $rs;
	}

	
	function updateBannerblank($id,$nama,$status,$url,$rtype='image'){
		$id = intval($id);
		$nama = mysql_escape_string($nama);
		$status = mysql_escape_string($status);
		$rtype = mysql_escape_string($stype);
		$sql= 
		"UPDATE tbl_banner 
		SET name='".$nama."', n_status='".$status."', url='".$url."', type='$rtype'
		WHERE id=".$id;
		$this->open(0);
		$rs= $this->query($sql);
		print mysql_error();
		$this->close();
		return $rs;	
	}
	
	function updateBanner($id,$nama,$status,$name,$url,$position,$rtype='image'){
		$id = intval($id);
		$nama = mysql_escape_string($nama);
		$status = mysql_escape_string($status);
		$name = mysql_escape_string($name);
		$rtype = mysql_escape_string($rtype);
		$sql= 
		"UPDATE tbl_banner 
		SET name='".$nama."', n_status='".$status."', file='".$name."', url='".$url."', position='".$position."', type='$rtype'
		WHERE id=".$id;
		$this->open(0);
		$rs= $this->query($sql);
		$this->close();
		return $rs;	
	}
	
	function getListBanner($start,$total_per_page){
		$sql=
		"SELECT * FROM tbl_banner LIMIT $start,$total_per_page";
		$this->open(0);
		$rs=$this->fetch($sql,1);
		print mysql_error();
		$this->close();
		return $rs;
	}
	
	function insertBanner($judul,$status,$name,$url,$position,$rtype='image'){
		$sql=
		"INSERT INTO tbl_banner (name,file,n_status,url,position,type) VALUES ('".$judul."','".$name."','".$status."','".$url."','".$position."','$rtype')";
		$this->open(0);
		$rs= $this->query($sql);
		print mysql_error();
		$this->close();
		return $rs;				
	}
	
	function selectdelBanner($id,$file=''){
		$sql=
		"SELECT * FROM tbl_banner WHERE id='".$id."' LIMIT 1";
		$this->open(0);
		$rs=$this->fetch($sql);
		print mysql_error();
		$this->close();
		return $rs;	
	}
	
	function deleteBanner($id){
		$sql=
		"DELETE FROM tbl_banner WHERE id='".$id."' ";
		$this->open(0);
		$rs=$this->query($sql);
		print mysql_error();
		$this->close();
		return $rs;	
	}
	
}
?>
