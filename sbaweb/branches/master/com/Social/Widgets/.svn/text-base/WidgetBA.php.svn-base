<?php
class WidgetBA extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $_template="";
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->run();
	}
	function run(){
		//$list = $this->getTopPerformers();
		//$this->View->assign("list",$list);
	}
	function getBACharts($total=10){
		$caps = 1050;
		$sql = "SELECT * FROM (SELECT b.*,SUM(amount) as registrants FROM tbl_daily_registration a 
				INNER JOIN social_member b
				ON a.user_id = b.id 
				GROUP BY a.user_id) c
				ORDER BY registrants DESC LIMIT ".intval($total);
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
	foreach($rs as $nn=>$v){
			$rs[$nn]['progress'] = round($rs[$nn]['registrants'] / $caps * 100);
		}
		$this->_template = 'ba_charts';
		$this->View->assign("list",$rs);
	}
	function my_network($user_id, $self=1, $name='My'){
		//friends
		$friends = $this->getNetwork($user_id,1,4);
		$entourage = $this->getNetwork($user_id,0,4);
		
		if( $self == 1){
			$this->assign("name","My");
		}else{
			$this->assign("name",$name);
		}
		$this->assign("sid",$user_id);
		$this->_template = 'my_network';
		$this->assign("friends",$friends);
	}
	function getNetwork($user_id,$type=0,$total=4){
		$sql = "SELECT * FROM social_network a
				INNER JOIN social_member b
				ON a.friend_id = b.id
				WHERE a.user_id = ".$user_id." 
				AND b.type=".$type." 
				LIMIT ".$total;
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	function getTopPerformer($total=3){
		$caps = 1050;
		$sql = "SELECT * FROM (SELECT b.*,SUM(amount) as registrants FROM tbl_daily_registration a 
				INNER JOIN social_member b
				ON a.user_id = b.id 
				GROUP BY a.user_id) c
				ORDER BY registrants DESC LIMIT ".intval($total);
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		foreach($rs as $nn=>$v){
			$rs[$nn]['progress'] = round($rs[$nn]['registrants'] / $caps * 100);
		}
		$this->_template = 'top_performers';
		$this->View->assign("list",$rs);
	}
	
	function getMyPerformance($user_id){
		/*
		$sql = "some sql here";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		*/
		global $APP_PATH;
		include_once $APP_PATH."BA/Performance.php";
		$performance = new Performance($this->Request);
		$performance->open();
		$rs = $performance->getDailyRegistration(intval($user_id));
		$performance->close();
		$data = array();
		if(sizeof($rs)>0){
			foreach($rs as $d){
				$nd = strtotime($d['tgl']);
				settype($nd,'string');
				array_push($data,array($nd,intval($d['amount'])));
			}
		}
		$this->assign("data", json_encode($data));
		$this->_template = 'my_performance';
		
	}
	
	function getTopCity($total=3){
		/*
		$sql = "some sql here";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		*/
		global $APP_PATH;
		include_once $APP_PATH.'BA/Performance.php';
		$ba=new Performance();
		$this->open(0);
		$rs=$ba->TopCity($total);
		$this->close();
		$this->assign("list",$rs);
		$this->_template = 'top_city';
		return '';
	}
	function getTopEvent($total=3){
		/*
		$sql = "some sql here";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		*/
		$this->_template = 'top_event';
		return '';
	}
	function __toString(){
		return $this->View->toString("Social/widgets/".$this->_template.".html");
	}
	
}
?>
