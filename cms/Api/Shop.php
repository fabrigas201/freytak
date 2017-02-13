<?php namespace Cms\Api;

use Cms\Api\Proto;
use Cms\DB;

// Класс от старой CMS, немного модифицированный.
class Shop extends Proto{
	public $mainTbl;
	public $imgTbl;
	public $filesTbl;
	public $linksTbl;
	public $producersTbl;
	public $ordersTbl;
	public $mode='';/*режим выборки записей
					admin	- все
					user	- все, кроме isHidden=1
					owner	- все, где uid=$_SESSION['uid']
				*/
	public $sqlWhereAdd;
	public $submod;
	public $db;
	
	public function __construct($tbl = false){
		$this->mainTbl = $tbl;
		$this -> db =  \Cms\Database::getInstance();
	}
	
	public function getById($id){
	
		$m = [
			'01'=>'января',
			'02'=>'февраля',
			'03'=>'марта',
			'04'=>'апреля',
			'05'=>'мая',
			'06'=>'июня',
			'07'=>'июля',
			'08'=>'августа',
			'09'=>'сентября',
			'10'=>'октября',
			'11'=>'ноября',
			'12'=>'декабря',
		];
			
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($id));
		if(!$id) return false;

		$description = [];

		// Достаем данные на разных языках
		$sql = 'SELECT and.alias, and.metaD, and.metaK, and.title, and.descrfull, and.descr, and.news_id, and.for_smi, and.lang FROM `a_news_description` as `and`  WHERE '.(is_numeric($id)?'`and`.`news_id`':'`and`.`alias`').'="'.$id.'" LIMIT 2';
			
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			foreach($query -> rows as $row){
				$description['langs'][$row -> lang] = $row;
			}
		}
		
		// Достаем общие данные
		$sql = 'SELECT `id`, `mod`, `pid`, `isHidden`, `inCalendar`, `inIndex`, `posi`, `dateAdd`, `eventDate`, `categories`, `images`, `datePub` FROM `a_news` WHERE `id`='.$id.' LIMIT 1';
		$base_query	= DB::query($sql);
		
		if($base_query -> numRows == 1){
			$result = $base_query -> row;
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
			if(isset($result -> data)){
				$result -> data = unserialize($result -> data);
			}
			
			if(!isset($result -> alias)){
				$result -> alias = $result -> id;
			}

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
		}
		return $description;
		
	}

	// Вытаскиваем все новости
	public function getList($sqlParam = []){
		
		
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
		
		if(isset($this->mod)){
			if(is_array($this->mod)){
				$mod = implode(' AND ', $this->mod);
				$mod = rtrim($mod, 'AND');
			}else{
				$mod = ' AND `mod`="'.$this->mod.'" ';
			}
		}
		
		
		
		$sql = 'SELECT `an`.`id`, `an`.`mod`, `an`.`isHidden`, `an`.`inCalendar`, `an`.`inIndex`, `an`.`posi`, `an`.`dateAdd`, `an`.`datePub`, `and`.`news_id`, `and`.`title`, `and`.`lang` FROM `'.$this->mainTbl.'` as `an` LEFT JOIN `a_news_description` as `and` ON `an`.`id`=`and`.`news_id` WHERE `and`.`lang`="'.config('lang.base').'"  '.(isset($this->mod) ? $mod :'').' '.(isset($orderbyAdd)?' ORDER BY '.$orderbyAdd.' ':'').(isset($limit)?'LIMIT '.$start.','.$limit:'');;
		
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			
			
			foreach($query -> rows as $tmp){
				
				if(isset($tmp -> data)){
					$tmp -> data = unserialize($tmp -> data);
				}
				if(isset($tmp -> images)){
					$tmp -> images = unserialize($tmp -> images);
				}
				if(!isset($tmp -> alias)){
					$tmp -> alias = $tmp -> id;
				}
				
				$l[$tmp -> id] = $tmp;
				$ids[] = $tmp -> id;
			}

			if(count($ids) && isset($this->imgTbl)){
				$sql = 'SELECT * FROM '.$this->imgTbl.' WHERE `isCover`="1" AND `mod`="'.(isset($this->mod) ? $this->mod : addslashes($_GET['mod'])).'" AND `modid` IN ('.implode(',',$ids).')';
	
				$result = DB::query($sql);
				
				if($result -> numRows > 0){
					$this->covers = [];
					foreach($result -> rows as $tmp){
						$tmp -> ext = explode('.',$tmp -> name);
						$tmp -> ext = array_pop($tmp -> ext);
						$l[$tmp -> modid] -> cover = $tmp;
					}
				}
			}
			
			return $l;
		}
		return null;
	}
	
	// Вытаскиваем все слайды
	public function getListImages($sqlParam = []){
		
		
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
		
		if(isset($this->mod)){
			if(is_array($this->mod)){
				$mod = implode(' AND ', $this->mod);
				$mod = rtrim($mod, 'AND');
			}else{
				$mod = ' AND `mod`="'.$this->mod.'" ';
			}
		}
		
		
		
		$sql = 'SELECT `an`.`id`, `an`.`mod`,  `an`.`posi`, `an`.`dateAdd`, `an`.`datePub`, `and`.`images_id`, `and`.`title`, `and`.`lang` FROM `'.$this->mainTbl.'` as `an` LEFT JOIN `a_images_description` as `and` ON `an`.`id`=`and`.`images_id` WHERE `and`.`lang`="'.config('lang.base').'"  '.(isset($this->mod) ? $mod :'').' '.(isset($orderbyAdd)?' ORDER BY '.$orderbyAdd.' ':'').(isset($limit)?'LIMIT '.$start.','.$limit:'');;
		
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			
			
			foreach($query -> rows as $tmp){
				
				if(isset($tmp -> data)){
					$tmp -> data = unserialize($tmp -> data);
				}
				if(isset($tmp -> images)){
					$tmp -> images = unserialize($tmp -> images);
				}
				if(!isset($tmp -> alias)){
					$tmp -> alias = $tmp -> id;
				}
				
				$l[$tmp -> id] = $tmp;
				$ids[] = $tmp -> id;
			}

			
			
			
			if(count($ids) && isset($this->imgTbl)){
				$sql = 'SELECT * FROM '.$this->imgTbl.' WHERE `isCover`="1" AND `mod`="'.(isset($this->mod) ? $this->mod : addslashes($_GET['mod'])).'" AND `modid` IN ('.implode(',',$ids).')';
	
	
	
	
				$result = DB::query($sql);
				
				if($result -> numRows > 0){
					$this->covers = [];
					foreach($result -> rows as $tmp){
						$tmp -> ext = explode('.',$tmp -> name);
						$tmp -> ext = array_pop($tmp -> ext);
						$l[$tmp -> modid] -> cover = $tmp;
					}
				}
			}
			
			return $l;
		}
		return null;
	}
	
	public function getByImagesId($id){
	
		$m = [
			'01'=>'января',
			'02'=>'февраля',
			'03'=>'марта',
			'04'=>'апреля',
			'05'=>'мая',
			'06'=>'июня',
			'07'=>'июля',
			'08'=>'августа',
			'09'=>'сентября',
			'10'=>'октября',
			'11'=>'ноября',
			'12'=>'декабря',
		];
			
		$id = preg_replace('/[^0-9a-z_-]+/i','',trim($id));
		if(!$id) return false;

		$description = [];

		// Достаем данные на разных языках
		$sql = 'SELECT and.alias, and.metaD, and.metaK, and.title, and.descrfull, and.descr, and.images_id, and.lang FROM `a_images_description` as `and`  WHERE '.(is_numeric($id)?'`and`.`images_id`':'`and`.`alias`').'="'.$id.'" LIMIT 2';
			
		$query = DB::query($sql);
		
		if($query -> numRows > 0){
			foreach($query -> rows as $row){
				$description['langs'][$row -> lang] = $row;
			}
		}
		
		// Достаем общие данные
		$sql = 'SELECT `id`, `mod`, `posi`, `dateAdd`,  `images`, `datePub` FROM `a_images` WHERE `id`='.$id.' LIMIT 1';
		$base_query	= DB::query($sql);
		
		if($base_query -> numRows == 1){
			$result = $base_query -> row;
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
			if(isset($result -> data)){
				$result -> data = unserialize($result -> data);
			}
			
			if(!isset($result -> alias)){
				$result -> alias = $result -> id;
			}

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
		}
		return $description;
		
	}

	
	public function getCover($id){
		if(!$this->imgTbl) return false;

		$id=(int)$id;
		if(!$id) return false;

		$sql='SELECT * FROM '.$this->imgTbl.' WHERE modid="'.$id.'" AND isCover="1" LIMIT 1';
		$result = DB::query($sql);
		
		if($result -> numRows == 1){
			$result -> row -> ext = explode('.',$result -> row -> name);
			$result -> row -> ext = array_pop($result -> row -> ext);
			return $result -> row;
		}
	}

	public function getImages($id){
		if(!$this->imgTbl) return false;

		$id=(int)$id;
		if(!$id) return false;

		$sql='SELECT * FROM '.$this->imgTbl.' WHERE '.(isset($this->mod)?' `mod`="'.$this->mod.'" AND ':'').' modid="'.$id.'" ORDER BY posi ASC, id ASC';
		$query = DB::query($sql);	
		
		if($query -> numRows > 0){
			$i=0;
			
			foreach($query -> rows as $tmp){
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
		return null;
	}


	public function addEditImagesInfo($imagesInfo , $images = []){

		if(!$this -> imgTbl) return false;
		
		if(!is_array($images)){
			$images = [$images];
		}

		if(isset($imagesInfo['newCoverId'])){
			$curCoverId = $imagesInfo['newCoverId'];
		}else{
			$curCoverId = false;
		}
		
		$delIds = [];

		//dd_die($imagesInfo['newCoverId']);
		
		//Удаляем существующие картинки
		if(isset($imagesInfo['imgdelIds']) && is_array($imagesInfo['imgdelIds'])){
		
			foreach ($imagesInfo['imgdelIds'] as $id => $path) {
				
				if($curCoverId==$id) $curCoverId = false;
				
				$path = rtrim(rtrim(SITE_DIR, '/'). str_replace(array('../', '..\\', '..'), '', $path), '/');

				if (is_file($path)) {
					unlink($path);
				}
				$delIds[] = $id;
			}
		
			//удаляем картинки из базы
			if(count($delIds)){
				DB::deleteIn($this->imgTbl, ['id' => $delIds], count($delIds));
			}
		}

		//действия с Cover
		/*
			если нет картинки с Cover (только загрузили или удалили картинку с Cover)
			ставим Cover
		*/
	
		if($curCoverId == false){
			if(isset($images[0])){
				$images[0] -> isCover = 1;
			}
		} 

		//меняем Cover если надо
		if($curCoverId != false ){
			// Сбрасываем все Cover
			DB::update($this->imgTbl, ['isCover'=>'0'], ['modid'=>$imagesInfo['modid']]);
			// Обновляем Cover
			DB::update($this->imgTbl, ['isCover'=>'1'], ['id'=>$curCoverId]);
		}

		//добавляем в базу новыe загруженные картинки
		if(count($images)){
			foreach($images as $file){

				list($width, $height) = getimagesize($file -> path);
				
				if(isset($file -> w)){
					$width = $file -> w;
				}
				if(isset($file -> h)){
					$height = $file -> h;
				}
	        	
				$data = [
					'name' 		=> $file -> name,
					'size'		=> $file -> size,
					'w'			=> $width,
					'h'			=> $height,
					'path'		=> $imagesInfo['path'],
					'isCover'	=> (isset($file -> isCover)?1:0),
					'modid'		=> $imagesInfo['modid'],
					'mod'		=> $imagesInfo['mod'],
				];
				
				DB::insert($this->imgTbl , $data);

			}
		}
	}


	public function getCountRec($sqlParam = []){
		
		if(isset($sqlParam['colforletter'])){
			$colforletter = $sqlParam['colforletter'];
		}
		
		if(isset($sqlParam['category'])){
			$category = $sqlParam['category'];
		}

		if(isset($sqlParam['conditions'])){
			$conditions = $sqlParam['conditions'];
		}else{
			$conditions = '';
		}
		

		if(is_array($conditions)){
			$conditionsAdd='';
			foreach($conditions as $v){
				$conditionsAdd.=' AND '.$v.' ';
			}
		}
		
		if(isset($this->mod)){
			if(is_array($this->mod)){
				$mod = ' AND `mod` IN ("'.implode(',',$this->mod).'") ';
			}else{
				$mod = ' AND `mod`="'.$this->mod.'" ';
			}
		}
		

		$sql = 'SELECT COUNT(*) as count FROM '.$this->mainTbl.' WHERE 1'.
				$this->sqlWhereAdd.
				($this->mod ? $mod :'').
				(isset($category)?' AND FIND_IN_SET(category,"'.implode(',',$category).'")<>"0"':'').
				(isset($conditionsAdd)?$conditionsAdd:'').
			' ';
		
		$result = DB::query($sql);
		return $result -> row -> count;

	}


	public function delGoodsById($id){
		if(is_array($id)){
			foreach($id as $k=>$v){
				if(intval($v)) $ids[]=$v;
			}
		}else{
			$id=(int)$id;
			$ids[]=$id;
		}
		if(!$ids) return false;

		
		
		$result = DB::deleteIn($this->mainTbl, ['id' => $ids], count($ids));

		if($result == count($ids)){
			//удаляем картинки
			$this->delImages($ids);
		}

		return;
	}


	public function delImages($modid){
		if(!$this->imgTbl) return false;

		if(is_array($modid)){
			foreach($modid as $k=>$v){
				if(intval($v)) $modids[]=$v;
			}
		}else{
			$modid=(int)$modid;
			$modids[]=$modid;
		}
		if(!$modid) return false;

		$sql='SELECT * FROM '.$this->imgTbl.' WHERE '.
				($this->mod?' `mod`="'.$this->mod.'" AND ':'').
				 'modid IN ('.implode(',',$modids).')';
		$result = $this -> db -> query($sql);

		if($result -> numRows > 0){
			$imageIds = [];
			foreach($result -> rows as $l){
				if(is_file(SITE_DIR.$l->path.$l->name)){
					unlink(SITE_DIR.$l->path.$l->name);
				}
				if(is_file(SITE_DIR.$l->path.'tm_'.$l->name)){
					unlink(SITE_DIR.$l->path.'tm_'.$l->name);
				}
				if(is_file(SITE_DIR.$l->path.'tm2_'.$l->name)){
					unlink(SITE_DIR.$l->path.'tm2_'.$l->name);
				}
				$imageIds[]=$l->id;
			}

			
			DB::deleteIn($this->imgTbl, ['id' => $imageIds], count($imageIds));
		}

		return;
	}

}