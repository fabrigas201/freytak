<?php namespace Cms;

use Cms\Database;
use Cms\Exception\BaseException;

class DB {
	
	public static function query($sql){
		return Database::getInstance() -> query($sql);
	}

	public static function getLastId($sql) {
		$result = Database::getInstance() ;
		if($result -> query($sql)){
			return $result -> getLastId();
		}
	}
	
	public static function update($table, $data, $where, $params=[]){
		return Database::getInstance() -> update($table, $data, $where, $params=[]);
	}
	
	public static function insert($table, $data){
		return Database::getInstance() -> insert($table, $data);
	}
	
	public static function delete($table, $where){
		return Database::getInstance() -> delete($table, $where);
	}
	
	public static function deleteIn($table, $in, $limit=1){
		return Database::getInstance() -> deleteIn($table, $in, $limit);
	}
	
	public static function raw($sql){
		return Database::getInstance() -> raw($sql);
	}
}

