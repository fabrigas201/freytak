<?php namespace Cms\Api;

use Cms\DB;
use Cms\Libs\Trees;

class Articles extends Trees{
	public $mainTbl;
	public $sqlWhereAdd;
	public $imgTbl;
	
	public function getById($id){
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($id));
		if(!$id) return false;
		
		$description = [];
		
		// Достаем данные на разных языках
		$sql = 'SELECT amd.alias, amd.metaD, amd.metaK, amd.title, amd.title as `fname`, amd.text, amd.description, amd.menu_id, amd.for_smi, amd.lang FROM `a_menu_description` as `amd`  WHERE '.(is_numeric($id)?'`amd`.`menu_id`':'`amd`.`alias`').'="'.$id.'" LIMIT 2';
			
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			foreach($query -> rows as $row){
				$description['langs'][$row -> lang] = $row;
			}
		}
		
		// Достаем общие данные
		$sql = 'SELECT am.id, am.typeMenu,  am.pid, am.images, am.posi, am.isHidden, am.dateAdd, am.datePub, am.isIndex, am.inIndex, am.in_sitemap  FROM `a_menu` as `am` WHERE `am`.`id`='.$id.' LIMIT 1';
		$base_query	= DB::query($sql);
		
		$result = $base_query -> row;
		
		if(isset($this->imgTbl)){
			//$l->cover=$this->getCover($l->id);
			$result -> images = $this -> getImages($result -> id);
			if(isset($result -> images['cover'])){
				$result -> cover = $result -> images['cover'];
				//не удалять тут т.к. пропадет в админке в карточке
				//unset($l->images['cover']);
			}
		}elseif(isset($result -> images)){
			$result -> images = unserialize($result -> images);
		}
		
		
		
		$description['base'] = $result;
		
		return $description;
	}
	
	public function getByIdForMenu($sqlParam = []){
		$m = __('date');

		if(!isset($sqlParam['id']) || empty($sqlParam['id'])){
			return false;
		}
		
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($sqlParam['id']));
		
		if(isset($sqlParam['start'])){
			$start = (int)$sqlParam['start'];
		}else{
			$start = 0;
		}
		
		if(isset($sqlParam['limit'])){
			$limit = (int)$sqlParam['limit'];
		}
		
		if(isset($sqlParam['date'])){
			$date = $sqlParam['date'];
		}
		
		
		/* if(isset($date)){
			list($year,$month,$day)=explode('_',$date);
			$year=(int)$year;
			$month=(int)$month;
			$day=(int)$day;
			//%c 	ћес¤ц, число (1..12)
			//%e 	ƒень мес¤ца, число (0..31)
			$date=' DATE_FORMAT(dateAdd,"%Y'.($month?'%c'.($day?'%e':''):'').'")='.$year.($month?$month.($day?$day:''):'');
		} */
		
		
		
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

		if(isset($sqlParam['conditions'])){
			$conditions = $sqlParam['conditions'];
		}else{
			$conditions = '';
		}
		
		if(is_array($conditions)){
			$conditionsAdd = implode(' AND ',$conditions);
		}
		
		
		$sql_menu = 'SELECT id, alias, name, typeMenu , metaD, metaK FROM '.PREFIX.'_menu WHERE '.(is_numeric($id) ? 'id' : 'alias').'="'.$id.'" ';
		$menu = DB::query($sql_menu);

		$data = [];

		if($menu -> numRows > 0){
			
			$data['alias'] = $menu -> row -> alias;
			$data['name'] =  $menu -> row -> name;
			$data['type'] =  $menu -> row -> typeMenu;
			$data['metaD'] =  $menu -> row -> metaD;
			$data['metaK'] =  $menu -> row -> metaK;
			$data['menu_id'] = $menu -> row -> id;
			
			$sql = 'SELECT * FROM '.PREFIX.'_news WHERE categories="'.$menu -> row -> id. '"' ;
		}else{
			$sql = 'SELECT * FROM '.PREFIX.'_news WHERE '.(is_numeric($id) ? 'id' : 'alias').'="'.$id.'" ';
		}

		$sql .=
				
			$this->sqlWhereAdd.
			(isset($this->mod) ? ' AND `mod`="'.$this->mod.'" ' : '').
			(isset($conditionsAdd)?' AND '.$conditionsAdd:'').
			(isset($ids)?' AND id IN ('.implode(',',$ids).') ':'').''.
			(isset($orderbyAdd)?' ORDER BY '.$orderbyAdd.' ':'').
			//(isset($date)?' AND '.$date.' ':'').
			(isset($limit)?'LIMIT '.$start.','.$limit:'');

		$result = DB::query($sql);

		
		
		$data['result'] =  [];

		if($result -> numRows > 0){
			if($this -> getCountByIdForMenu($sqlParam) == 1){
				
				if(isset($this->imgTbl)){
					//$l->cover=$this->getCover($l->id);
					$result -> row -> images = $this -> getImages($result -> row -> id);
					if(isset( $result -> row -> images['cover'])){
						$result -> row -> cover =  $result -> row -> images['cover'];
						//не удалять тут т.к. пропадет в админке в карточке
						//unset($l->images['cover']);
					}
				}elseif(isset($l -> images)){
					 $result -> row -> images = unserialize( $result -> row -> images);
				}

				$data['result'][0] = $result -> row;
				
				
			}else if($this -> getCountByIdForMenu($sqlParam) > 1){
				
				foreach($result -> rows as $l){
					
					$l -> created_at = $l -> dateAdd;
					
					if (isset($l -> dateAdd) && $l -> dateAdd != '0000-00-00 00:00:00'){
						//$l -> created_at = $l -> dateAdd;
						$dateAdd = explode(' ',$l -> dateAdd);
						if(isset($dateAdd[0])){
							$first_data = explode('-', $dateAdd[0]);
							$l -> dateAdd = implode(' ',[$first_data[2],$m[$first_data[1]],$first_data[0]]);
						}else{
							$l -> dateAdd = $dateAdd[0];
						}
							
						//$dateAdd = explode(" ",$l -> dateAdd);
						//$dateAdd_d = explode("-",$dateAdd[0]);
						//$dateAdd_t = explode(":",$dateAdd[1]);
						//$l -> dateAdd = $m[$dateAdd_d[1]]." ".$dateAdd_d[2]." ".$dateAdd_d[0]." ".$dateAdd_t[0].":".$dateAdd_t[1];
					}
					
					
					if(isset($l -> data)){
						$l -> data = unserialize($l -> data);
					}
					
					if(!isset($l -> alias)){
						$l -> alias = $l -> id;
					}
					
					
					if(isset($this->imgTbl)){
						//$l->cover=$this->getCover($l->id);
						$l -> images = $this -> getImages($l -> id);
						if(isset($l -> images['cover'])){
							$l -> cover = $l -> images['cover'];
							//не удалять тут т.к. пропадет в админке в карточке
							//unset($l->images['cover']);
						}
					}elseif(isset($l -> images)){
						$l -> images = unserialize($l -> images);
					}
					
					if(isset($this -> filesTbl)){
						$l -> files = $this -> getFiles($l -> id);
					}
					
					$data['result'][] = $l;
				}
			}

		}
		return $data;
	}
	
	public function getCountByIdForMenu($sqlParam = []){
		

		if(!isset($sqlParam['id']) || empty($sqlParam['id'])){
			return false;
		}
		
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($sqlParam['id']));
		

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

		if(isset($sqlParam['conditions'])){
			$conditions = $sqlParam['conditions'];
		}else{
			$conditions = '';
		}
		
		if(is_array($conditions)){
			$conditionsAdd = implode(' AND ',$conditions);
		}
		

		$sql_menu = 'SELECT id, alias, name, typeMenu FROM '.PREFIX.'_menu WHERE '.(is_numeric($id) ? 'id' : 'alias').'="'.$id.'" ';
		$menu = DB::query($sql_menu);
		
		if($menu -> numRows > 0){
			
			$sql = 'SELECT COUNT(*) as count FROM '.PREFIX.'_news WHERE categories='.$menu -> row-> id. ' ' ;
			
		}else{
			$sql = 'SELECT  COUNT(*) as count FROM '.PREFIX.'_news WHERE '.(is_numeric($id) ? 'id' : 'alias').'="'.$id.'" ';
		}

		$sql .= 
			$this->sqlWhereAdd.
			(isset($this->mod) ? ' AND `mod`="'.$this->mod.'" ' :'').
			(isset($conditionsAdd)?' AND '.$conditionsAdd:'').
			(isset($orderbyAdd)?' ORDER BY '.$orderbyAdd.' ':'');

			
			//dd_die($sql);
		$result = DB::query($sql);
		return $result -> row -> count;
	}
	
	public function getImages($id){
		if(!$this->imgTbl) return false;

		$id=(int)$id;
		if(!$id) return false;

		
		
		
		$q='SELECT * FROM '.$this->imgTbl.' WHERE 1 '.
				(isset($this -> mod) ? ' AND `mod`="'.$this->mod.'" ' : '').
				' AND modid="'.$id.'"
			ORDER BY posi ASC, id ASC';
			//dd_die($q);
		$r=@mysql_query($q) or die(mysql_error());
		if(mysql_num_rows($r)){
			$i=0;
			while($tmp=mysql_fetch_object($r)){
				$tmp->ext=explode('.',$tmp->name);
				$tmp->ext=array_pop($tmp->ext);

				if($tmp->isCover)
				//if($i==0)
					$l['cover']=$tmp;
				else
					$l[]=$tmp;

				$i++;
			}

			return $l;
		}
	}
	
	public function getList($sqlParam = []){
		
		if(!is_array($sqlParam)){
			$sqlParam = [$sqlParam];
		}
		
		if(isset($sqlParam['start'])){
			$start=(int)$sqlParam['start'];
		}else{
			$start=0;
		}
		
		if(isset($sqlParam['limit'])){
			$limit=(int)$sqlParam['limit'];
		}else{
			$limit = 20;
		}
		
		if(isset($sqlParam['orderby'])){
			$orderby=(int)$sqlParam['orderby'];
		}else{
			$orderby = '';
		}
		
		if(is_array($orderby)){
			$orderbyAdd='';
			$v=strtolower($v)=='asc'?'asc':'desc';
			foreach($orderby as $k=>$v){
				$orderbyAdd.='`'.$k.'` '.$v.',';
			}
			$orderbyAdd=trim($orderbyAdd,',');
		}else{
			$orderbyAdd='posi asc';
		}
		
		
		if(isset($sqlParam['conditions'])){
			$conditions = $sqlParam['conditions'];
		}else{
			$conditions ='';
		}
		

		if(is_array($conditions)){
			$conditionsAdd='';
			foreach($conditions as $v){
				$conditionsAdd.=' AND '.$v.' ';
			}
		}
		
		if(isset($sqlParam['letter'])){
			if($sqlParam['letter']=='num'){
				$letter='0-9';
			}else{
				$letter = substr(preg_replace("/[^0-9a-zA-Zа-яА-ЯЁё]+/",'',$sqlParam['letter']),0,1);
			}
		}else{
			$letter = '';
		}
		
		
		if(isset($sqlParam['colforletter'])){
			$colforletter = $sqlParam['colforletter'];
		}else{
			$colforletter ='';
		}
		
		if(isset($sqlParam['category'])){
			$category = $sqlParam['category'];
		}
		
		if(isset($sqlParam['ids'])){
			$ids = $sqlParam['ids'];
		}
		
		if(isset($sqlParam['date'])){
			$date = $sqlParam['date'];
		}
		
		if(isset($date)){
			list($year,$month,$day)=explode('_',$date);
			$year	=(int)$year;
			$month	=(int)$month;
			$day	=(int)$day;
			$date	=' DATE_FORMAT(dateAdd,"%Y'.($month?'%c'.($day?'%e':''):'').'")='.$year.($month?$month.($day?$day:''):'');
		}
		
		$sql='SELECT  `am`.`id`,`am`.`pid`, `am`.`posi`, `am`.`isIndex`, `am`.`typeMenu`, `amd`.`title`,`amd`.`alias`, `amd`.`menu_id`, `amd`.`lang` FROM `a_menu` as `am` LEFT JOIN `a_menu_description` as `amd` ON `am`.`id`=`amd`.`menu_id` WHERE 1 '.
			$this->sqlWhereAdd.
			($letter&&$letter!='0-9'?' AND `'.$colforletter.'` LIKE "'.$letter.'%" ':'').
			($letter&&$letter=='0-9'?' AND `'.$colforletter.'` REGEXP "^['.$letter.']" ':'').
			(isset($category)?' AND FIND_IN_SET("'.$category.'",category)<>"0" ':'').
			//($colkey&&$colval?' AND `'.$colkey.'`="'.$colval.'"':'').
			(isset($conditionsAdd)?$conditionsAdd:'').
			(isset($date)?' AND '.$date.' ':'').
			(isset($ids)?' AND id IN ('.implode(',',$ids).') ':'').
			($orderbyAdd?' ORDER BY '.$orderbyAdd.' ':'').
			($limit?'LIMIT '.$start.','.$limit:'');
	
		$result = DB::query($sql);

		if($result -> numRows > 0){
			$ids='';
			/* foreach($result -> rows as $tmp){
				$tmp -> alias = $tmp -> alias ? $tmp -> alias : $tmp -> id;
				$tmp -> images = unserialize($tmp -> images);
				$l[$tmp -> id] = $tmp;
			} */
			return $result -> rows;
		}
	}
	
	public function getCountRec($sqlParam=array()){
		
		if(isset($sqlParam['letter'])){
			if($sqlParam['letter']=='num'){
				$letter='0-9';
			}else{
				$letter = substr(preg_replace("/[^0-9a-zA-Zа-яА-ЯЁё]+/",'',$sqlParam['letter']),0,1);
			}
		}else{
			$letter = '';
		}
		
		if(isset($sqlParam['colforletter'])){
			$colforletter = $sqlParam['colforletter'];
		}else{
			$colforletter ='';
		}
		
		if(isset($sqlParam['category'])){
			$category = $sqlParam['category'];
		}
		
		if(isset($sqlParam['conditions'])){
			$conditions = $sqlParam['conditions'];
		}
		
		if(is_array($conditions)){
			$conditionsAdd='';
			foreach($conditions as $v){
				$conditionsAdd.=' AND '.$v.' ';
			}
		}
		
		if(isset($sqlParam['date'])){
			$date = $sqlParam['date'];
		}
		
		
		if(isset($date)){
			list($year,$month,$day)=explode('_',$date);
			$year=(int)$year;
			$month=(int)$month;
			$day=(int)$day;
			
			$date=' DATE_FORMAT(dateAdd,"%Y'.($month?'%c'.($day?'%e':''):'').'")='.$year.($month?$month.($day?$day:''):'');
		}
		
		$sql =' SELECT COUNT(*) as count FROM '.$this->mainTbl.' WHERE 1'.
			$this->sqlWhereAdd.
			($letter&&$letter!='0-9'?' AND `'.$colforletter.'` LIKE "'.$letter.'%" ':'').
			($letter&&$letter=='0-9'?' AND `'.$colforletter.'` REGEXP "^['.$letter.']" ':'').
			(isset($category)?' AND FIND_IN_SET("'.$category.'",category)<>"0"':'').
			(isset($conditionsAdd)?$conditionsAdd:'').
			(isset($date)?' AND '.$date.' ':'').
			' ';
		
		
		$result = DB::query($sql);
		return $result -> row -> count;
	}
	
	
	public function getByContactForMenu($sqlParam = []){
		$m = __('date');

		if(!isset($sqlParam['id']) || empty($sqlParam['id'])){
			return false;
		}
		
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($sqlParam['id']));
		
		
		
		
		
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

		if(isset($sqlParam['conditions'])){
			$conditions = $sqlParam['conditions'];
		}else{
			$conditions = '';
		}
		
		if(is_array($conditions)){
			$conditionsAdd = implode(' AND ',$conditions);
		}
		
		
		$sql_menu = 'SELECT id, alias, name, typeMenu, metaK, metaD FROM '.PREFIX.'_menu WHERE '.(is_numeric($id) ? 'id' : 'alias').'="'.$id.'" ';
		$menu = DB::query($sql_menu);
		//dd_die($menu);
		$data = [];

		if($menu -> numRows > 0){
			
			$data['alias'] = $menu -> row -> alias;
			$data['name'] =  $menu -> row -> name;
			$data['type'] =  $menu -> row -> typeMenu;
			$data['menu_id'] = $menu -> row -> id;
			$data['metaD'] =  $menu -> row -> metaD;
			$data['metaK'] =  $menu -> row -> metaK;
			
			$sql = 'SELECT * FROM '.PREFIX.'_other WHERE category='.$menu -> row -> id. ' ' ;
		}else{
			$sql = 'SELECT * FROM '.PREFIX.'_other WHERE '.(is_numeric($id) ? 'id' : 'alias').'="'.$id.'" ';
		}
		

		$sql .=
				
			$this->sqlWhereAdd.
			(isset($conditionsAdd)?' AND '.$conditionsAdd:'').
			(isset($ids)?' AND id IN ('.implode(',',$ids).') ':'').''.
			(isset($orderbyAdd)?' ORDER BY '.$orderbyAdd.' ':'').
			(isset($limit)?'LIMIT '.$start.','.$limit:'');

			
		//dd_die($sql);
		$result = DB::query($sql);

		
		$data['result'] =  [];

		if($result -> numRows > 0){
				
			if(isset($this->imgTbl)){
				//$l->cover=$this->getCover($l->id);
				$result -> row -> images = $this -> getImages($result -> row -> id);
				if(isset( $result -> row -> images['cover'])){
					$result -> row -> cover =  $result -> row -> images['cover'];
					//не удалять тут т.к. пропадет в админке в карточке
					//unset($l->images['cover']);
				}
			}elseif(isset($result  -> row -> images)){
				 $result -> row -> images = unserialize( $result -> row -> images);
			}
			
			if(isset($result -> row -> data)){
				$result -> row -> data = unserialize( $result -> row -> data);
			}

			$data['result'][0] = $result -> row;

		}
		return $data;
	}
	
	public function setMode($mod){
		switch($mod){
			case'user':
				$this->submod='user';
				$this->sqlWhereAdd=' AND isHidden<>"1" ';
				break;
			case'owner':
				$this->submod='owner';
				$this->sqlWhereAdd=' AND uid="'.$_SESSION['uid'].'" ';
				break;
			case'admin':
				$this->submod='admin';
				$this->sqlWhereAdd='';
				break;
			default://не залогинен
				$this->submod='guest';
				$this->sqlWhereAdd=' AND isHidden<>"1" ';
				$this->sqlWhereAdd.=' AND onlylog<>"1" ';
				break;
		}
	}
	
	
	public function delTree($id){
		if(!is_numeric($id)) return;
		
		$articles = new Articles(PREFIX.'_menu');
		$articles -> mainTbl = PREFIX.'_menu';
		$articles -> getTree($id);
		
		DB::delete('a_menu', ['id' => $id]);
		DB::delete('a_menu_description', ['menu_id' => $id]);
		
		if(count($articles -> catList)){
			foreach($articles -> catList as $item){
				DB::delete('a_menu', ['id' => $item->id]);
			}
		}
		
		$articles -> catList = [];
	}
}
