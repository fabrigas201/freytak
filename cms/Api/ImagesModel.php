<?php namespace Cms\Api;

use Cms\DB;

class ImagesModel{
	

	public $image_table;
	public $mod;

	public function getCover($id){
		if(!$this->image_table) return false;

		$id=(int)$id;
		if(!$id) return false;

		$sql='SELECT * FROM '.$this->image_table.' WHERE modid="'.$id.'" AND isCover="1" LIMIT 1';
		$result = DB::query($sql);
		
		if($result -> numRows == 1){
			$result -> row -> ext = explode('.',$result -> row -> name);
			$result -> row -> ext = array_pop($result -> row -> ext);
			return $result -> row;
		}
	}

	public function getImages($id){
		if(!$this->image_table) return false;

		$id=(int)$id;
		if(!$id) return false;

		$sql='SELECT * FROM '.$this->image_table.' WHERE '.(isset($this->mod)?' `mod`="'.$this->mod.'" AND ':'').' modid="'.$id.'" ORDER BY posi ASC, id ASC';
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

		if(!$this -> image_table) return false;
		
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
				DB::deleteIn($this->image_table, ['id' => $delIds], count($delIds));
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
			DB::update($this->image_table, ['isCover'=>'0'], ['modid'=>$imagesInfo['modid']]);
			// Обновляем Cover
			DB::update($this->image_table, ['isCover'=>'1'], ['id'=>$curCoverId]);
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
				
				DB::insert($this->image_table , $data);
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