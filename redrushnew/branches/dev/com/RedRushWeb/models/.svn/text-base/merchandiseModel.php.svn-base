<?php
	class merchandiseModel extends SQLData{
		function __construct(){ parent::SQLData();	}
		
		function countAll($q=null){
			$q = "SELECT COUNT(*) total FROM ".RedRushDB.".rr_merchandise
				WHERE 1 $q";
			$this->open(0);
			$r = $this->fetch($q);
			$this->close();
			return $r['total'];
		}
		
		function getMerchandise($start=0,$total=1,$q=null){
		
			//id 	item_name 	amount 	prize 	img 	created_date 	n_status 
			$q = "
				SELECT * FROM ".RedRushDB.".rr_merchandise
				WHERE 1 $q ORDER BY level asc, n_status DESC LIMIT ".$start.",".$total;
			$this->open(0);
			$r = $this->fetch($q,1);
			$this->close();
			
			$n=0;
			
			foreach($r as $val){
			$r[$n]['display_item'] = false;
			$amount = $this->checkMerchandiseStock($val['id']);
			$prize = $this->getMerchandiseStockPrize($val['id']);
			
			
			if($prize) $r[$n]['prize'] = $prize;
			else {
			$primaryMerch = $this->getMerchandiseByGroup($val['group_merchandise'],true);
			$r[$n]['prize'] =  $primaryMerch['prize'];
			$r[$n]['amount'] =  $primaryMerch['amount'];
			$r[$n]['display_item'] = true;
			}
			if($amount) $r[$n]['amount'] = $amount;
			$n++;
			}
			// print_r('<pre>');print_r($r);exit;
		
		
			return $r;
		}
		
		function getGroupMerchandise(){
		
			//id 	item_name 	amount 	prize 	img 	created_date 	n_status 
			$q = "
				SELECT DISTINCT group_merchandise FROM ".RedRushDB.".rr_merchandise
				WHERE group_merchandise is Not NULL and group_merchandise<>'' ORDER BY group_merchandise ";
			$this->open(0);
			$r = $this->fetch($q,1);
			$this->close();
			return $r;
		}
		
		function getMerchandiseByID($id=null){
			if($id==null)return false;
			//id 	item_name 	amount 	prize 	img 	created_date 	n_status 
			$q = "
				SELECT * FROM ".RedRushDB.".rr_merchandise
				WHERE id=".$id.";";
			$this->open(0);
			$r = $this->fetch($q);
			$this->close();
			return $r;
		}
		
		function getDetailMerchandisebyID($user_id =null , $id=null){
		if($user_id==null)return false;
		if($id==null)return false;
		$q = "
				SELECT purch.prize  as pPrice , merch.item_name FROM ".RedRushDB.".rr_purchase_merchandise purch
				LEFT JOIN ".RedRushDB.".rr_merchandise as merch ON purch.merchandise_id = merch.id
				WHERE purch.id=".$id." AND purch.user_id=".$user_id." LIMIT 1;";

			$this->open(0);
			$purchaseMerchData = $this->fetch($q);
			$this->close();
		
		$txt="";
		if($purchaseMerchData)	{
		$txt= "
		<span>Point Redeem Updates</span> 
		<span>You have redeemed {$purchaseMerchData['pPrice']} points for {$purchaseMerchData['item_name']} </span> 
		<span> The merchandise will be delivered within one month. You'll be the envy of all your friends with this stunning merchandise. They won't believe you're getting it for free. </span>";
		
		}
			// print_r  ($txt);		exit;
		return $txt;
		}
		
		
		function getMerchandiseByGroup($group_merchandise=null,$avg=false){
			if($group_merchandise==null)return false;
			//id 	item_name 	amount 	prize 	img 	created_date 	n_status 
			$q = "
				SELECT * FROM ".RedRushDB.".rr_merchandise
				WHERE group_merchandise ='".$group_merchandise."';";
			$this->open(0);
			$r = $this->fetch($q,1);
			$this->close();
			$n=0;
			foreach($r as $val){
			$prize = $this->getMerchandiseStockPrize($val['id']);
			$r[$n]['prize'] = $prize;			
			$n++;
			}
						
			if($avg==true){
				foreach($r as $valPrize){
				$sumPrize+=$valPrize['prize'];
				$sumAmount+=$this->checkMerchandiseStock($valPrize['id']);
				
				}
					
				$prize=ceil($sumPrize/(count($r)-1));
				$amount=$sumAmount ;
				$r['prize'] = $prize;
				$r['amount'] = $amount;
			}
			
			return $r;
		}
		
		
		
		function addMerchandise($data){
		
			
			//id 	item_name 	amount 	prize 	img 	created_date 	n_status 
			$q = "
					INSERT INTO ".RedRushDB.".rr_merchandise 
					(id, item_name, amount, prize, description, img, created_date, n_status,level,group_merchandise,variant) 
					VALUES 
					(NULL, '".$data['name']."', ".$data['amount'].", ".$data['prizes'].", '".$data['desc']."', '".$data['img']."', now(), '".$data['status']."', '".$data['level']."','".$data['group_merchandise']."','".$data['variant']."')";
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
		
		
		function editMerchandise($item_id,$data,$img=0){
			if($item_id==NULL) return false;
			if($img==1){
				$q = "
				UPDATE ".RedRushDB.".rr_merchandise 
				SET 
					item_name = '".$data['name']."',
					amount = ".$data['amount'].", 
					prize = ".$data['prize'].", 
					description = '".$data['desc']."',
					img = '".$data['img']."', 
					n_status = '".$data['status']."' ,
					level = '".$data['level']."',
					group_merchandise = '".$data['group_merchandise']."',
					variant = '".$data['variant']."'
				WHERE 
					id = ".$item_id."";
			}else{
				$q = "
				UPDATE ".RedRushDB.".rr_merchandise 
				SET 
					item_name = '".$data['name']."',
					amount = ".$data['amount'].", 
					prize = ".$data['prize'].", 
					description = '".$data['desc']."',
					n_status = '".$data['status']."' ,
					level = '".$data['level']."',
					group_merchandise = '".$data['group_merchandise']."',
					variant = '".$data['variant']."'
					
				WHERE 
					id = ".$item_id."";
			}
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
		
		
		function changeStatus($id,$n_status){
			$q = "UPDATE ".RedRushDB.".rr_merchandise 
			SET 
				n_status = '".$n_status."' 
			WHERE 
				id = ".$id."";
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
		
		function deleteMerchandise($id){
			$q = "DELETE FROM ".RedRushDB.".rr_merchandise WHERE id='".$id."'";
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
		
		function getPurchaseList($start=0,$total=1,$q=null){
			$q = "SELECT concat(u.name, ' ', u.last_name) AS name, m.item_name, pm.* ,fm.*, u.StreetName , u.MobilePhone, qrcity.city_name as qrCityName
					FROM ".RedRushDB.".rr_purchase_merchandise pm 
					INNER JOIN ".RedRushDB.".rr_merchandise m 
					ON m.id = pm.merchandise_id 
					INNER JOIN ".RedRushDB.".kana_member u 
					ON u.id = pm.user_id 
					LEFT JOIN tbl_form_merchandise fm
					ON fm.purchase_merchandise_id=pm.purchase_merchandise_id
					LEFT JOIN tbl_qr_city qrcity ON u.city=qrcity.city_id
				WHERE 1 $q 
				GROUP by fm.purchase_merchandise_id
				ORDER BY pm.purchase_date DESC 
				LIMIT ".$start.",".$total;
			$this->open(0);
			$r = $this->fetch($q,1);
			$this->close();
			return $r;
		}
		
		function getPurchaseListByID($id=null){
			$q = "SELECT concat(u.name, ' ', u.last_name) AS name, m.item_name, pm.* ,fm.*, u.StreetName , u.MobilePhone, qrcity.city_name as qrCityName
					FROM ".RedRushDB.".rr_purchase_merchandise pm 
					INNER JOIN ".RedRushDB.".rr_merchandise m 
					ON m.id = pm.merchandise_id 
					INNER JOIN ".RedRushDB.".kana_member u 
					ON u.id = pm.user_id 
					LEFT JOIN tbl_form_merchandise fm
					ON fm.purchase_merchandise_id=pm.purchase_merchandise_id
					LEFT JOIN tbl_qr_city qrcity ON u.city=qrcity.city_id
				WHERE pm.id = '".$id."' 
				GROUP by fm.purchase_merchandise_id
				ORDER BY pm.purchase_date DESC 
				LIMIT 1";
			$this->open(0);
			$r = $this->fetch($q);
			$this->close();
			return $r;
		}
		
		function countPurchase($q=null){
			$q = "SELECT COUNT(*) total
				FROM ".RedRushDB.".rr_purchase_merchandise pm
					INNER JOIN ".RedRushDB.".rr_merchandise m
					ON m.id = pm.merchandise_id
					INNER JOIN ".RedRushDB.".kana_member u
					ON u.id = pm.user_id
					LEFT JOIN tbl_form_merchandise fm
					ON fm.purchase_merchandise_id=pm.purchase_merchandise_id
				WHERE 1 $q";
			$this->open(0);
			$r = $this->fetch($q);
			$this->close();
			return $r['total'];
		}
		
		function deletePurchase($id){
			$q = "DELETE FROM ".RedRushDB.".rr_purchase_merchandise WHERE id='".$id."'";
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
		
		function getMerchName($id){
			$q = "SELECT item_name FROM ".RedRushDB.".rr_merchandise WHERE id='".$id."'";
			$this->open(0);
			$r = $this->fetch($q);
			$this->close();
			return $r['item_name'];
		}
		
		function InputStock($data){
			$q = "
					INSERT INTO ".RedRushDB.".tbl_merchandise_stock 
					(merchandise_id, prize, amount, date, n_status) 
					VALUES 
					('".$data['id']."', ".$data['prize'].", ".$data['amount'].", '".$data['date']."', '".$data['status']."')";
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
		
		function EditStock($data){
			$q = "
					UPDATE ".RedRushDB.".tbl_merchandise_stock 
					SET prize='".$data['prize']."', amount='".$data['amount']."', date='".$data['date']."', n_status='".$data['status']."'
					WHERE id='".$data['id']."'";
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
		
		function countAllStock($id=null){
			$q = "SELECT COUNT(*) total FROM ".RedRushDB.".tbl_merchandise_stock 
				WHERE merchandise_id='".$id."'";
			$this->open(0);
			$r = $this->fetch($q);
			$this->close();
			return $r['total'];
		}
		
		function getMerchStock($start=0,$total=1,$id=null){
		
			//id 	item_name 	amount 	prize 	img 	created_date 	n_status 
			$q = "
				SELECT s.*, m.item_name as merch, m.img as merch_img 
				FROM ".RedRushDB.".tbl_merchandise_stock s 
				LEFT JOIN ".RedRushDB.".rr_merchandise m
				ON m.id = s.merchandise_id 
				WHERE merchandise_id='".$id."' ORDER BY id DESC LIMIT ".$start.",".$total;
			$this->open(0);
			$r = $this->fetch($q,1);
			$this->close();
			return $r;
		}
		
		function DeleteStock($id,$mid){
			$q = "DELETE FROM ".RedRushDB.".tbl_merchandise_stock WHERE id='".$id."' AND merchandise_id='".$mid."'";
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
		
		function getStock($id,$mid){
			$q = "SELECT s.*, m.item_name as merch, m.img as merch_img  
				FROM ".RedRushDB.".tbl_merchandise_stock s
				LEFT JOIN ".RedRushDB.".rr_merchandise m
				ON m.id = s.merchandise_id 
				WHERE s.id='".$id."' AND s.merchandise_id='".$mid."'";
			$this->open(0);
			$r = $this->fetch($q);
			$this->close();
			return $r;
		}
		
		
		function checkMerchandiseStock($id){
		
		$this->open(0);
		//get merchandise all stock
		$sql="SELECT sum(amount) as total FROM tbl_merchandise_stock WHERE merchandise_id=".$id." GROUP BY merchandise_id";
		$mercData =  $this->fetch($sql);
		//get purchased merchandise
		$sql="SELECT count(merchandise_id) as total FROM rr_purchase_merchandise WHERE n_status in('0','1') and merchandise_id=".$id." GROUP BY merchandise_id";
		$mercPurchaseData =  $this->fetch($sql);
		//count merchandise
		$this->close();
		$currentStock = round($mercData['total'] - $mercPurchaseData['total']);
		if($currentStock< 0 ) $currentStock = '0'.' ('.$currentStock.')';
		return $currentStock;
		
		}
		
		function getMerchandiseStockPrize($id){
		$this->open(0);
		//get merchandise all stock
		$sql="SELECT prize as total FROM tbl_merchandise_stock WHERE merchandise_id=".$id." ORDER BY date DESC LIMIT 1";
		$mercData =  $this->fetch($sql);
		$this->close();
		return $mercData['total'];
		
		}
		
		function NotificationStatus($msg_id){
			$q = "UPDATE ".RedRushDB.".rr_purchase_merchandise SET has_read=1
					WHERE id=".$msg_id."";
			$this->open(0);
			$r = $this->query($q);
			$this->close();
			return $r;
		}
		
	}
?>