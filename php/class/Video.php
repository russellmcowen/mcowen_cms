<?php
class Video {
    private $_db, 
            $_link = '', 
            $_results = array(), 
            $_count = 0,
            $_data = array();
    
    private function __construct() {
        $this->_db = DB::getInstance();
    }
    
    public static function getVideos($id= null, $limit = null, $order = 'DESC') {
        $r = new Video();
        if($id === null || $id === '') {
            $r->_results = $r->_db->getAll('videos');
        } else {
            (is_numeric($id) ? $r->_results = $r->_db->get('videos', array('id','=',$id)) : $r->_results = 0);
        }
        $r->_count = $r->count();
        $r->putData();
        
        return $r;
    }
    
    public static function getLinkData($link) {
        $r = new Video();
        $r->_link = $link;
        if(!filter_var($r->_link, FILTER_VALIDATE_URL)) {
            $r->_results = '';
            $r->_data = '';
            $r->_count = 0;
            return $r;
        }
        $link = urlencode($link);
        $link = 'http://www.getlinkinfo.com/info?link='.$link;
        $r->_results = array();
        $html = file_get_html($link);
        $data = $html->find('dd');
        $videoid = '';
        $vidname = '';
        $viddesc = '';
        $length = '';
        foreach($data as $key=>$value) {
            switch ($key) {
                case 0:
                    $videoid = strip_tags(''.$value.'');
                    break;
                case 3:
                    $vidname = strip_tags(''.$value.'');
                    break;
                case 4:
                    $viddesc = strip_tags(''.$value.'');
                    break;
            }
        }
        array_push($r->_data, array(
            'videoid' => $videoid, 
            'vidname' => $vidname, 
            'viddesc' => $viddesc
        ));
        $r->_count = $r->count();
        return $r;
    }
    
    public function parseURL($url) {
		return parse_url($this->_link);
	}

    public function search($col, $q) {
        $q = explode(' ', $q);
        $this->_results = $this->_db->search('videos', $col, $q);
        $this->putData();
    }
    
    public function putData($limit = null, $order = 'DESC') {
        $r = array_values((array)$this->_results);
        foreach($r[3] as $key) {
            $key = (array)$key;
            array_push($this->_data, $key);
        }
        if($order === 'ASC') {
            krsort($this->_data);
        }
        if($limit !== null && is_numeric($limit)) {
            $this->_data = array_slice($this->_data, 0, $limit);
        }
    }
    
    public function getResults() {
        return $this->_results;
    }
    
    public function getData() {
        return $this->_data;
    }
        
    public function count() {
        return count($this->_data);
    }    
    
    public function getFirst() {
        return $this->_data[0];
    }
}
