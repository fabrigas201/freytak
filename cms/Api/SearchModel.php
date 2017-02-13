<?php namespace Cms\Api;

use Cms\DB;

class SearchModel{

	public $image_table;

	public function getSearch($data = []){
		
		if(isset($data['query'])){
			$query = addslashes($data['query']);
		}else{
			$query = '';
		}
		
		$sql = 'SELECT `an`.`id`, `an`.`dateAdd`, `an`.`mod`, `an`.`id`, `an`.`isHidden`, `an`.`id`, `an`.`inIndex`, `an`.`posi`,  `an`.`categories`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND (`and`.`title` LIKE "%'.$query.'%" OR `and`.`descrfull` LIKE "%'.$query.'%" OR `and`.`descr` LIKE "%'.$query.'%")  AND (`an`.`mod`="news" OR `an`.`mod`="pages")  AND `an`.`isHidden`="0" ';

		$sql .= ' ORDER BY  `an`.`dateAdd` DESC ';
		

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
	
	public function getSearchCount($data = []){
		
		
		if(isset($data['query'])){
			$query = addslashes($data['query']);
		}else{
			$query = '';
		}
		
		$sql = 'SELECT COUNT(an.id) as `total`, `an`.`dateAdd`, `an`.`mod`, `an`.`id`, `an`.`isHidden`, `an`.`id`, `an`.`inIndex`, `an`.`posi`,  `an`.`categories`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND (`and`.`title`	LIKE "%'.$query.'%" OR `and`.`descrfull` LIKE "%'.$query.'%" OR `and`.`descr` LIKE "%'.$query.'%")  AND (`an`.`mod`="news" OR `an`.`mod`="pages")  AND `an`.`isHidden`="0" ';
		
		$sql .= ' ORDER BY  `an`.`dateAdd` DESC ';
		
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			return $query -> row -> total;
		}

		return null;
	}
}
