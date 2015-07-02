<?php
class Product {
	private $_db, 				// DB OBJECT
			$_data; 			// DATA FROM DB
		
    public function __construct($id = null) {
        $this->_db = DB::getInstance();
        if($id) {
        	$this->find($id);
        }
	}
	
	public function data() {
        return $this->_data;
	}
		
	public function exists() {
        return (!empty($this->_data)) ? true : false;
	}

					
	public function find($product = null) {
		if($product) {
			$data = $this->_db->get('products', array('id', '=', $product));
			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}
			
	public function update($fields = array(), $id = null) {
        if (!$id) {
        	if(count($this->data())) {
        		$id = $this->data()->id;
        	}
        } else {
        	if(count($fields) > 0) {
        		if($this->_db->update('products', $id, $fields)) {
	        		return true;
        		} else { throw new Exception('Problem Updating'); }
        	}
        }
	}
				
	public function create($fields = array()) {
        if(!$this->_db->insert('Products', $fields)) {
            throw new Exception('There was a problem creating an account');
        }
	}
	
	public function getUnique($c) {
		return $this->_db->getUnique('products', $c)->results();
	}
		
	public function getResultsBy($fields = array()) {
		$this->_data = $this->_db->get('products', $fields)->results();
		return true;
	}
	public function browse($cat = null, $op = '=', $val = null) {
		$sql = "SELECT * FROM products";
		if ($cat) {
			if ($val) {
				//if (is_array($val)) {
				if (is_array($op)) {
					if (strpos($val, '-')) {
						$val_array = explode('-', $val);
						$sql .= " WHERE ".$cat." ".$op[0]." '".$val_array[0]."'";
						$sql .= " AND ".$cat." ".$op[1]." '".$val_array[1]."'";
					}
				} else {
					$sql .= " WHERE ".$cat." ".$op." '".$val."'";
				}
				$sql .= " ORDER BY ".$cat." ASC";
				if ($this->_db->query($sql)) {
					$this->_data = $this->_db->results();
					return true;
				}
			}
		}
		return false;
	}
	public function between($a, $b) {
		
	}
	public function getOrder($order = null, $limit = null, $desc = null) {
		$sql = "SELECT * FROM products";
		if ($order) {
			$sql .= " ORDER BY ".$order;
			if ($desc) {
				if ($desc === 'ASC' || $desc === 'DESC') {
					$sql .= " ".$desc;
				} 
			}
			if ($limit) {
				$sql .= " LIMIT ".$limit;
			}
			if ($this->_db->query($sql)) {
				$this->_data = $this->_db->results();
				return true;
			}
		}
		return false;
	}	
	
	public function search($col, $keywords = array()) {
		$this->_data = $this->_db->search('products', $col, $keywords)->results();
		//return $this->data();
	}
	
	public function outOfStock() {
		return $this->_data->OOS;
	}
	
	public function purchase($id = null) {
		
	}
	
	
}
