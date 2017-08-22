<?php
require_once ('DownloadPageHelper.php');
/**
 * @author duf
 * @version 1.0
 * @created 10-Mar-2011 3:08:06 PM
 */
class DownloadPage extends Application
{

	var $helper;
	var $m_DownloadPageHelper;
	var $View;
	var $_template='download_list';
	/**
	 * 
	 * @param req
	 */
	function __construct($req)
	{
		$this->helper = new DownloadPageHelper($req);
		$this->View = new BasicView();
	}

	function main()
	{
		if( $req->getParam('id') != null ){
			$this->getDownload( $req->getParam('id') );
		}else{
			$this->getListDownload();
		}
	}
	
	function getListDownload(){
		return '';
	}
	
	function getDownload($id){
		
	}
	
	function __toString(){
		return $this->View->toString("Social/".$this->_template.".html");
	}
	
}
?>