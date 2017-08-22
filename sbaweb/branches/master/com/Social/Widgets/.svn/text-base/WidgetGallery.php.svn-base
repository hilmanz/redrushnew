<?php
global $APP_PATH;
include_once $APP_PATH."Gallery/Gallery.php";
class WidgetGallery extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $_template = "gallery";
	var $html;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
	
		//$this->run();
	}
	function run(){
		//$this->getAlbumGallery();
	}
	function getLatestGallery(){
		$this->_template = "gallery";
		$helper = new Gallery($this->Request);
		$rs = $helper->getLatestPhotos();
		
		$this->View->assign("list",$rs);
		
	}
	function getGallery(){
		$sql = "some sql here";
		$this->open(0);
		//$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	
	function getListGallery($total=999999){

		$this->_template = 'gallery_list';
		$helper = new Gallery($this->Request);
		$rs = $helper->getLatestPhotos($total);
		
		$this->View->assign("list",$rs);
	}
	
	function getGalleryUpdates($total=999999){

		$this->_template = 'gallery_list';
		$helper = new Gallery($this->Request);
		$rs = $helper->getGalleryUpdates($total);
		
		$this->View->assign("list",$rs);
	}
	
	function getEventGallery(){
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
	
	function __toString(){
		return $this->View->toString("Social/widgets/".$this->_template.".html");
	}
	
}
?>
