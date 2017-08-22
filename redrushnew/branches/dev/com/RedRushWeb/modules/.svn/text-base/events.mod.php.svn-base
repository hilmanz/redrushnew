<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class events extends App{
	
	var $Request;
	
	var $View;
	
	var $eventsModel;
	
	var $Paging;
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		require_once APP_PATH.APPLICATION."/models/eventsModel.php";
		$this->eventsModel = new eventsModel();
	}
	
	function home(){
			
			$total_per_page = 4;
			$start = $this->Request->getParam('st');
			if($start==NULL){$start = 0;}
			$events = $this->eventsModel->getLatest($start,$total_per_page); // get latest events
			$total_events = $this->eventsModel->getTotalEvents(); // get total events
			$this->Paging = new Paginate();
			$paging = $this->Paging->generate($start, $total_per_page, $total_events, "index.php?page=events");
			// echo $paging."<br>";
			// print_r($events);exit;
			$featured = $this->eventsModel->getFeatured(1); // get latest featured events
			//print_r($featured);exit;
			$this->assign('events',$events);
			$this->assign('paging',$paging);
			$this->assign('featured',$featured);
		
			$this->log('page','events');
			
			if($this->user->verified!='1') return $this->contentString("/not_verified_news.html",true);
			return $this->contentString("/events.html",true);
		
		
	}
	
	// View events Function
	function view(){
		$id = strip_tags($this->Request->getParam('nid'));
		if($id!=null){
			$featured = $this->eventsModel->getFeatured(1); // get latest featured events
			$view = $this->eventsModel->getEvents($id);
			$img = $view['img'];
			if($img) {
			$this->assign('img','contents/news/'.$img);
			$this->assign('thumb_img','contents/news/thumb_'.$img);
			}
			else $this->assign('img','');
			// print_r($view['img']);exit;
			$this->log('article',$id); // Log article
			$this->assign('view',$view);
			$this->assign('title',stripslashes($view['title']));
			$this->assign('detail',htmlspecialchars_decode($view['detail']));
			$this->assign('date',date('Y-m-d', strtotime($view['posted_date'])));
			$this->assign('featured',$featured);
			$this->assign('events',true);
			return $this->contentString("/events_view.html",true);
		}
		else{
			sendRedirect('index.php?page=events');
			exit;
		}
	}
	
	

}