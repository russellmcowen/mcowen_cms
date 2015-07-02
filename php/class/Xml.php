<?php

class Xml {
	private $_newsXML = '', 
			$_news = null, 
			$_newsArray = array(), 
			$_error = array();
	
	public function __construct($file = null) {
		if($file) {
			try {
				$this->_news = simplexml_load_file($file);
				$this->setArray();
			} catch (Exception $e) {
				$this->addError('Error Loading File');
				return false;
			}
			$this->_newsXML = $file;
			return true;
		}
		return false;
	}
	
	public function load($file = null) {
		$this->_error = array();
		if($file !== null) {
			try {
				$this->_news = simplexml_load_file($file);
				$this->setArray();
			} catch(Exception $e) {
				$this->addError('Error Loading File');
				return false;
			}
			return true;
		}
		$this->addError('Error Loading File');
		return false;
	}
	
	public function refreshNews() {
		$this->load($this->_newsXML);
	}
	
	public function makeArray($obj = null) {
		$return = array();
		$news = $this->getNews();
		foreach($obj as $news) {
			$a = array();
			foreach($news as $key=>$post) {
				$a[$key] = $post;
				//echo $key."=".$post."<br />";	
			}
			array_push($return, $a);
			return $return;
		}
	}
	
	public function setArray($limit = null) {
		$xarray = array();
		if($limit === null) {
			foreach($this->_news as $article=>$value) {
				$a = array();
				foreach($value as $key=>$item) {
					$a[$key] = $item;
				}
				array_push($xarray, $a);
			}
			$this->_newsArray = $xarray;
			return true;
		} else {
			if(is_numeric($limit)) {
				if(count($this->_news) < $limit) { $limit = count($this->_news); }
				for($i=0;$i<$limit-1;$i++) {
					$a = array();
					foreach($value as $key=>$item->$thing) {
						$a[$key] = $thing;
					}
					array_push($xarray, $a);
				}
				$this->_newsArray = $xarray;
				return true;
			}
		}
		return false;
	}
	
	public function orderArray($order = 'DESC') {
		if($order === 'ASC') {
			ksort($this->_newsArray);
			return true;
		} else if($order === 'DESC') {
			krsort($this->_newsArray);
			return true;
		} 
		$this->addError('Invalid Order');
		return false;
	}
	
	public function getArticle($id) {
		foreach($this->getArray() as $item) {
			if($item['id'] == $id) {
				return $item;
			}
		}
		return false;
	}
	
	public function getArray() {
		return $this->_newsArray;
	}
		
	public function getNews() {
		return $this->_news;
	}
	
	private function addError($string = '') {
		if($string === '') {
			$string = 'Unspecified Error';
		}
		array_push($this->_error, $string);
	}
	
	public function getError() {
		return $this->_error;
	}
	
}

?>
