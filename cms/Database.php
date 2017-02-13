<?php namespace Cms;

use PDO;
use Cms\Exception\BaseException;
class Database {
    
    private $settings;
    private $pdo;
    private $statement;
    public static $_instance;

	
	
	public static function getInstance(){
		if(empty(self::$_instance)){
			self::$_instance = new Database();
		}
		return self::$_instance;
	}
	
	
    private function __construct(){

		$dsn = 'mysql:dbname=' . DBNAME . ';host=' . DBHOST . '';
        try {
            $this->pdo = new \PDO($dsn, DBLOGIN, DBPASS,[
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ]);
            
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        }
        catch (PDOException $e) {
            echo $this->Exception($e->getMessage());
            die();
        }

	}

    
    public function prepare($sql) {
		$this->statement = $this->pdo->prepare($sql);
	}


    
    public function query($sql, $params = []) {
		//$db = new DB;
			
		//$db -> query('INSERT INTO #_users_group (`name`, `permissions`) VALUES ("'.$_POST['groupName'].'", "")');
		

		$this->statement = $this->pdo->prepare($sql);
		$result = false;

		try {
			if ($this->statement && $this->statement->execute($params)) {
				$data = [];

				while ($row = $this->statement->fetch(\PDO::FETCH_OBJ)) {
					$data[] = $row;
				}

				$result = new \stdClass();
				$result -> row = (isset($data[0]) ? $data[0] : array());
				$result -> rows = $data;
				$result -> numRows = $this->statement->rowCount();
			}

		} catch (\PDOException $e) {
			trigger_error('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode() . ' <br />' . $sql);
			exit();
		}

		if ($result) {
			return $result;
		} else {
			
			return;
		}
	}
	
	
	
	public function insert($table, $data)
    {
        ksort($data);
		$fields = [];
		
		foreach ($data as $key => $value) {
            $fields[] = "`$key`";
        }

        $fieldNames = implode(',', array_values($fields));
        $fieldValues = ':'.implode(', :', array_keys($data));

        $stmt = $this -> pdo -> prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $this->pdo->lastInsertId();
    }
	

	public function update($table, $data, $where, $params=[]) {
        $setters = [];

		
		if(!is_array($data)){
			$data = [$data];
		}
		
        foreach ($data as $key => $value) {
            $setters[] = "`$key`='$value'";
        }
		
		$whereSet = null;
		$i = 0;
        foreach ($where as $key => $value) {
		   if ($i == 0) {
                $whereSet .= " `$key`='$value' ";
            } else {
				$whereSet .= " AND `$key` = '$value' ";
            }
        }
		
        $whereSet = ltrim($whereSet, ' AND ');
        $sql = 'UPDATE '.$table.' SET '.join(', ', $setters).' WHERE '.$whereSet;
	
        $stmt = $this -> pdo -> prepare($sql);
        return $stmt -> execute($params);
    }
	
	public function delete($table, $where, $limit = 100)
    {
        ksort($where);

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :$key";
            } else {
                $whereDetails .= " AND $key = :$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        //if limit is a number use a limit on the query
        if (is_numeric($limit)) {
            $uselimit = "LIMIT $limit";
        }
        $stmt = $this->pdo->prepare("DELETE FROM $table WHERE $whereDetails $uselimit");
		
        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }
	
	
	public function deleteIn($table, $in, $limit = 1)
    {

        $whereDetails = null;
       
        $i = 0;
		$str = '';
        foreach ($in as $key => $value) {
			
			if(is_array($value)){
				foreach($value as $id => $v){
					$str .= "'".$v."'". ',';
				}
			}
			
			$str = trim($str, ',');
            if ($i == 0) {
                $whereDetails .= "`$key` IN (".$str.")";
            } else {
				
                $whereDetails .= " AND `$key` IN (".$str.")";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');
		
		
		
		
        //if limit is a number use a limit on the query
        if (is_numeric($limit)) {
            $uselimit = "LIMIT $limit";
        }

		//dd_die("DELETE FROM `$table` WHERE $whereDetails $uselimit");
		
        $stmt = $this->pdo->prepare("DELETE FROM `$table` WHERE $whereDetails $uselimit");

        $stmt->execute();
        return $stmt->rowCount();
    }
	
	
	public function raw($sql)
    {
        return $this->pdo->query($sql);
    }
	
	
	
	public function getLastId() {
		return $this->pdo->lastInsertId();
	}
}

