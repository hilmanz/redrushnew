<?php
require_once ('..\Application.php');

/**
 * @author duf
 * @version 1.0
 * @created 09-Mar-2011 4:51:20 PM
 */
class PhotoGalleryHelper extends Application
{
	var $m_PhotoGallery;


	function PhotoGalleryHelper()
	{
	}



	/**
	 * 
	 * @param req
	 */
	function __construct($req)
	{
	}

	/**
	 * returns album owned by  a user. only published album are allowed to shown.
	 * 
	 * @param user_id
	 */
	function getAlbumByUserId($user_id)
	{
		$sql = "SELECT * FROM social_albums WHERE user_id=".$user_id." LIMIT 1";
		$this->open(0);
		$rs = $this->fetch($sql);
		$this->close();
		return $rs;
	}

	/**
	 * returns a photos inside an album owned by a user.
	 * 
	 * @param user_id
	 * @param album_id
	 */
	function getPhotoByAlbum($user_id, $album_id)
	{
		
	}

	/**
	 * return photo detail.
	 * 
	 * @param id    photo id
	 */
	function getPhotoDetail($id)
	{
	}

	/**
	 * 
	 * @param owner_id
	 * @param name
	 */
	function addAlbum($owner_id, $name)
	{
	}

	/**
	 * delete an album specified album id and it's owned by a user.
	 * 
	 * by deleting the album.. means that we are also deleting its photos.
	 * 
	 * @param owner_id
	 * @param album_id
	 */
	function deleteAlbum($owner_id, $album_id)
	{
	}

	/**
	 * add new photo to database
	 * 
	 * @param file
	 * @param caption
	 * @param album_id
	 */
	function addPhoto($file, $caption, $album_id)
	{
	}

	/**
	 * delete a photo, and make sure the photo is owned by the album before delete it.
	 * 
	 * @param id
	 * @param album_id
	 */
	function deletePhoto($id, $album_id)
	{
	}

	/**
	 * 
	 * @param id
	 * @param name
	 * @param file
	 */
	function updatePhoto($id, $name, $file)
	{
	}

	/**
	 * 
	 * @param name
	 * @param owner_id
	 * @param album_id
	 */
	function updateAlbum($name, $owner_id, $album_id)
	{
	}

}
?>