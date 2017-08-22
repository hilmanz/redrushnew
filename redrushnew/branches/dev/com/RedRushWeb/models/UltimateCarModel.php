<?php
	class UltimateCarModel extends SQLData{
		function __construct(){ parent::SQLData();	}
		
		function countAll($q=null){
			$q = "SELECT COUNT(*) total FROM ".RedRushDB.".rr_ultimate_car
				WHERE 1 $q";
			$this->open(0);
			$r = $this->fetch($q);
			$this->close();
			return $r['total'];
		}
		
		function getCarList($start=0,$total=1,$q=null){
		
			//id 	item_name 	amount 	prize 	img 	created_date 	n_status 
			$q = "
				SELECT * FROM ".RedRushDB.".rr_ultimate_car
				WHERE 1 $q LIMIT ".$start.",".$total;
			$this->open(0);
			$r = $this->fetch($q,1);
			$this->close();
			return $r;
		}
		
		function getCarByID($id=null){
			if($id==null)return false;
			//id 	item_name 	amount 	prize 	img 	created_date 	n_status 
			$q = "
				SELECT * FROM ".RedRushDB.".rr_ultimate_car 
				WHERE ultimate_id=".$id.";";
			$this->open(0);
			$r = $this->fetch($q);
			$this->close();
			return $r;
		}
		
		
		function addCar($data){
			$q = "
					INSERT IGNORE INTO ".RedRushDB.".rr_ultimate_car 
					(ultimate_id, name, img, small_img, level, persentase_lose, n_status) 
					VALUES 
					(".$data['id'].", '".$data['name']."', '".$data['img']."', '".$data['thumb']."', '".$data['level']."', '".$data['persen']."' , '".$data['status']."')";
					//echo $q; exit;
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
		
		
		function editCar($item_id,$data,$img=0){
			if($item_id==NULL) return false;
			if($img==1){
				$q = "
				UPDATE ".RedRushDB.".rr_ultimate_car 
				SET 
					name = '".$data['name']."',
					img = '".$data['img']."', 
					small_img = '".$data['thumb']."',
					level = ".$data['level'].",
					persentase_lose = ".$data['persen'].",
					n_status = '".$data['status']."' 
				WHERE 
					ultimate_id = ".$item_id."";
			}else{
				$q = "
				UPDATE ".RedRushDB.".rr_ultimate_car 
				SET 
					name = '".$data['name']."',
					level = ".$data['level'].",
					persentase_lose = ".$data['persen'].",
					n_status = '".$data['status']."' 
				WHERE 
					ultimate_id = ".$item_id."";
			}
			/* print_r($data);
			echo "<hr>";
			echo $q;exit; */
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
		
		
		function changeStatus($id,$n_status){
			$q = "UPDATE ".RedRushDB.".rr_ultimate_car 
			SET 
				n_status = '".$n_status."' 
			WHERE 
				ultimate_id = ".$id."";
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
		
		function deleteCar($id){
			$q = "DELETE FROM ".RedRushDB.".rr_ultimate_car WHERE ultimate_id='".$id."'";
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
		
	}
?>