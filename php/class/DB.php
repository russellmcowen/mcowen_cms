<?php
	class DB {
		private static $_instance = null;	//PDO Connection Instance
		private $_pdo,						//PDO Object
				$_query,					//Query String
				$_results, 					//DB Results
				$_count = 0, 				//DB Results Count
				$_error = false;			//Error True/False
	
	// PDO DB Connection
	private function __construct() {
		try {
			$this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
		} catch(PDOException $e) {
			die(Redirect::to(500));
		}
	}
	
	// Checks If Instance of Connection Already Exists.  Otherwise, It Creates One.
	public static function getInstance() {
		if (!isset(self::$_instance)) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}
	
	// MySQL Query
	public function query($sql, $params = array()) {
		$this->_error = false;
		if ($this->_query = $this->_pdo->prepare($sql)) {
			$x = 1;
			if (count($params)) {
				foreach($params as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}
			if ($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
			}	
		}
		return $this;
	}

	// MySQL Action
	public function action($action, $table, $where = array()) {
		if (count($where) === 3) {
			$operators = array('=', '>', '<', '>=', '<=', '!=');
			$field = $where[0];
			$operator = $where[1];
			$value = $where[2];
			if (in_array($operator, $operators)) {
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				if (!$this->query($sql, array($value))->error()) {
					return $this;
				}
			}
		}
		return false;
	}
	
	public function search($table, $col, $keywords = array()) {
		$this->_error = false;
		if(count($keywords)) {
			$sql = "SELECT * FROM {$table} WHERE {$col} LIKE ?";
			for($i=1;$i<count($keywords);$i++) {
				$sql .= " OR {$col} LIKE ?";
			}
			$r = array();
			if($this->_query = $this->_pdo->prepare($sql)) {
				foreach ($keywords as $keyword) {
					array_push($r, '%'.$keyword.'%');
				}
			}
			if($this->_query->execute($r)) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
			}
		}
		return $this;
	}
		
	public function get($table, $where) {
		return $this->action("SELECT *", $table, $where);
	}
        
	public function getAll($table) {
		return $this->action("SELECT *", $table, array('id','!=',''));
	}
	
	public function getUnique($table, $col) {
		$sql = 'SELECT DISTINCT '.$col.' FROM products';
		return $this->query($sql);		
	}
		
	public function delete($table, $where) {
		return $this->action("DELETE", $table, $where);
	}
		
	public function insert($table, $fields = array()) {
		$keys = array_keys($fields);
		$values = null;
		$x = 1;
		foreach($fields as $field) {
			$values .= "?";
			if ($x < count($fields)) {
				$values .= ', ';
			}
			$x++;
		}
		$sql = "INSERT INTO {$table} (`".implode('`, `', $keys)."`) VALUES ({$values})";
		if (!$this->query($sql, $fields)->error()) {
			return true;
		}
		return false;
	}
		
	public function update($table, $id, $fields) {
		$set = '';
		$x = 1;
		foreach($fields as $name => $value) {
			$set .= "{$name} = ?";
			if ($x < count($fields)) {
				$set .= ', ';
			}
			$x++;
		}
		$sql = "UPDATE {$table} SET {$set} WHERE id = '{$id}'";
		if (!$this->query($sql, $fields)->error()) {
			return true;
		}
		return false;
	}
		
	public function results() {
		return $this->_results;
	}
		
	public function first() {
		return $this->_results[0];
	}
		
	public function count() {
		return $this->_count;
	}
				
	public function error() {
		return $this->_error;
	}		
}
