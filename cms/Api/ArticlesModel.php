<?php namespace Cms\Api;

use Cms\DB;

class ArticlesModel{

	public $mode;

	public function getArticles($data = []){
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($data['menu_id']));
		if(!$data['menu_id']) return false;

		
		$sql = 'SELECT `amd`.`alias` as `menu_alias`, `amd`.`lang`, `amd`.`menu_id`, `an`.`id`, `an`.`mod`, `an`.`dateAdd`, `an`.`isHidden`, `an`.`inCalendar`, `an`.`inIndex`, `an`.`posi`, `an`.`eventDate`, `an`.`categories`, `and`.`metaD`, `and`.`metaK`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) LEFT JOIN `a_menu_description` as `amd` ON (`an`.`categories`=`amd`.`menu_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="pages" ';
		
		if(is_numeric($data['menu_id'])){
			$sql .= 'AND `amd`.`menu_id`='.$data['menu_id'].' ';
		}else{
			$sql .= 'AND `amd`.`alias`="'.$data['menu_id'].'" ';
		}
		
		$sql .= ' ORDER BY `an`.`dateAdd` DESC ';
		
		
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
	
	
	
	public function getArticlesCount($data = []){
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($data['menu_id']));
		if(!$data['menu_id']) return false;

		
		$sql = 'SELECT COUNT(an.id) as `total`,  `amd`.`alias` as `menu_alias`, `amd`.`menu_id`, `an`.`id`, `an`.`mod`, `an`.`id`, `an`.`isHidden`, `an`.`id`, `an`.`inCalendar`, `an`.`inIndex`, `an`.`posi`, `an`.`eventDate`, `an`.`categories`, `and`.`metaD`, `and`.`metaK`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) LEFT JOIN `a_menu_description` as `amd` ON (`an`.`categories`=`amd`.`menu_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="pages" ';
		
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
	
	public function getArticle($data = []){
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($data['menu_id']));
		if(!$data['menu_id']) return false;

		
		$sql = 'SELECT `an`.`id`, `an`.`mod`, `an`.`id`, `an`.`isHidden`, `an`.`id`, `an`.`inCalendar`, `an`.`inIndex`, `an`.`posi`, `an`.`eventDate`, `an`.`categories`, `and`.`metaD`, `and`.`metaK`, `and`.`title`, `and`.`alias`, `and`.`descr`, `and`.`descrfull`, `and`.`news_id`, `and`.`for_smi`, `and`.`lang` FROM `a_news` as `an` LEFT JOIN `a_news_description` as `and` ON(`an`.`id`=`and`.`news_id`) WHERE `and`.`lang` = "'.config('lang.weblang').'" AND `an`.`mod`="pages" ';
		
		if(is_numeric($data['menu_id'])){
			$sql .= ' AND `an`.`id`='.$data['menu_id'].' ';
		}else{
			$sql .= ' AND `and`.`alias`="'.$data['menu_id'].'" ';
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
	
	
	public function getAliasArticles($id){	
		$sql = 'SELECT `news_id`, `lang`, `id`, `alias` FROM `a_news_description` WHERE `news_id`='.$id;
		return DB::query($sql) -> rows;
	}
	
	

}
