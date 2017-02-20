<?php namespace Cms\Api;

use Cms\DB;

class NewsModel{

	public $image_table;

	public function getNews($data = []){
		
		$sql = 'SELECT `amd`.`alias` as `menu_alias`,`amd`.`lang`, `amd`.`menu_id`, `an`.`id`, `an`.`dateAdd`, `an`.`mod`, `an`.`id`, `an`.`isHidden`, `an`.`id`, `an`.`inCalendar`, `an`.`inIndex`, `an`.`posi`, `an`.`eventDate`, `an`.`categories`, `and`.`metaD`, `and`.`metaK`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) LEFT JOIN `a_menu_description` as `amd` ON (`an`.`categories`=`amd`.`menu_id`) WHERE `amd`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="news"  ';
		
		if(isset($data['menu_id'])){
			$id = preg_replace('/[^0-9a-z_-]+/i','',trim($data['menu_id']));
			
			if(is_numeric($data['menu_id'])){
				$sql .= 'AND `amd`.`menu_id`='.$id.' ';
			}else{
				$sql .= 'AND `amd`.`alias`="'.$id.'" ';
			}
		}
		
		if (isset($data['inCalendar'])) {
			$sql .= ' AND  `an`.`inCalendar`="'.$data['inCalendar'].'" ';
		}
		
		if(isset($data['sort'])){
			if($data['sort'] == 'date_events_desc'){
				$sql .= ' ORDER BY  `an`.`eventDate` DESC ';
			}
		}else{
			$sql .= ' ORDER BY  `an`.`dateAdd` DESC ';
		}
		

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
				$sql = 'SELECT * FROM '.$this->image_table.' WHERE `isCover`="1" AND `mod`="news" AND `modid` IN ('.implode(',',$ids).')';
	
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
	
	public function getNewsCount($data = []){
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($data['menu_id']));
		if(!$data['menu_id']) return false;

		
		$sql = 'SELECT COUNT(an.id) as `total`,  `amd`.`alias` as `menu_alias`, `amd`.`menu_id`, `an`.`id`, `an`.`mod`, `an`.`id`, `an`.`isHidden`, `an`.`id`, `an`.`inCalendar`, `an`.`inIndex`, `an`.`posi`, `an`.`eventDate`, `an`.`categories`, `and`.`metaD`, `and`.`metaK`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) LEFT JOIN `a_menu_description` as `amd` ON (`an`.`categories`=`amd`.`menu_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="news"';
		
		if(is_numeric($data['menu_id'])){
			$sql .= 'AND `amd`.`menu_id`='.$data['menu_id'].' ';
		}else{
			$sql .= 'AND `amd`.`alias`="'.$data['menu_id'].'" ';
		}
		
		if (isset($data['inCalendar'])) {
			$sql .= ' AND  `an`.`inCalendar`="'.$data['inCalendar'].'" ';
		}
		
		$sql .= ' ORDER BY  `an`.`id` DESC ';
		
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			return $query -> row -> total;
		}

		return null;
	}
	
	public function getNewsItem($data = []){
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($data['menu_id']));
		if(!$data['menu_id']) return false;

		$m = __('date');
		
		$sql = 'SELECT `an`.`id`, `an`.`mod`, `an`.`id`, `an`.`dateAdd`, `an`.`id`, `an`.`inCalendar`, `an`.`inIndex`, `an`.`posi`, `an`.`eventDate`, `an`.`categories`, `and`.`metaD`, `and`.`metaK`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="news" ';
		
		if(is_numeric($data['menu_id'])){
			$sql .= 'AND `an`.`id`="'.$data['menu_id'].'" ';
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
				$images -> mod = 'news';
				
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
	
	
	public function ItemPrev($params = []){
		
		if(isset($params['dateAdd'])){
			$dateAdd = $params['dateAdd'];
		}
		if(isset($params['categories'])){
			$categories = $params['categories'];
		}
		if(!isset($dateAdd) || (!isset($categories) || $categories == 0)){
			return;
		}
		
		$sql = 'SELECT `an`.`id`, `an`.`mod`, `an`.`dateAdd`, `an`.`posi`, `an`.`eventDate`, `an`.`categories`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="news"  AND `an`.`categories`="'.$categories.'" AND `an`.`dateAdd` < "'.$dateAdd.'" ORDER BY  `an`.`dateAdd` DESC LIMIT 1';
		
		
		return DB::query($sql) -> row;
	}
	
	public function ItemNext($params = []){
		
		if(isset($params['dateAdd'])){
			$dateAdd = $params['dateAdd'];
		}
		if(isset($params['categories'])){
			$categories = $params['categories'];
		}
		if(!isset($dateAdd) || (!isset($categories) || $categories == 0)){
			return;
		}
		
		$sql = 'SELECT `an`.`id`, `an`.`mod`, `an`.`dateAdd`, `an`.`posi`, `an`.`categories`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="news"  AND `an`.`categories`="'.$categories.'" AND `an`.`dateAdd` > "'.$dateAdd.'" ORDER BY  `an`.`dateAdd` ASC LIMIT 1';
		
		return DB::query($sql) -> row;
	}
	
	
	public function getAliasNews($id){	
		$sql = 'SELECT `news_id`, `lang`, `id`, `alias` FROM `a_news_description` WHERE `news_id`='.$id;
		return DB::query($sql) -> rows;
	}
	
	
}
