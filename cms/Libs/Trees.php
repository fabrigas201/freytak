<?php namespace Cms\Libs;

use Cms\DB;

class Trees{
	public $tbl;
	public $catList;
	public $noPrefix=false;

	public function __construct($tbl=false){
		$this -> tbl = $tbl;
		$this -> catList = [];
	}


	/*
		рекурсивно получает из базы дерево
	*/
	public function getTree($id, $params = [] ,$level=0){
		if(!is_numeric($id))
			return;


		$sql = 'SELECT `am`.`id`, `am`.`isHidden`,`am`.`pid`, `am`.`typeMenu`, `am`.`posi`, `am`.`isIndex`, `amd`.`title`,`amd`.`title`, `amd`.`alias`, `amd`.`menu_id`, `amd`.`lang` FROM `a_menu` as `am` LEFT JOIN `a_menu_description` as `amd` ON (`am`.`id`=`amd`.`menu_id`) WHERE `am`.`pid`="'.$id.'"  ';
	   
		if(!isset($params['prefix'])){
			$params['prefix'] = '';
		}
		
		if(isset($params['lang'])){
			$sql .= ' AND `amd`.`lang`= "'.$params['lang'].'" ';
		}else{
			$sql .= ' AND `amd`.`lang`= "'.config('lang.base').'" ';
		}
		
		if(isset($params['isHidden']) && $params['isHidden'] == false){
			$sql .= ' AND `am`.`isHidden`= "0" ';
		}
		
		$sql .= ' ORDER BY `am`.`posi`,`amd`.`title` ASC ';
		
		$result = DB::query($sql);
		
		
		if($result -> numRows > 0){
			$level++;

			$params['prefix'].=$this->noPrefix ? '' : ($level==1?'|':'').'&#8212; ';
			
			
			foreach($result -> rows as $item){
				
				$item->title=$params['prefix'].$item->title;
				$item->level=$level;
				if(isset($_GET['action']) && $_GET['action']=='edit' && $item->id==$_GET['id']) continue;

				array_push($this->catList,$item);
				
				$this->getTree($item->id,$params,$level);
			}
		}else{
			return null;
		}
	}
	

	public function getTreeMenu($id,$params =[], $level=0){
		if(!is_numeric($id))
			return;

		$sql = 'SELECT `am`.`id`,`am`.`pid`, `am`.`posi`, `am`.`isIndex`, `am`.`typeMenu`, `amd`.`title`,`amd`.`alias`, `amd`.`menu_id`, `amd`.`lang` FROM `a_menu` as `am` LEFT JOIN `a_menu_description` as `amd` ON (`am`.`id`=`amd`.`menu_id`) WHERE `am`.`pid`='.$id.' ';

		
		
		if(!isset($params['prefix'])){
			$params['prefix'] = '';
		}
		
		if(isset($params['lang'])){
			$sql .= ' AND `amd`.`lang`= "'.config('lang.weblang').'" ';
		}else{
			$sql .= ' AND `amd`.`lang`= "'.config('lang.base').'" ';
		}
		
		if(isset($params['isHidden']) && $params['isHidden'] == false){
			$sql .= ' AND `am`.`isHidden`= "0" ';
		}
		
		$sql .= ' ORDER BY `am`.`posi` ASC ';

		$result = DB::query($sql);

		if($result -> numRows > 0){
			$level++;
			
			
			if($level == 2){
				$html = '<ul class="nav__second">';
			}else{
				$html = '<ul id="rm-top-menu" class="nav arno">';
			}
	
			foreach($result -> rows as $item){
				$item->level=$level;
				
				if($item -> pid != 0){
					$class = ' nav__item_second ';
					$link_class = ' nav__link_second ';
				}else{
					$class = '';
					$link_class = '';
				}
				
				$html .= '<li class="nav__item '.$class.'">';
				
				if($item -> alias == 'podpiska-na-pravovye-novosti'){
					continue;
				}

				if(!preg_match("/^(http:|https:)\/\//", $item -> alias)){
					if($item -> isIndex == 1){
						$html .= '<a href="'.get_url(config('lang.weblang')).'" class="nav__link '.$link_class.'">'.$item -> title.'</a>';
					}elseif(!empty($item -> typeMenu) && $item -> isIndex != 1){
						$html .= '<a href="'.get_url(config('lang.weblang'), $item -> typeMenu,$item -> alias).'" class="nav__link '.$link_class.'">'.$item -> title.'</a>';
					}else{
						$html .= '<a href="'.get_url(config('lang.weblang'),'page',$item -> alias).'" class="nav__link '.$link_class.'">'.$item -> title.'</a>';
					}
				}else{
					$html .= '<a href="'.$item -> alias.'" class="nav__link '.$link_class.'">'.$item -> title.'</a>';
				}
				
				
				
				
				
				$html .= $this->getTreeMenu($item->id,$params, $level);
				$html .= '</li">';
			}
			$html .= '</ul>';
			return $html;
		}else{
			return;
		}
	}


