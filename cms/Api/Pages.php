<?php namespace Cms\Api;

use Cms\DB;

// Класс от старой CMS, модифицированный.
class Pages{
	
	public $image_table;
	
	public function getPages($data = []){
		
		$sql = 'SELECT `amd`.`alias` as `menu_alias`, `amd`.`menu_id`, `an`.`id`, `an`.`dateAdd`, `an`.`mod`, `an`.`id`, `an`.`isHidden`, `an`.`id`, `an`.`inCalendar`, `an`.`inIndex`, `an`.`posi`, `an`.`eventDate`, `an`.`categories`, `and`.`metaD`, `and`.`metaK`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) LEFT JOIN `a_menu_description` as `amd` ON (`an`.`categories`=`amd`.`menu_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="pages"  ';
		
		if(isset($data['menu_id'])){
			$id = preg_replace('/[^0-9a-z_-]+/i','',trim($data['menu_id']));
			
			if(is_numeric($data['menu_id'])){
				$sql .= 'AND `amd`.`menu_id`='.$id.' ';
			}else{
				$sql .= 'AND `amd`.`alias`="'.$id.'" ';
			}
			
			if (isset($data['inCalendar'])) {
				$sql .= ' AND  `an`.`inCalendar`="'.$data['inCalendar'].'" ';
			}
		}
		
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
	
	
	public function getPagesCount($data = []){

		$sql = 'SELECT COUNT(an.id) as `total`, `amd`.`alias` as `menu_alias`, `amd`.`menu_id`, `an`.`id`, `an`.`mod`, `an`.`id`, `an`.`isHidden`, `an`.`id`, `an`.`inCalendar`, `an`.`inIndex`, `an`.`posi`, `an`.`eventDate`, `an`.`categories`, `and`.`metaD`, `and`.`metaK`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) LEFT JOIN `a_menu_description` as `amd` ON (`an`.`categories`=`amd`.`menu_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="pages"';
		
		if(isset($data['menu_id'])){
			$id = preg_replace('/[^0-9a-z_-]+/i','',trim($data['menu_id']));
			
			if(is_numeric($data['menu_id'])){
				$sql .= 'AND `amd`.`menu_id`='.$id.' ';
			}else{
				$sql .= 'AND `amd`.`alias`="'.$id.'" ';
			}
			
			if (isset($data['inCalendar'])) {
				$sql .= ' AND  `an`.`inCalendar`="'.$data['inCalendar'].'" ';
			}
		}
		
		$sql .= ' ORDER BY  `an`.`dateAdd` DESC ';
		
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			return $query -> row -> total;
		}

		return null;
	}
	
	
	public static function getPage($id){
		
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($id));
		if(!$id) return false;

		
		$sql = 'SELECT `am`.`id`,`am`.`typeMenu`, `am`.`isHidden`,`am`.`dateAdd`,`am`.`isIndex`, `amd`.`alias`, `amd`.`title`, `amd`.`text`, `amd`.`metaK`, `amd`.`metaD`, `amd`.`menu_id`, `amd`.`for_smi`, `amd`.`lang` FROM `a_menu` as `am` LEFT JOIN `a_menu_description` as `amd` ON (`am`.`id`=`amd`.`menu_id`) WHERE `amd`.`alias`="'.$id.'" AND `amd`.`lang`="'.config('lang.weblang').'" AND `am`.`isHidden`="0"';
		

		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			return $query -> row;
		}

		return null;
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
	
	
	############### Для web #############
	
	public function getPageWeb($data = []){
		
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($data['menu_id']));
		if(!$data['menu_id']) return false;

		
		$sql = 'SELECT `amd`.`menu_id`,`amd`.`alias`, `an`.`id`, `an`.`mod`,  `an`.`isHidden`, `an`.`inCalendar`, `an`.`inIndex`, `an`.`posi`, `an`.`eventDate`, `an`.`categories`, `and`.`metaD`, `and`.`metaK`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) LEFT JOIN `a_menu_description` as `amd` ON (`an`.`categories`=`amd`.`menu_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="pages" AND `an`.`isHidden`="0"';
		
		if(is_numeric($data['menu_id'])){
			$sql .= 'AND `amd`.`menu_id`='.$data['menu_id'].' ';
		}else{
			$sql .= 'AND `amd`.`alias`="'.$data['menu_id'].'" ';
		}
		$query = DB::query($sql);
		
		if($query -> numRows == 1){
			$result = $query -> row;

			if(!isset($result -> alias)){
				$result -> alias = $result -> id;
			}
			
			if(isset($this->image_table)){
				$images = new ImagesModel();
				$images -> image_table = 'a_shop_images';
				$images -> mod = 'pages';
				
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
	
	
	
	public function getAliasPages($id){	
		$sql = 'SELECT `menu_id`, `lang`, `id`, `alias` FROM `a_menu_description` WHERE `menu_id`='.$id;
		return DB::query($sql) -> rows;
	}
	
	
}