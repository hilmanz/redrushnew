<?php
global $APP_PATH;
include_once $APP_PATH."Download/Download.php";
class WidgetDownload extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $_template = "download";
	var $html;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
	
		//$this->run();
	}
	function run(){
		//$this->getAlbumGallery();
	}
	function listCategory(){
		$this->_template = "download";
		$helper = new Download($this->Request);
		$rs = $helper->listcategory();
		$this->View->assign("list",$rs);
		
	}
	function getDownload(){
		$sql = "some sql here";
		$this->open(0);
		//$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	
	function getListGallery(){

		$this->_template = 'gallery_list';
		$helper = new Gallery($this->Request);
		$rs = $helper->getLatestPhotos();
		
		$this->View->assign("list",$rs);
	}
//function belum terpakai	*.wendy
/*	function getEventGallery(){
		$this->_template = 'gallery_event';
		return '';
	}
	
	function getAlbumGallery(){
		$this->_template = 'gallery_album';
		return '';
	}
	
	function getPhotoGallery(){
		$this->_template = 'gallery_photo';
		return '';
	}
*/	
	function __toString(){
		return $this->View->toString("Social/widgets/".$this->_template.".html");
	}
	
}
?>