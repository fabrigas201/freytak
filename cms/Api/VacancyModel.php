<?php namespace Cms\Api;

use Cms\DB;

class VacancyModel{

	public function getVacancy($data = []){
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($data['menu_id']));
		if(!$data['menu_id']) return false;

		
		$sql = 'SELECT `amd`.`alias` as `menu_alias`, `amd`.`menu_id`, `an`.`id`, `an`.`mod`, `an`.`id`, `an`.`isHidden`, `an`.`id`,  `an`.`inIndex`, `an`.`posi`, `an`.`categories`, `and`.`metaD`, `and`.`metaK`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) LEFT JOIN `a_menu_description` as `amd` ON (`an`.`categories`=`amd`.`menu_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="vacancy"';
		
		if(is_numeric($data['menu_id'])){
			$sql .= 'AND `amd`.`menu_id`='.$data['menu_id'].' ';
		}else{
			$sql .= 'AND `amd`.`alias`="'.$data['menu_id'].'" ';
		}
		
		
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
			return $query -> rows;
		}

		return null;
	}
	
	public function getVacancyCount($data = []){
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($data['menu_id']));
		if(!$data['menu_id']) return false;

		
		$sql = 'SELECT COUNT(an.id) as `total`,  `amd`.`alias` as `menu_alias`, `amd`.`menu_id`, `an`.`id`, `an`.`mod`, `an`.`id`, `an`.`isHidden`, `an`.`id`, `an`.`inIndex`, `an`.`posi`, `an`.`categories`, `and`.`metaD`, `and`.`metaK`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) LEFT JOIN `a_menu_description` as `amd` ON (`an`.`categories`=`amd`.`menu_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="vacancy"';
		
		if(is_numeric($data['menu_id'])){
			$sql .= 'AND `amd`.`menu_id`='.$data['menu_id'].' ';
		}else{
			$sql .= 'AND `amd`.`alias`="'.$data['menu_id'].'" ';
		}
		
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			return $query -> row -> total;
		}

		return null;
	}
	
	public function getVacancyItem($data = []){
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($data['menu_id']));
		if(!$data['menu_id']) return false;

		
		$sql = 'SELECT `an`.`id`, `an`.`mod`, `an`.`id`, `an`.`isHidden`, `an`.`id`, `an`.`inIndex`,  `an`.`posi`,  `an`.`categories`, `and`.`metaD`, `and`.`metaK`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) LEFT JOIN `a_menu_description` as `amd` ON (`an`.`categories`=`amd`.`menu_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="news"';
		
		if(is_numeric($data['menu_id'])){
			$sql .= 'AND `an`.`id`='.$data['menu_id'].' ';
		}else{
			$sql .= 'AND `and`.`alias`="'.$data['menu_id'].'" ';
		}
	

		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			return $query -> row;
		}

		return null;
	}
	
	
	


}
