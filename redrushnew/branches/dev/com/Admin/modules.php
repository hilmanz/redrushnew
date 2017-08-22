<?php
class modules extends SQLData{
	var $View;
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
	}
	
	function admin(){
		$act = $this->Request->getParam('act');
		if($act != '' ){
			return $this->edit();
		}else{
			return $this->modulesList();
		}
	}
	
	function modulesList(){
		
		global $APP_PATH;
		$rs = array();
		if ($handle = opendir($APP_PATH . APPLICATION . '/modules/')) {
			while (false !== ($entry = readdir($handle))) {
				if( eregi('.mod.php',$entry) ){
					$mod = explode('.',$entry);
					$qry = "SELECT COUNT(*) total, page_status AS status FROM gm_pages WHERE page_type='module' AND page_name='module_".$mod[0]."';";
					$r = $this->fetch($qry);
					$status = ( $r['status'] == 'active' ) ? 'Active' : 'Deactive';
					$rs[] = array('name' => $mod[0], 'status' => $status);
				}
			}
			closedir($handle);
		}
		$this->View->assign('list', $rs);
		return $this->View->toString("common/admin/modules-list.html");
	}
	
	function edit(){
		
		$module = strtolower($this->Request->getParam('module'));
		
		$qry = "SELECT * FROM gm_pages WHERE page_type='module' AND page_name='".mysql_escape_string('module_'.$module)."' LIMIT 1;";
		
		$rs = $this->fetch($qry);
		
		if(intval($this->Request->getPost('edit')) == 1){
		
			$set = $this->setModule($this->Request,$rs,$module);
			
			if( $set === true ){
			
				return $this->View->showMessage('Edit module success','index.php?s=modules');
			
			}else{
			
				$this->View->assign('err',$set);
				$this->View->assign('status',$this->Request->getPost('status'));
				$this->View->assign('login',$this->Request->getPost('login'));
			
			}
			
		}
		
		$this->View->assign('data', $rs);
		
		$this->View->assign('module', $module);
		
		$this->View->assign('widgets', $this->getWidgetList(unserialize($rs['page_widgets'])) );
		
		return $this->View->toString("common/admin/modules-edit.html");
		
	}
	
	function setModule($req,$rs,$name){
		
		$err = array();
		
		if( intval($rs['page_id']) == 0)
		{
		
			//Check page name must unique
			if( ! $this->checkPageName('module_'.$name) ){
				$err[] = "Module name not available";
			}
			//Check page request must unique
			if( ! $this->checkPageRequest($req->getPost('request')) ){
				$err[] = "Module request not available";
			}
		
		}
		
		if( count($err) > 0 ){
			
			return $err;
		
		}else{
			
			$widgets = serialize($_POST['widgets']);
			
			if( intval($rs['page_id']) == 0)
			{
				$qry = "INSERT INTO gm_pages
							(page_request,page_name,page_type,page_status,page_login,page_widgets)
							VALUES
							('".mysql_escape_string(strtolower($req->getPost('request')))."',
							 '".mysql_escape_string('module_'.$name)."',
							 'module',
							 '".mysql_escape_string($req->getPost('status'))."',
							 '".mysql_escape_string($req->getPost('login'))."',
							 '".$widgets."')";
			}else{
				$qry = "UPDATE gm_pages SET
								page_request='".mysql_escape_string(strtolower($req->getPost('request')))."',
								page_status='".mysql_escape_string($req->getPost('status'))."',
								page_login= '".mysql_escape_string($req->getPost('login'))."',
								page_widgets='".$widgets."'
							WHERE
								page_id='".intval($rs[page_id])."'
							";
			}
						 
			if( !$this->query($qry) ){
				$err[] = "Edit module failed, please try again!";
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