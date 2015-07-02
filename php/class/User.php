<?php
class User {
	private $_db, 				// DB OBJECT
			$_data, 			// DATA FROM DB
			$_sessionName, 		// USERNAME STORED IN SESSION
			$_cookieName, 		// USERNAME STORED IN COOKIE
			$_isLoggedIn;		// TRUE/FALSE IF SESSION IS SET
		
    public function __construct($user = null) {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie');
        if (!$user) {
			if(Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
			}
                if($this->find($user)) {
                    $this->_isLoggedIn = true;
                }
			
        } else { $this->find($user); }
	}
	
	public function data() {
        return $this->_data;
	}
		
	public function isLoggedIn() {
        return $this->_isLoggedIn;
	}
		
	public function exists() {
        return (!empty($this->_data)) ? true : false;
	}
		
	public function hasPermission($key) {
		$group = $this->_db->get('grp', array('id', '=', $this->data()->grp));
		$perm = $group->first()->permissions;
		$json = json_decode($perm, true);
		foreach($json as $p) {
			if($p == $key) {
				return true;
			}
		}
		return false;
	}
		
	public function userLevel() {
        $group = $this->_db->get('grp', array('id', '=', $this->data()->grp));
        if($group->count()) {
        	return $group->first()->name;
        }
        return "";
	}
					
	public function find($user = null) {
        if($user) {
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->_db->get('members', array($field, '=', $user));
			if($data->count()) {
            	$this->_data = $data->first();
            	return true;
			}
    	}
        return false;
	}
		
	public function update($fields = array(), $id = null) {
        if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
        }
        if($this->_db->update('members', $id, $fields)) {
        	return true;
        } else { throw new Exception('Problem Updating'); }
	}
				
	public function create($fields = array()) {
        if(!$this->_db->insert('members', $fields)) {
            throw new Exception('There was a problem creating an account');
            return false;
        }
        return true;
	}
		
	public function login($username = null, $password = null, $remember = false) {
        if(!$username && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->id);
        } else {
			$user = $this->find($username);
            if($user) {
                if($this->checkPassword($password)) {
                	Session::put($this->_sessionName, $this->data()->id);
                	$ip = $_SERVER['REMOTE_ADDR'];
                 	$change = $this->_db->get('members', array('last_ip', '=', $ip))->results();
                 	if(count($change) && $change[0]->id != Session::get('user')) {
	                	foreach($change as $ch) {
	                		$this->_db->update('members', $ch->id, array('last_ip'=>0));
	                	}
                 	}
                	$this->_db->update('members', Session::get('user'), array('last_ip'=>$ip));
                	if($remember) {
                    	$hash = Hash::unique();
                        $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));
                        if(!$hashCheck->count()) {
							$this->_db->insert('users_session', array(
                                'user_id' => $this->data()->id, 
                                'hash' => $hash
							));
                        } else {
							$hash = $hashCheck->first()->hash;
                        }
                        if(!Cookie::put($this->_cookieName, $hash, Config::get('remember/expires'))) {
                        	return false;
                        }
					}
					$this->_isLoggedIn = true;
					return true;
                }
			}
			return false;	
        }
	}
	
	public function checkEmail($email) {
		
		if($this->_db->get('members', array('email', '=', $email))->count()) {
			return true;
		}
		return false;		
	}
	
	public function validateEmail($email) {
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		return false;
	}
	
	public function checkPassword($pass) {
		if($this->exists()) {
			$salt = $this->data()->salt;
			return ($this->data()->password === Hash::make($pass, $salt)) ? true : false;
			//return password_verify($pass, $this->data()->password);
		}
		return false;
	}
	
	public function changeEmail($email = null) {
		if($email) {
			if ($this->update(array('email'=>$email))) {
				return true;
			}
		}
		return false;
	}
	
	public function changePass($pass = null) {
		if($pass) {
			$passhash = Hash::make($pass, $this->data()->salt);
			if($this->update(array('password'=>$passhash))) {
				return true;
			}
		}
		return false;
	}
	
	public function canAfford() {
		return $this->_db->get('products', array('price', '<=', $this->data()->points))->results();
	}
	
	public function addPoints($value = 0) {
		$temp_points = $this->data()->points + $value;
		$temp_count = $this->data()->surveys + 1;
		$update = $this->update(array('points'=>$temp_points, 'surveys'=>$temp_count), $this->data()->id);
		if($update) {
			return true;
		}
		return false;
	}
	
	public function buyItem($id) {
		$item = $this->_db->get("products", array('id','=', $id))->first();
		if($this->_data->points >= $item->price) {
			$p = $this->_data->points - $item->price;
			$b = $this->_data->winnings + 1;
			$bc = $this->_data->winnings_cost += $item->cash;
			$rq = $this->_data->requests + 1;
			$t = $this->update(array('points'=>$p, 'requests'=>$rq, 'winnings'=>$b, 'winnings_cost'=>$bc));
			if($t) {
				$pop = $item->pop++;
				$p = $this->_db->update('products', $item->id, array('pop'=>$pop));
				if($p) {
					$date = date("Y-m-d");
					$request = $this->_db->insert('requests', array('userid'=>$this->_data->id, 'prodid'=>$item->id, 'date'=>$date));
					if($request) {
						return true;
					}
				}
			}
		}
		return false;
	}
	
	public function logout() {
		if($this->isLoggedIn()) {
	        $this->_db->delete('users_session', array('user_id', '=', $this->data()->id));
	        Session::delete($this->_sessionName);
	        Cookie::delete($this->_cookieName);
		}
	}
			
}
