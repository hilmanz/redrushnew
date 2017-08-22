<?php

class ModelDownload extends SQLData{
	function __construct($req){
		
	}
	
	function getContentDownloadnull($start,$total_per_page){
		$sql= "SELECT * FROM tbl_download LIMIT 6";
		$this->open(0);
		$rs=$this->fetch($sql,1);
		$this->close();
		
		$num = count($rs);
		for( $i=0; $i < $num; $i++){
			$filename = 'contents/download/' . $rs[$i]['file'];
			if( is_file( $filename) ){
				$size = ceil( filesize($filename) / 1024 );
				$rs[$i]['size'] = $size . 'kb';
			}else{
				$rs[$i]['size'] = '0kb';
			}
		}
		return $rs;
	}

	function getContentDownload($id,$start,$total_per_page){
		$sql= "SELECT * FROM tbl_download WHERE category_id='".$id."' LIMIT $start,$total_per_page";
		$this->open(0);
		$rs=$this->fetch($sql,1);
		$this->close();
		
		$num = count($rs);
		for( $i=0; $i < $num; $i++){
			$filename = 'contents/download/' . $rs[$i]['file'];
			if( is_file( $filename) ){
				$size = ceil( filesize($filename) / 1024 );
				$rs[$i]['size'] = $size . 'kb';
			}else{
				$rs[$i]['size'] = '0kb';
			}
		}
		return $rs;
	}
	
	function selectcountdelallpic($id){
		$sql="
		SELECT count(*)AS jum 
		FROM tbl_download 
		WHERE category_id='".$id."'";
		$this->open(0);
		$rs= $this->fetch($sql);
		
		$this->close();
		return $rs;
	}
	
	
	
	function getlistcategorydownload($start,$total_per_page){
		$sql="SELECT * 
		FROM tbl_download_category LIMIT $start,$total_per_page";
		$this->open(0);
		$rs=$this->fetch($sql,1);
		
		$this->close();
		return $rs;
	}
	
	
	function getlistcategory(){
		$sql="SELECT * FROM tbl_download_category";
		$this->open(0);
		$rs=$this->fetch($sql,1);
		
		$this->close();
		return $rs;		
	}
	
	function doinsertcategory($nama){
		$sql="INSERT INTO 
		tbl_download_category (name) 
		VALUES ('".$nama."')";
		$this->open(0);
		$rs=$this->query($sql);
		
		$this->close();
		return $rs;		
	}
	
	function selectdelallfile($id){
		$sql="
		SELECT * 
		FROM tbl_download 
		WHERE category_id='".$id."'";
		$this->open(0);
		$rs= $this->fetch($sql,1);
		
		$this->close();
		return $rs;
	}
	
	function deleteallfile($id){
		$sql="DELETE FROM tbl_download 
		WHERE category_id='".$id."'";
		$this->open(0);
		$rs= $this->query($sql);
		$this->close();
		return $rs;
	}
	
	
	function deletecategory($id){
		$sql="DELETE FROM tbl_download_category 
		WHERE id='".$id."'";
		$this->open(0);
		$rs=$this->query($sql);
		//
		$this->close();
		return $rs;
	}
	
	function getCategory($id){
		$sql="SELECT * FROM tbl_download_category WHERE id='".$id."' LIMIT 1";
		$this->open(0);
		$rs=$this->fetch($sql);
		//
		$this->close();
		return $rs;
	}
	
	function updatefileblank($id,$namafile){
		$sql="UPDATE tbl_download SET name='".$namafile."' 
		WHERE id='".$id."'";
		$this->open(0);
		$rs=$this->query($sql);
		//
		$this->close();
		return $rs;
	}
	
	function deletedata($id){
		$sql="DELETE FROM tbl_download WHERE id='".$id."'";
		$this->open(0);
		$rs=$this->query($sql);
		//
		$this->close();
		return $rs;
	}
	
	function getdatadownload($id){
		$sql="SELECT * 
		FROM tbl_download 
		WHERE id='".$id."' LIMIT 1";
		$this->open(0);
		$rs=$this->fetch($sql);
		//
		$this->close();
		return $rs;
	}
	
	function Selectbycategory($id){
		$sql="SELECT * FROM tbl_download WHERE category_id='".$id."'";
		$this->open(0);
		$rs=$this->fetch($sql,1);
		//
		$this->close();
		
		$num = count($rs);
		for( $i=0; $i < $num; $i++){
			$filename = 'contents/download/' . $rs[$i]['file'];
			if( is_file( $filename) ){
				$size = ceil( filesize($filename) / 1024 );
				$rs[$i]['size'] = $size . 'kb';
			}else{
				$rs[$i]['size'] = '0kb';
			}
		}
		
		return $rs;
	}
	
