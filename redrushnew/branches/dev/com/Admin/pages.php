<?php
/*
 *	Irvan Fanani
 *	Desember 2011
 */

class pages extends SQLData{
	var $View;
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
	}
	
	function admin(){
		$act = $this->Request->getParam('act');
		if($act == 'add' ){
			return $this->add();
		}elseif($act == 'delete' ){
			return $this->delete();
		}elseif($act == 'edit' ){
			return $this->edit();
		}else{
			return $this->pagesList();
		}
	}
	
	function pagesList(){
		
		$qry = "SELECT * FROM gm_pages WHERE page_type='static'";
		
		$rs = $this->fetch($qry,1);
		
		$this->View->assign('list', $rs);
		
		return $this->View->toString("common/admin/pages-list.html");
	
	}
	
	function add(){
	
		if(intval($this->Request->getPost('add')) == 1){
			
			$create = $this->createNewPage($this->Request);
			
			if( $create === true ){
			
				return $this->View->showMessage('Create page success','index.php?s=pages');
			
			}else{
			
				$this->View->assign('err',$create);
				$this->View->assign('name',$this->Request->getPost('name'));
				$this->View->assign('request',$this->Request->getPost('request'));
				$this->View->assign('template',$this->Request->getPost('template'));
				$this->View->assign('content',$this->Request->getPost('content'));
				$this->View->assign('content_title',$this->Request->getPost('content_title'));
				$this->View->assign('content_text',$this->Request->getPost('content_text'));
				$this->View->assign('status',$this->Request->getPost('status'));
				$this->View->assign('login',$this->Request->getPost('login'));
			
			}
			
		}
		
		$this->View->assign('widgets', $this->getWidgetList() );
		
		return $this->View->toString("common/admin/pages-add.html");
		
	}
	
	function createNewPage($req){
		
		$err = array();
		
		//Check page name must unique
		if( ! $this->checkPageName($req->getPost('name')) ){
			$err[] = "Page name not available";
		}
		//Check page request must unique
		if( ! $this->checkPageRequest($req->getPost('request')) ){
			$err[] = "Page request not available";
		}
		//Check page template not empty
		if( $req->getPost('template') == '' ){
			$err[] = "Page template is empty";
		}
		
		if( count($err) > 0 ){
			
			return $err;
		
		}else{
			
			$widgets = serialize($_POST['widgets']);
			
			$qry = "INSERT INTO gm_pages
						(page_request,page_name,page_type,page_template,page_status,page_content,page_login,page_widgets)
						VALUES
						('".cleanXSS(strtolower($req->getPost('request')))."',
						 '".cleanXSS($req->getPost('name'))."',
						 'static',
						 '".cleanXSS($req->getPost('template'))."',
						 '".cleanXSS($req->getPost('status'))."',
						 '".cleanXSS($req->getPost('content'))."',
						 '".cleanXSS($req->getPost('login'))."',
						 '".$widgets."')";
						 
			if($this->query($qry)){
				
				$page_id = mysql_insert_id();
				
				//if( $req->getPost('content') == 'yes' ){
					
					$qry = "INSERT INTO gm_page_content
								(page_id,content_title,content_text)
								VALUES
								('".$page_id."','".cleanXSS($req->getPost('content_title'))."','".cleanXSS($req->getPost('content_text'))."')";
								
					if( $this->query($qry) ){
						
					
					}else{
						
						$err[] = "Create page failed, please try again!";
						
						//Delete page data
						$qry = "DELETE FROM gm_pages WHERE page_id='".$page_id."';";
						
						$this->query($qry);
					
					}
					
				//}
				
			}else{
				
				$err[] = "Create page failed, please try again!";
			}
			
			if( count($err) > 0 ){
				return $err;
			}else{
				return true;
			}
		
		}
		
	}
	
	function checkPageName($name,$edit=false){
	
		if($edit === false){
			$qry = "SELECT count(*) total FROM gm_pages WHERE page_name LIKE '".mysql_escape_string($name)."';";
		}else{
			$qry = "SELECT count(*) total FROM gm_pages WHERE page_name <> '".mysql_escape_string($edit)."' AND page_name LIKE '".mysql_escape_string($name)."';";
		}
		
		$rs = $this->fetch($qry);
		
		if(intval($rs['total']) > 0){
			return false;
		}else{
			return true;
		}
		
	}
	
	function checkPageRequest($request,$edit=false){
	
		if($edit === false){
			$qry = "SELECT count(*) total FROM gm_pages WHERE page_request LIKE '".mysql_escape_string($request)."';";
		}else{
			$qry = "SELECT count(*) total FROM gm_pages WHERE page_request <> '".mysql_escape_string($edit)."' AND page_request LIKE '".mysql_escape_string($request)."';";
		}
		
		$rs = $this->fetch($qry);
		
		if(intval($rs['total']) > 0){
			return false;
		}else{
			return true;
		}
		
	}
	
	function delete(){
		
		$id = $_POST['page_id'];
		
		foreach($id as $k){
			
			$qry1 = "DELETE FROM gm_pages WHERE page_id='".intval($k)."' LIMIT 1";
			$qry2 = "DELETE FROM gm_page_content WHERE page_id='".intval($k)."' LIMIT 1";
			
			if( !$this->query($qry1) ){
				
				return $this->View->showMessage('Delete page\'s failed, please try again!','index.php?s=pages');
				
			}else{
				
				 $this->query($qry2);
				
			}
			
		}
		
		return $this->View->showMessage('Delete page\'s success','index.php?s=pages');
		
	}
	
	function edit(){
		
		$qry = "SELECT * FROM gm_pages p LEFT JOIN gm_page_content c ON p.page_id=c.page_id WHERE p.page_type='static' AND p.page_id='".intval($this->Request->getParam('id'))."' LIMIT 1;";
		$rs = $this->fetch($qry);
		$this->View->assign('data',$rs);
		$this->View->assign('id',intval($this->Request->getParam('id')));
	
		if(intval($this->Request->getPost('edit')) == 1){
			
			$edit = $this->editPage(intval($this->Request->getParam('id')),$this->Request,$rs['page_name'],$rs['page_request'],$rs['page_content']);
			
			if( $edit === true ){
			
				return $this->View->showMessage('Edit page success','index.php?s=pages');
			
			}else{
			
				$this->View->assign('err',$edit);
			
			}
			
		}
		
		$this->View->assign('widgets', $this->getWidgetList(unserialize($rs['page_widgets'])) );
		
		return $this->View->toString("common/admin/pages-edit.html");
		
	}
	
	function editPage($id,$req,$name,$request,$content){
		
		$err = array();
		
		//Check page name must unique
		if( ! $this->checkPageName($req->getPost('name'),$name) ){
			$err[] = "Page name not available";
		}
		//Check page request must unique
		if( ! $this->checkPageRequest($req->getPost('request'),$request) ){
			$err[] = "Page request not available";
		}
		//Check page template not empty
		if( $req->getPost('template') == '' ){
			$err[] = "Page template is empty";
		}
		
		if( count($err) > 0 ){
			
			return $err;
		
		}else{
			
			$widgets = serialize($_POST['widgets']);
			
			$qry = "UPDATE gm_pages SET
						page_request='".cleanXSS(strtolower($req->getPost('request')))."',
						page_name='".cleanXSS($req->getPost('name'))."',
						page_template='".cleanXSS($req->getPost('template'))."',
						page_status='".cleanXSS($req->getPost('status'))."',
						page_content='".cleanXSS($req->getPost('content'))."',
						page_login='".cleanXSS($req->getPost('login'))."',
						page_widgets='".$widgets."'
						WHERE
						page_id='".$id."';";
						
			//echo $qry;
						 
			if($this->query($qry)){
				
				//if($content == 'yes'){
					
					$qry = "UPDATE gm_page_content SET
								content_title='".cleanXSS($req->getPost('content_title'))."',
								content_text='".cleanXSS($req->getPost('content_text'))."'
								WHERE
								page_id='".$id."'";
									
						if( $this->query($qry) ){
							
						
						}else{
							
							$err[] = "Edit page failed, please try again!";
						
						}
			
				/*
				}else{
					if( $req->getPost('content') == 'yes' ){
						
						$qry = "INSERT INTO gm_page_content
									(page_id,content_title,content_text)
									VALUES
									('".$id."','".mysql_escape_string($req->getPost('content_title'))."','".mysql_escape_string($req->getPost('content_text'))."')";
									
						if( $this->query($qry) ){
							
						}else{
							
							$err[] = "Edit page failed, please try again! 2";
		
						}
						
					}
				}
				*/
				
			}else{
				$err[] = "Edit page failed, please try again!";
				//echo '<hr />'.mysql_error();
				//exit;
			}
			
			if( count($err) > 0 ){
				return $err;
			}else{
				return true;
			}
		
		}
		
	}
	
	function getWidgetList($widgets=null){
		
		if($widgets == null){
			global $APP_PATH;
			$rs = array();
			if ($handle = opendir($APP_PATH . APPLICATION . '/widgets/')) {
				while (false !== ($entry = readdir($handle))) {
					if( eregi('.widget.php',$entry) ){
						$mod = explode('.',$entry);
						$rs[] = array('name' => $mod[0], 'status' => 0);
					}
				}
				closedir($handle);
			}
		}else{
			global $APP_PATH;
			$rs = array();
			if ($handle = opendir($APP_PATH . APPLICATION . '/widgets/')) {
				while (false !== ($entry = readdir($handle))) {
					if( eregi('.widget.php',$entry) ){
						$mod = explode('.',$entry);
						$active = 0;
						$key = array_search($mod[0], $widgets); 
						if( $key === 0 || intval($key) >= 1 ){
							$active = 1;
						}
						
						$rs[] = array('name' => $mod[0], 'active' => $active);
					}
				}
				closedir($handle);
			}
		}
		
		return $rs;
		
	}
	
}