	/*
		удаляет категорию и все подкатегории
	*/
	function delTree($id){
		if(!is_numeric($id))
			return;

		$q='DELETE FROM '.$this->tbl.' WHERE id='.$id;
		@mysql_query($q) or die(mysql_error());

		$this->catList=array();
		$this->getTree($id);
		foreach($this->catList as $item){
			$q='DELETE FROM '.$this->tbl.' WHERE id="'.$item->id.'"';
			@mysql_query($q) or die(mysql_error());
		}

		$this->catList=array();
	}

	/*
		сохраняем позиции категории (то как они сортируются)
	*/
	function savePosi($arr=array()){
		if(!is_array($arr)) return;

		foreach($arr as $id=>$posi){
			if($posi!=''){
				$q='UPDATE '.$this->tbl.' SET posi='.intval($posi).' WHERE id='.intval($id);
				@mysql_query($q);
			}
		}
		return true;
	}

	/*
		получаем подкатегории для данной категории
	*/
	public function getChilds($id, $params = []){
		if(!is_numeric($id))
			return;

		
		$sql = 'SELECT `am`.`id`,`am`.`pid`, `am`.`posi`, `am`.`isIndex`, `am`.`typeMenu`, `amd`.`title`,`amd`.`alias`, `amd`.`menu_id`, `amd`.`lang` FROM `a_menu` as `am` LEFT JOIN `a_menu_description` as `amd` ON (`am`.`id`=`amd`.`menu_id`) WHERE `am`.`pid`="'.$id.'" ';
		
		
		if(isset($params['lang'])){
			$sql .= ' AND `amd`.`lang`= "'.config('lang.weblang').'" ';
		}else{
			$sql .= ' AND `amd`.`lang`= "'.config('lang.base').'" ';
		}
		
		if(isset($params['isHidden']) && $params['isHidden'] == false){
			$sql .= ' AND `am`.`isHidden`= "0" ';
		}
		
		$sql .= ' ORDER BY `am`.`posi` ASC ';

		$result = DB::query($sql);

		if($result -> numRows > 0){
			foreach($result -> rows as $item){
				array_push($this->catList,$item);
			}
		}else{
			return null;
		}
	}



	// родительское дерево начиная от выбранной ($id) до корня ветви категории.
	
	public function getParents($id,$separator=''){
		
		$sql = 'SELECT `am`.`id`,`am`.`typeMenu`,`am`.`isHidden`,`am`.`dateAdd`, `am`.`pid`, `am`.`isIndex`, `amd`.`alias`, `amd`.`title`, `amd`.`text`, `amd`.`metaK`, `amd`.`metaD`, `amd`.`menu_id`, `amd`.`for_smi`, `amd`.`lang` FROM `a_menu` as `am` LEFT JOIN `a_menu_description` as `amd` ON (`am`.`id`=`amd`.`menu_id`) WHERE `am`.`id`="'.$id.'" AND `amd`.`lang`="'.config('lang.weblang').'" LIMIT 1';
		
		$query = DB::query($sql);

		if($query -> numRows > 0){
			if($query -> row -> pid != 0){
				$this->getParents($query -> row -> pid,' -> ');
			}
			
			if(!in_array($query -> row -> id,  json_decode(json_encode((array)$this->catList),TRUE))){
				array_push($this->catList,$query -> row);
			}
		}
		
		return null;
	}
	
	public function getCatList(){
		return $this -> catList;
	}
	
	


}