	function getCountPage(){
		$sql="SELECT COUNT(*) AS jumData FROM tbl_download";
		$this->open(0);
		$rs=$this->fetch($sql);
		$this->close();
		return $rs;
	}
	
	function getalldownload($offset, $dataPerPage){
		$sql="SELECT * FROM tbl_download LIMIT $offset, $dataPerPage";
		$this->open(0);
		$rs=$this->fetch($sql,1);
		//
		$this->close();
		
		$num = count($rs);
		for( $i=0; $i < $num; $i++){
			$filename = 'contents/download/' . $rs[$i]['file'];
			if( is_file( $filename) ){
				$size = ceil( filesize($filename) / 1024 );
				$rs[$i]['size'] = $size . 'kb';
			}else{
				$rs[$i]['size'] = '0kb';
			}
		}
		return $rs;
	}
	
	function selectdel($id){
		$sql="SELECT * 
		FROM tbl_download 
		WHERE id='".$id."'";
		$this->open(0);
		$rs=$this->fetch($sql);
		
		$this->close();
		return $rs;
	}
	
	function updatepicwithoutimg($id,$namafile,$filedownload){
		$sql="UPDATE tbl_download SET name='".$namafile."',file='".$filedownload."' WHERE id='".$id."'";
		$this->open(0);
		$rs=$this->query($sql);
		
		$this->close();
		return $rs;
	}
	
	function getdatafile($id){
		$sql="SELECT * FROM tbl_download WHERE id='".$id."' LIMIT 1";
		$this->open(0);
		$rs=$this->fetch($sql);
		
		$this->close();
		return $rs;
	}
	
	function updatepicwithoutfiles($id,$namafile,$name){
		$sql="UPDATE tbl_download SET name='".$namafile."',thumb='".$name."' WHERE id='".$id."'";
		$this->open(0);
		$rs=$this->query($sql);
		
		$this->close();
		return $rs;
	}
	
	function updatepic($id,$namafile,$name,$filedownload){
		$sql="UPDATE tbl_download SET name='".$namafile."',thumb='".$name."',file='".$filedownload."' WHERE id='".$id."'";
		$this->open(0);
		$rs=$this->query($sql);
		
		$this->close();
		return $rs;
	}
	
	function doupdatefile($id,$namafile,$hiddenfile,$hiddenimg){
		$sql="UPDATE tbl_download SET name='".$namafile."',file='".$hiddenfile."',thumb='".$hiddenimg."' WHERE id='".$id."'";
		$this->open(0);
		$rs=$this->query($sql);
		
		$this->close();
		return $rs;
	}
	
	function getUpdateFile($id){
		$sql="SELECT * FROM tbl_download where id='".$id."' LIMIT 1";
		$this->open(0);
		$rs= $this->fetch($sql);
		
		$this->close();
		return $rs;
	}
	
	function updateCategory($id,$txtcategory){
		$sql="UPDATE tbl_download_category set name='".$txtcategory."' WHERE id='".$id."'";
		$this->open(0);
		$rs=$this->query($sql);
		
		$this->close();
		return $rs;
	}
	
	function dogetfile($id,$start,$total_per_page){
		$sql="SELECT * 
		FROM tbl_download WHERE category_id='".$id."' LIMIT $start,$total_per_page";
		$this->open(0);
		$rs=$this->fetch($sql,1);
		
		$this->close();
		return $rs;
	}
	
	function dogetidcategory($id){
		$sql="SELECT * FROM tbl_download_category WHERE id='".$id."'";
		$this->open(0);
		$rs=$this->fetch($sql);
		
		$this->close();
		return $rs;
	}
	
	function getspecificFile($id){
		$sql="SELECT * 
		FROM tbl_download 
		WHERE id='".$id."'";
		$this->open(0);
		$rs=$this->fetch($sql);
		
		$this->close();
		return $rs;
	}
	
	function insertnewfile($nama,$namethumbnail,$filedownload,$id_category){
		$sql="INSERT INTO tbl_download 
		(category_id,name,file,thumb) 
		VALUES ('".$id_category."','".$nama."','".$filedownload."','".$namethumbnail."')";
		$this->open(0);
		$rs=$this->query($sql);
		
		$this->close();
		return $rs;
	}
}
?>