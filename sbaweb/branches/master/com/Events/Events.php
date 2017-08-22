<?php
/*
 *	Irvan Fanani
 *	10 Maret 2011
 */
include_once $ENGINE_PATH."Admin/UserManager.php";
//include_once "EventsModel.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
class Events extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	function admin(){
		$req = $this->Request;
		if ($req->getPost('cmd')=='edit'){
			return $this->updateeventadmin();
		}
		elseif( $req->getParam('act') == 'changestats'){
			return $this->changeStatsEvent( $req->getParam('id'), $req->getParam('stats'));
		}elseif ($req->getParam('act')== 'updateevent'){
			return $this->updateEvent($req->getParam('id'));
		}elseif( $req->getParam('act') == 'delete'){
			return $this->deleteEvent( $req->getParam('id'));
		}elseif( $req->getParam('act') == 'add'){
			return $this->addEvent();
		}else{
			return $this->getListEvent($req);
		}
	}
	
	function updateeventadmin(){
		$nama=$this->Request->getPost('nama');
		$date = explode('/',$this->Request->getPost('date'));
		$date = $date[0].'-'.$date[1].'-'.$date[2];
		$datetime= $date .' '. $this->Request->getPost('time') . ':00';
		$attend=$this->Request->getPost('attendance');
		$summary=$this->Request->getPost('sum');
		$desc=$this->Request->getPost('desc');
		$id=$this->Request->getPost('id');
		
		require_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
				$with_img = false;
				$img_err = false;
				if( $_FILES['img']['size'] > 0 ){
					$with_img = true;
					list($width, $height, $type, $attr) = getimagesize( $_FILES['img']['tmp_name'] );
					if( $_FILES['img']['error'] != 0 ){
						$img_err = true;
						return $this->View->showMessage('Gagal, file gambar rusak', "index.php?s=events");
					}
					if( $width < 110 || $height < 120 ){
						$img_err = true;
						return $this->View->showMessage('Gagal, Resolusi gambar terlalu kecil, minimal 110x120', "index.php?s=events");
					}
					if( $type == 1 || $type == 2 || $type == 3 || $type == 6 ){
						if( $type == 1 ){
							$ext = '.gif';
						}else if( $type == 2 ){
							$ext = '.jpg';
						}else if( $type == 3 ){
							$ext = '.png';
						}else if( $type == 6 ){
							$ext = '.bmp';
						}
					}else{
						$img_err = true;
						return $this->View->showMessage('Gagal, Hanya file JPG, GIF, PNG dan BMP yang diperbolehkan', "index.php?s=events");
					}				
				}
	
		if( !$img_err ){
			$sql="UPDATE events SET nama_event='".$nama."',deskripsi='".$desc."',attendants=".$attend.",summary='".$summary."',tanggal_event='".$datetime."' WHERE id=".$id."";
			if ($this->query($sql)==true){
				
				if( $with_img ){
					try{
						 $thumb = PhpThumbFactory::create( $_FILES['img']['tmp_name'] );
					}catch (Exception $e){
						 // handle error here however you'd like
					}
						
					$thumb->adaptiveResize(110, 120);
					
					if(!is_dir("../contents")){
						@mkdir("../contents");
					}
					if(!is_dir("../contents/events")){
						@mkdir("../contents/events");
					}
						
					if( $thumb->save( "../contents/events/". $id . $ext ) ){
						@move_uploaded_file( $_FILES['img']['tmp_name'], "../contents/events/big_". $id . $ext );
						$qry = "UPDATE events SET image_ext='$ext' WHERE id=". $id;
						$this->query($qry);
					}else{
						//jika resize image bermasalah
					}
				}
				
				return $this->View->showMessage("sukses update event","?s=events");
			}else {
				return $this->View->showMessage("Gagal update event","?s=events");
			}
		}
	}
	
	function updateEvent($id){
		$list = $this->fetch("SELECT * FROM events WHERE id='".$id."'");
		
		$d = explode(' ',$list['tanggal_event']);
		$date = explode('-',$d[0]);
		$list['tanggal_event_only'] = $date[0].'/'.$date[1].'/'.$date[2];
		$time = explode(':',$d[1]);
		$list['waktu'] = $time[0].':'.$time[1];  
		
		$this->View->assign("edit",$list);
		return $this->View->toString("Events/admin/update_event.html");
	}
	
	function getListEvent( $req,$total_per_page=10 ){
		$this->open(0);
		$start = $req->getParam("st");
		if($start==NULL){$start = 0;}
		$r = $this->fetch("SELECT * FROM events", 1);
		$total = count( $r );
		$list = $this->fetch("SELECT e.*, IF( e.user_id=0, 'admin', m.name) name, IF( e.n_status=0, 'tidak aktif', 'aktif') stats FROM events e LEFT JOIN social_member m ON m.id=e.user_id ORDER BY posted_date DESC LIMIT $start,$total_per_page;", 1);
		
		$num = count($list);
		for($i=0;$i<$num;$i++){
			$chars = 200;  
			$mytext = substr($list[$i]['deskripsi'],0,$chars);  
			$mytext = substr($mytext,0,strrpos($mytext,' '));
			$list[$i]['deskripsi'] = $mytext;
		}
		
		$this->View->assign("list",$list);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=events"));
		$this->close();
		return $this->View->toString("Events/admin/index.html");
	}
	function changeStatsEvent( $id, $stats ){
		( $stats == 0 ) ? $sts = 1 : $sts = 0;
		$this->open(0);
		if( !$this->query("UPDATE events SET n_status=$sts WHERE id=$id")){
			$this->close();
			return $this->View->showMessage('Gagal', "index.php?s=events");
		}else{
			$this->close();
			return $this->View->showMessage('Berhasil', "index.php?s=events");
		}
		
	}
	function deleteEvent($id){
		$this->open(0);
		if( !$this->query("DELETE FROM events WHERE id=$id")){
			$this->close();
			return $this->View->showMessage('Gagal', "index.php?s=events");
		}else{
			$this->close();
			return $this->View->showMessage('Berhasil', "index.php?s=events");
		}
		
	}
	function addEvent(){
		if( $this->Request->getPost('cmd') == 'add' ){
			
			if( $this->Request->getPost('title') != null ){
				require_once '../../engines/Utility/phpthumb/ThumbLib.inc.php';
				$with_img = false;
				$img_err = false;
				if( $_FILES['img']['size'] > 0 ){
					$with_img = true;
					list($width, $height, $type, $attr) = getimagesize( $_FILES['img']['tmp_name'] );
					if( $_FILES['img']['error'] != 0 ){
						$img_err = true;
						return $this->View->showMessage('Gagal, file gambar rusak', "index.php?s=events");
					}
					if( $width < 110 || $height < 120 ){
						$img_err = true;
						return $this->View->showMessage('Gagal, Resolusi gambar terlalu kecil, minimal 110x120', "index.php?s=events");
					}
					if( $type == 1 || $type == 2 || $type == 3 || $type == 6 ){
						if( $type == 1 ){
							$ext = '.gif';
						}else if( $type == 2 ){
							$ext = '.jpg';
						}else if( $type == 3 ){
							$ext = '.png';
						}else if( $type == 6 ){
							$ext = '.bmp';
						}
					}else{
						$img_err = true;
						return $this->View->showMessage('Gagal, Hanya file JPG, GIF, PNG dan BMP yang diperbolehkan', "index.php?s=events");
					}				
				}
				
				if( !$img_err ){
					$title = $this->Request->getPost('title');
					$sum = htmlspecialchars(stripslashes( $this->Request->getPost('sum')),ENT_QUOTES);
					$desc = $this->fixTinyEditor( $this->Request->getPost('desc') );
					$date = $this->Request->getPost('date') . " " . $this->Request->getPost('time') . ':00'; 
					$post_date = date('Y-m-d H:i:s');
					$att = ( $this->Request->getPost('attend') == '' ) ? 0 : $this->Request->getPost('attend') ;
					$qry = "INSERT INTO events 
									(user_id, tanggal_event, posted_date, nama_event, deskripsi, attendants, summary)
								VALUES
									(0, '$date', '$post_date', '$title', '$desc', '$att', '$sum');";
					if( !$this->query($qry)){
						$err = mysql_error();
						return $this->View->showMessage('Gagal menambahkan event<br />'.$err , "index.php?s=events");
					}else{
						$last_id = mysql_insert_id();
					}
					
					if( $with_img ){
					
						try{
							 $thumb = PhpThumbFactory::create( $_FILES['img']['tmp_name'] );
						}catch (Exception $e){
							 // handle error here however you'd like
						}
						
						$thumb->adaptiveResize(110, 120);
						
						if(!is_dir("../contents")){
							@mkdir("../contents");
						}
						if(!is_dir("../contents/events")){
							@mkdir("../contents/events");
						}
						
						if( $thumb->save( "../contents/events/". $last_id . $ext ) ){
							@move_uploaded_file( $_FILES['img']['tmp_name'], "../contents/events/big_". $last_id . $ext );
							$qry = "UPDATE events SET image_ext='$ext' WHERE id=". $last_id;
							if( !$this->query($qry)){
								$this->close();
							}else{
								$this->close();
							}
						}else{
							//jika resize image bermasalah
						}
						return $this->View->showMessage('Berhasil menambahkan event', "index.php?s=events");
					}else{
						return $this->View->showMessage('Berhasil menambahkan event', "index.php?s=events");
					}
				}
			}
			
		}
		
		return $this->View->toString("Events/admin/add.html");
	}
	
	function fixTinyEditor($content){
		$content = str_replace("\\r\\n","",$content);
		$content = stripslashes( $content );
		$content = str_replace("&lt;", "<", $content);
		$content = str_replace("&gt;", ">", $content);
		return $content;
	}
}
