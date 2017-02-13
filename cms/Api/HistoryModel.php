<?php namespace Cms\Api;

use Cms\DB;

class HistoryModel{

	public $image_table;

	public function getHistory($data = []){
		
		$sql = 'SELECT `amd`.`alias` as `menu_alias`, `amd`.`menu_id`, `an`.`id`, `an`.`mod`, `an`.`id`, `an`.`isHidden`, `an`.`id`, `an`.`inCalendar`, `an`.`inIndex`, `an`.`posi`, `an`.`eventDate`,`an`.`dateAdd` , `an`.`categories`, `and`.`metaD`, `and`.`metaK`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) LEFT JOIN `a_menu_description` as `amd` ON (`an`.`categories`=`amd`.`menu_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="history" ORDER BY `an`.`dateAdd` DESC ';
		 
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			
			$results = [];
			foreach($query -> rows as $tmp){
				
				if(isset($tmp -> images)){
					$tmp -> images = unserialize($tmp -> images);
				}
				if(!isset($tmp -> alias)){
					$tmp -> alias = $tmp -> id;
				}
				
				$results[$tmp -> id] = $tmp;
				$ids[] = $tmp -> id;
			}

			if(count($ids) && isset($this->image_table)){
				$sql = 'SELECT * FROM '.$this->image_table.' WHERE `isCover`="1" AND `mod`="history" AND `modid` IN ('.implode(',',$ids).')';
	
				$result = DB::query($sql);
				
				if($result -> numRows > 0){
					$this->covers = [];
					foreach($result -> rows as $tmp){
						$tmp -> ext = explode('.',$tmp -> name);
						$tmp -> ext = array_pop($tmp -> ext);
						$results[$tmp -> modid] -> cover = $tmp;
					}
				}
			}
			
			return $results;
		}
		return null;
	}
	
	public function getHistoryCount($data = []){

		$sql = 'SELECT COUNT(an.id) as `total`,  `amd`.`alias` as `menu_alias`, `amd`.`menu_id`, `an`.`id`, `an`.`mod`, `an`.`id`, `an`.`isHidden`, `an`.`id`, `an`.`inCalendar`, `an`.`inIndex`, `an`.`posi`, `an`.`eventDate`, `an`.`categories`, `and`.`metaD`, `and`.`metaK`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) LEFT JOIN `a_menu_description` as `amd` ON (`an`.`categories`=`amd`.`menu_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="history"';
		
		
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			return $query -> row -> total;
		}

		return null;
	}
	
	public function getHistoryItem($data = []){
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($data['menu_id']));
		if(!$data['menu_id']) return false;

		
		$m = __('date');
		
		$sql = 'SELECT `an`.`id`,`an`.`dateAdd`, `an`.`mod`, `an`.`id`, `an`.`isHidden`, `an`.`id`, `an`.`categories`, `and`.`metaD`, `and`.`metaK`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) LEFT JOIN `a_menu_description` as `amd` ON (`an`.`categories`=`amd`.`menu_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="history" AND `an`.`isHidden`="0"';
		
		if(is_numeric($data['menu_id'])){
			$sql .= 'AND `an`.`id`='.$data['menu_id'].' ';
		}else{
			$sql .= 'AND `and`.`alias`="'.$data['menu_id'].'" ';
		}
	

		$query = DB::query($sql);
		
		$results = [];
		if($query -> numRows == 1){
			$result = $query -> row;
			$result -> updated_at = $result -> dateAdd;
			if (isset($result -> dateAdd) && $result -> dateAdd != '0000-00-00 00:00:00'){
				
				$data = explode(' ',$result -> dateAdd);
				if(isset($data[0])){
					$first_data = explode('-', $data[0]);
					$result -> dateAdd = implode(' ',[$first_data[2],$m[$first_data[1]],$first_data[0]]);
				}else{
					$result -> dateAdd = $data[0];
				}
			}

			if(!isset($result -> alias)){
				$result -> alias = $result -> id;
			}
			
			if(isset($this->image_table)){
				$images = new ImagesModel();
				$images -> image_table = 'a_shop_images';
				$images -> mod = 'history';
				
				//$l->cover=$this->getCover($l->id);
				$result -> images = $images -> getImages($result -> id);
				if(isset($result -> images['cover'])){
					$result -> cover = $result -> images['cover'];
					//не удалять тут т.к. пропадет в админке в карточке
					//unset($l->images['cover']);
				}
			}elseif(isset($result -> images)){
				$result -> images = unserialize($result -> images);
			}
			return $result;
		}
		
		

		return null;
	}
}
