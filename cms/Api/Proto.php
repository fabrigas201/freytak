<?php namespace Cms\Api;

use Cms\DB;
use Cms\Controller;

class Proto{
	
	public $mainTbl;
	private $covers;
	
	public function __construct($tbl = false){
		$this -> mainTbl = $tbl;
		$this -> setMode('user');
	}

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

		if(isset($sqlParam['conditions'])){
			$conditions = $sqlParam['conditions'];
		}else{
			$conditions = '';
		}
		
		if(is_array($conditions)){
			$conditionsAdd = implode(' AND ',$conditions);
		}
		
		
		if(isset($sqlParam['letter'])){
			
			if($sqlParam['letter']=='num'){
				$letter = '0-9';
			}else{
				$letter = substr(preg_replace("/[^0-9a-zA-Zа-яА-ЯёЁ]+/",'',$sqlParam['letter']),0,1);
			}
		}
		
		
		if(isset($sqlParam['colforletter'])){
			$colforletter = $sqlParam['colforletter'];
		}else{
			$colforletter = 'name';
		}
		
		if(isset($sqlParam['category'])){
			$category = $sqlParam['category'];
		}else{
			$category = [];
		}
		
		if(isset($sqlParam['ids'])){
			$ids = $sqlParam['ids'];
		}
		
		//$colkey=$sqlParam['colkey'];
		//$colval=addslashes($sqlParam['colval']);
		if(isset($ids)) $limit = count($ids);

		if(isset($this->mod)){
			if(is_array($this->mod)){
				$mod = implode(' AND ', $this->mod);
				$mod = rtrim($mod, 'AND');
			}else{
				$mod = ' AND `mod`="'.$this->mod.'" ';
			}
		}
		
		
		$sql = 'SELECT *  FROM `'.$this->mainTbl.'` WHERE 1 ' .
			$this->sqlWhereAdd.
			(isset($this->mod) ? $mod :'').
			(isset($letter)&&$letter!='0-9'?' AND `'.$colforletter.'` LIKE "'.$letter.'%" ':'').
			(isset($letter)&&$letter=='0-9'?' AND `'.$colforletter.'` REGEXP "^['.$letter.']" ':'').
			($category?' AND FIND_IN_SET(category,"'.implode(',',$category).'")<>"0" ':'').
			//($colkey&&$colval?' AND `'.$colkey.'`="'.$colval.'"':'').
			(isset($conditionsAdd)?' AND '.$conditionsAdd:'').
			(isset($ids)?' AND id IN ('.implode(',',$ids).') ':'').''.
			(isset($orderbyAdd)?' ORDER BY '.$orderbyAdd.' ':'').
			($limit?'LIMIT '.$start.','.$limit:'');

		$result = DB::query($sql);
		

		$l = [];
		
		if($result -> numRows > 0){
			foreach($result -> rows as $tmp){
				
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
			
			
			/*cover*/
			if($ids && isset($this->imgTbl)){
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
			default:
				$this->submod='user';
				$this->sqlWhereAdd=' AND isHidden<>"1" ';
				break;
		}
	}
}