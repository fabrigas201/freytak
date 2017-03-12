<?php namespace Cms\Api;

use Cms\DB;

class ContactsModel{

	public $image_table;

	public function getContact($id){
		
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($id));
		if(!$id) return false;
		
		$description = [];
		
		// Достаем данные на разных языках
		$sql = 'SELECT od.alias, od.metaD, od.metaK, od.title, od.descr, od.descrfull, od.other_id, od.lang FROM `a_other_description` as `od`  WHERE '.(is_numeric($id)?'`od`.`other_id`':'`od`.`alias`').'="'.$id.'" LIMIT 2';
			
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			foreach($query -> rows as $row){
				$description['langs'][$row -> lang] = $row;
			}
		}
		
		// Достаем общие данные
		$sql = 'SELECT `id`, `mod`, `modid`, `isHidden`,  `data`,`inIndex`, `posi`, `category` FROM `a_other` WHERE `id`='.$id.' LIMIT 1';
		$base_query	= DB::query($sql);
		
		if($base_query -> numRows == 1){
			$result = $base_query -> row;
			
			if(isset($result -> data)){
				$result -> data = unserialize($result -> data);
			}
			
			if(!isset($result -> alias)){
				$result -> alias = $result -> id;
			}
			$description['base'] = $result;
		}
		return $description;
	}
	
	
	public function getContacts($data = []){
		
		$sql = 'SELECT `o`.`id`, `o`.`isHidden`, `o`.`modid`, `o`.`mod`, `o`.`posi`, `od`.`title`, `od`.`other_id`, `od`.`lang` FROM `a_other` as `o` LEFT JOIN `a_other_description` as `od` ON (`o`.`id`=`od`.`other_id`) WHERE `od`.`lang` = "'.config('lang.base').'" AND `o`.`mod`="contacts" ';
		
		$sql .= ' ORDER BY  `o`.`id` DESC ';
		
		if (isset($data['start']) || isset($data['limit'])) {
			if(!isset($data['start'])){
				$data['start'] = 0;
			}else{
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}
			}
			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			return $query -> rows;
		}
	

		return null;
	}
	
	public function getContactsCount($data = []){
		$sql = 'SELECT COUNT(o.id) as `total`, `o`.`modid`, `o`.`mod`, `o`.`posi`, `od`.`title`, `od`.`other_id`, `od`.`lang` FROM `a_other` as `o` LEFT JOIN `a_other_description` as `od` ON (`o`.`id`=`od`.`other_id`) WHERE `od`.`lang` = "'.config('lang.base').'" AND `o`.`mod`="contacts" ';
		
		$sql .= ' ORDER BY  `o`.`id` DESC ';
		

		
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			return $query -> row -> total;
		}
	

		return null;
	}
	

	public function getContactWeb($data = []){
		
		$sql = 'SELECT `amd`.`id`, `amd`.`alias`, `amd`.`title`,  `o`.`id`, `o`.`data`, `o`.`isHidden`, `o`.`modid`, `o`.`mod`, `o`.`posi`, `o`.`category`, `od`.`metaK`,`od`.`metaD`, `od`.`descr`,`od`.`descrfull`, `od`.`other_id`, `od`.`lang` FROM `a_other` as `o` LEFT JOIN `a_other_description` as `od` ON (`o`.`id`=`od`.`other_id`)  LEFT JOIN `a_menu_description` as `amd` ON (`o`.`category`=`amd`.`menu_id`) WHERE `od`.`lang` = "'.config('lang.weblang').'" AND `o`.`mod`="contacts" ';
		
		if(is_numeric($data['menu_id'])){
			$sql .= 'AND `amd`.`id`='.$data['menu_id'].' ';
		}else{
			$sql .= 'AND `amd`.`alias`="'.$data['menu_id'].'" ';
		}
		
		
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			$result = $query -> row;
			
			if(!isset($result -> alias)){
				$result -> alias = $result -> id;
			}
			if(isset($result -> data)){
				$result -> data = unserialize($result -> data);
			}

			return $result;
		}
		
	
		return null;
	}
}
