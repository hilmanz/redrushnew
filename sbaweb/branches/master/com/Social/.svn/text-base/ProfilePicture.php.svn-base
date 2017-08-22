<?php
class ProfilePicture extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->mainLayout('Social/profile_pic.html');
		
	}
	function run($user,$news){
		if($this->post('upload')=="1"){
			if($this->UploadPhoto($user['id'])){
				$msg = "Foto telah berhasil di-upload !";
				$news_msg = "<a href='users.php?u=".$user['id']."'>".$user['name']."<a/> has changed a profile picture.";
				$news->send($user['id'],$news_msg);
			}else{
				$msg = "Foto tidak berhasil di-upload !";
			}
			$this->assign("msg",$msg);
		}
		$this->assign("user",$user);
	}
	function UploadPhoto($user_id){
		global $ENGINE_PATH;
		include_once $ENGINE_PATH . 'Utility/phpthumb/ThumbLib.inc.php';
		if(eregi(".jpg",$_FILES['file']['name'])){
			$name = md5($_FILES['file']['name'].rand(1000,9999)).".jpg";
			try{
				$thumb = PhpThumbFactory::create( $_FILES['file']['tmp_name'] );
			}catch (Exception $e){
				// handle error here however you'd like
			}
			if(!is_dir("contents")){
				@mkdir("contents");
			}
			if(!is_dir("contents/images")){
				@mkdir("contents/images");
			}
			if(@move_uploaded_file($_FILES['file']['tmp_name'],"contents/images/".$name)){
				//resize the image
				$thumb->adaptiveResize(160, 160);
				$big = $thumb->save( "contents/images/".$name );
				$thumb->adaptiveResize(75, 75);
				$small = $thumb->save( "contents/images/small_".$name );
				$thumb->adaptiveResize(45, 45);
				$tiny = $thumb->save( "contents/images/tiny_".$user_id.".jpg" );
				
				if( $big && $small && $tiny ){
				
					if($this->SaveToDb($user_id,$name,"contents/images/")){
						return true;	
					}else{
						@unlink("contents/images/".$name);
						@unlink("contents/images/small_".$name);
						@unlink("contents/images/tiny_".$user_id.".jpg");
					}
				}else{
					@unlink("contents/images/".$name);
				}
			}
		}
	}
	function SaveToDb($user_id,$img,$path="contents/images/"){
		$sql = "UPDATE social_member SET small_img = '".$path."small_".$img."',img='".$path.$img."' WHERE id=".$user_id;
		$this->open(0);
		$q = $this->query($sql);
		$this->close();
		return $q; 
	}
	
}
?>