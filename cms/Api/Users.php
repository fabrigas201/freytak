<?php namespace Cms\Api;


use Cms\DB;

class Users{
	public $mainTbl;
	public $sqlWhereAdd;
	
	public function __construct($tbl = null){
		$this -> mainTbl = $tbl;
	}
	
	public function getById($id){
		$id = (int)$id;
		if(!$id) return false;
		
		$sql = 'SELECT * FROM '.$this->mainTbl.' WHERE uid="'.$id.'" '.$this->sqlWhereAdd. ' LIMIT 1';
		
		$result = DB::query($sql);
		if($result -> numRows == 1){
			$l = $result -> row;
			$l -> user_avatar = unserialize($l -> user_avatar);
			return $l;
		}
		return null;
	}
	
	public function getList($sqlParam=array()){
		if(isset($sqlParam['start'])){
			$start = (int)$sqlParam['start'];
		}else{
			$start = 0;
		}
		
		if(isset($sqlParam['limit'])){
			$limit = (int)$sqlParam['limit'];
		}else{
			$limit = 10;
		}
		
		if(isset($sqlParam['orderby'])){
			$orderby = $sqlParam['orderby'];
		}else{
			$orderby = '';
		}

		$ascdesc = strtolower($sqlParam['ascdesc']) == 'asc' ? 'asc' : 'desc';
		
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
				$letter = substr(preg_replace("/[^0-9a-zA-Zà-ÿÀ-ß¸¨]+/",'',$sqlParam['letter']),0,1);
			}
		}
		
		
		if(isset($sqlParam['colforletter'])){
			$colforletter = $sqlParam['colforletter'];
		}else{
			$colforletter = 'name';
		}
		
		if(isset($sqlParam['category'])){
			$category = $sqlParam['category'];
		}
		
		if(isset($sqlParam['ids'])){
			$ids = $sqlParam['ids'];
		}

		if(isset($sqlParam['colkey'])){
			$colkey = $sqlParam['colkey'];
		}else{
			$colkey = '';
		}
		if(isset($sqlParam['colval'])){
			$colval = addslashes($sqlParam['colval']);
		}else{
			$colval = '';
		}

		if(isset($ids)) $limit = count($ids);
				
		$sql = 'SELECT * FROM `'.$this->mainTbl.'` WHERE 1 '.
			$this->sqlWhereAdd.
			(isset($letter)&&$letter!='0-9'?' AND `'.$colforletter.'` LIKE "'.$letter.'%" ':'').
			(isset($letter)&&$letter=='0-9'?' AND `'.$colforletter.'` REGEXP "^['.$letter.']" ':'').
			(isset($category)?' AND FIND_IN_SET("'.$category.'",category)<>"0" ':'').
			(isset($colkey)&&$colval?' AND `'.$colkey.'`="'.$colval.'"':'').
			(isset($orderby)?'ORDER BY '.$orderby.' '.$ascdesc.' ':'').
			'LIMIT '.$start.','.$limit;
				
		$result = DB::query($sql);
		
		if($result -> numRows > 0){
			foreach($result -> rows as $tmp){
				$tmp->user_avatar=unserialize($tmp->user_avatar);
				$l[$tmp->uid]=$tmp;
			}
		}
		return $l;
	}
	
	public function delById($id){
		$id=(int)$id;
		if(!$id) return false;
		
		DB::delete($this->mainTbl, ['uid' => $id]);
		
	}
	
	public function getCountRec($sqlParam=array()){
		
		if(isset($sqlParam['letter'])){
			
			if($sqlParam['letter']=='num'){
				$letter = '0-9';
			}else{
				$letter = substr(preg_replace("/[^0-9a-zA-Zà-ÿÀ-ß¸¨]+/",'',$sqlParam['letter']),0,1);
			}
		}
		
		if(isset($sqlParam['colforletter'])){
			$colforletter = $sqlParam['colforletter'];
		}else{
			$colforletter = 'name';
		}
		
		if(isset($sqlParam['category'])){
			$category = $sqlParam['category'];
		}
		
		if(isset($sqlParam['colkey'])){
			$colkey = $sqlParam['colkey'];
		}else{
			$colkey = '';
		}
		
		if(isset($sqlParam['colval'])){
			$colval = addslashes($sqlParam['colval']);
		}else{
			$colval = '';
		}
		
		
		
		$sql = 'SELECT COUNT(*) as `count` FROM '.$this->mainTbl.'	 WHERE 1'.
			$this->sqlWhereAdd.
			(isset($letter)&&$letter!='0-9'?' AND `'.$colforletter.'` LIKE "'.$letter.'%" ':'').
			(isset($letter)&&$letter=='0-9'?' AND `'.$colforletter.'` REGEXP "^['.$letter.']" ':'').
			(isset($category)?' AND FIND_IN_SET("'.$category.'",category)<>"0"':'').
			(isset($colkey)&&$colval?' AND `'.$colkey.'`="'.$colval.'"':'').
		' ';

		$result = DB::query($sql);
		
		if($result -> numRows == 1){
			return $result -> row -> count;
		}
	}
}
