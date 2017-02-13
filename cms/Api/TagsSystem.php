<?php namespace Cms\Api;

use Cms\DB;

// Класс от старой CMS, немного модифицированный.
class TagsSystem{
	
	public static function getTag($id){
		
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($id));
		if(!$id) return false;

		$description = [];

		// Достаем данные на разных языках
		$sql = 'SELECT tgs.title,  tgs.descr, tgs.tgs_id, tgs.lang FROM `a_tags_system_description` as `tgs`  WHERE '.(is_numeric($id)?'`tgs`.`tgs_id`':'`tgs`.`alias`').'="'.$id.'" ';
			
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			foreach($query -> rows as $row){
				$description['langs'][$row -> lang] = $row;
			}
		}
		
		// Достаем общие данные
		$sql = 'SELECT `id`, `isHidden`, `dateAdd`,`alias`, `posi` FROM `a_tags_system` WHERE `id`='.$id.' LIMIT 1';
		$base_query	= DB::query($sql);
		
		
		
		if($base_query -> numRows == 1){
			$result = $base_query -> row;
		
			if(!isset($result -> alias)){
				$result -> alias = $result -> id;
			}

			$description['base'] = $result;
		}
		return $description;
		
		
	}
	
	// Вытаскиваем все новости
	public static function getTags($sqlParam = []){
		
		if(isset($sqlParam['start'])){
			$start = (int)$sqlParam['start'];
		}else{
			$start = 0;
		}
		
		if(isset($sqlParam['limit'])){
			$limit = (int)$sqlParam['limit'];
		}else{
			$limit = 100;
		}
		
		if(isset($sqlParam['orderby'])){
			$orderby = $sqlParam['orderby'];
		}else{
			$orderby = '';
		}
		

		if(is_array($orderby)){
			$orderbyAdd = '';
			foreach($orderby as $k => $v){
				$v = strtolower($v)=='asc'?'asc':'desc';
				$orderbyAdd.='`'.$k.'` '.$v.',';
			}
			$orderbyAdd = trim($orderbyAdd,',');
		}
		

		$sql = 'SELECT `an`.`id`, `an`.`isHidden`,`an`.`posi`,`an`.`dateAdd`, `and`.`tgs_id`, `and`.`title`, `and`.`lang` FROM `a_tags_system` as `an` LEFT JOIN `a_tags_system_description` as `and` ON `an`.`id`=`and`.`tgs_id` WHERE `and`.`lang`="'.config('lang.base').'"  '.(isset($orderbyAdd)?' ORDER BY '.$orderbyAdd.' ':'').(isset($limit)?'LIMIT '.$start.','.$limit:'');
		
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			
			return $query -> rows;
		}
		return null;
	}
	
	// Вытаскиваем все новости
	public static function getTagsCount($sqlParam = []){
		
		if(isset($sqlParam['start'])){
			$start = (int)$sqlParam['start'];
		}else{
			$start = 0;
		}
		
		if(isset($sqlParam['limit'])){
			$limit = (int)$sqlParam['limit'];
		}else{
			$limit = 100;
		}
		
		if(isset($sqlParam['orderby'])){
			$orderby = $sqlParam['orderby'];
		}else{
			$orderby = '';
		}
		

		if(is_array($orderby)){
			$orderbyAdd = '';
			foreach($orderby as $k => $v){
				$v = strtolower($v)=='asc'?'asc':'desc';
				$orderbyAdd.='`'.$k.'` '.$v.',';
			}
			$orderbyAdd = trim($orderbyAdd,',');
		}
		

		$sql = 'SELECT COUNT(an.id) as total ,`an`.`id`, `an`.`isHidden`,`an`.`posi`,`an`.`dateAdd`, `and`.`tgs_id`, `and`.`title`, `and`.`lang` FROM `a_tags_system` as `an` LEFT JOIN `a_tags_system_description` as `and` ON `an`.`id`=`and`.`tgs_id` WHERE `and`.`lang`="'.config('lang.base').'"  '.(isset($orderbyAdd)?' ORDER BY '.$orderbyAdd.' ':'').(isset($limit)?'LIMIT '.$start.','.$limit:'');
		
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			
			return $query -> row;
		}
		return null;
	}
	
	
	public static function getTagInfo($tag_alias, $single=false){
		
		$tag_alias = preg_replace('/[^0-9a-z_-]+/i','',trim($tag_alias));
		if(!$tag_alias) return false;

		$description = [];

		$sql = 'SELECT `ats`.`alias`, `ats`.`id`,`ats`.`isHidden`,`atgs`.`tgs_id`,`atgs`.`page_id`, `an`.`id`, `an`.`mod`, `an`.`categories`, `an`.`inIndex`, `an`.`inCalendar`, `an`.`posi`, `and`.`title`, `and`.`alias`, `and`.`metaD`, `and`.`metaK`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON (`an`.`id`=`and`.`news_id`) LEFT JOIN `a_tags_system_to_page` as `atgs` ON (`an`.`id`=`atgs`.`page_id`) LEFT JOIN `a_tags_system` as `ats` ON( `ats`.`id`=`atgs`.`tgs_id`) WHERE `and`.`lang`="'.config('lang.weblang').'"  AND `an`.`mod`="pages" AND `ats`.`alias`="'.$tag_alias.'" AND `ats`.`isHidden`=0 ORDER BY `an`.`posi` ASC ';
		
		$query = DB::query($sql);
		
		
		if($single == true){
			return $query -> row;
		}
		
		if(count($query -> rows)){
			return $query -> rows;
		}
		
	}
	
	
	public static function getTagsForPage($menu_id){
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($menu_id));
		if(!$menu_id) return false;
		
		$sql = 'SELECT * FROM `a_tags_system_to_page` WHERE `page_id`="'.$id.'" ';	
		$query = DB::query($sql);
		
		$tags = [];
		
		if(count($query -> rows)){
			foreach($query -> rows as $rows){
				$tags[$rows -> tgs_id] = $rows;
			}
		}
		
		return $tags;
	}

}