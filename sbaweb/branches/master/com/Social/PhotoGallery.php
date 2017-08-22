<?php
require_once ('PhotoGalleryHelper.php');

/**
 * @author duf
 * @version 1.0
 * @created 10-Mar-2011 10:54:52 AM
 */
class PhotoGallery extends Application
{

	var $helper;

	function __construct($req)
	{
	}



	function main($user_id,$album_id=NULL)
	{
		$this->helper = new PhotoGalleryHelper($this->Req);
		if($req->getParam('albums')){
			//do something
			return $this->getAlbums($user_id);
		}else{
			//do something
		}
	}
	
	/**
	 * 
	 * @param user_id    show user's album.
	 */
	function getAlbums($user_id){
	
		$album = $this->helper->getAlbumByUserId($user_id);
		$this->View->assign('album',$album);
		return $this->View->assign("Social/gallery/get_album.html");
	}

}
?>