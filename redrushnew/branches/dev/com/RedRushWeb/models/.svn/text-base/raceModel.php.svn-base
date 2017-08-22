<?php
	class raceModel extends SQLData{
		
		private $arr_use_equal = array();
		function __construct(){ parent::SQLData();	}
		
		/**
		 * get random user untuk halaman race - choose your opponent
		 * @todo
		 * yang ditarik hanya user2 yang levelnya sama atau lebih besar dari si user.
		 * @param $user_id
		 */
		function getOpponent($user_id,$start=0,$total=10){
			$start = intval($start);
			//$q = "SELECT id,name,img,small_img FROM kana_member WHERE id <> ".$user_id." AND n_status=1 LIMIT ".$start.",".$total;
			$q = "SELECT level FROM ".RedRushDB.".kana_member a
				LEFT JOIN ".GameDB.".racing_level b	ON a.id = b.user_id
				WHERE a.id = ".$user_id." LIMIT 1";
			$this->open(0);
			$playerData = $this->fetch($q);
			$this->close();
			
			$q = "SELECT a.*,b.level,d.title_name FROM ".RedRushDB.".kana_member a
				LEFT JOIN ".GameDB.".racing_level b	ON a.id = b.user_id
				LEFT JOIN ".RedRushDB.".rr_user_title c ON c.user_id=a.id
				LEFT JOIN ".RedRushDB.".tbl_ref_title d  ON c.title_id = d.id 
				WHERE a.id <> ".$user_id." AND b.level between ".($playerData['level']-1)." AND ".($playerData['level']+1)." AND a.n_status=1 AND a.register_id <> 0 AND b.level <> 0 ";
			$this->open(0);
			$rand_opponent = $this->fetch($q,1);
			$this->close();
			//random
			for($start=1;$start<=$total;$start++){
				$queue = rand(0,sizeof($rand_opponent)-1);
				if(sizeof($rand_opponent) == sizeof($this->arr_use_equal) ) $this->arr_use_equal = null;
				if(! in_array($queue,$this->arr_use_equal)) array_push($this->arr_use_equal,$queue);
				else {
				while(in_array($queue,$this->arr_use_equal)){
				$queue = rand(0,sizeof($rand_opponent)-1);
				}
				array_push($this->arr_use_equal,$queue);
				}
				$x[] = $rand_opponent[$queue];
			}
			
			return $x;
		}
		
		
		
	}
?>