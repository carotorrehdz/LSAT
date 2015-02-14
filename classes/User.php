<?php

class User {
	private $_db,
	$_sessionName = null,
	$_cookieName = null,
	$_data = array(),
	$_isLoggedIn = false,
	$_userTableName = 'user';

	public function __construct($user = null) {
		$this->_db = DB::getInstance();
		
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		// Check if a session exists and set user if so.
		if(Session::exists($this->_sessionName) && !$user) {
			$user = Session::get($this->_sessionName);

			if($this->find($user)) {
				$this->_isLoggedIn = true;
			} else {
				$this->logout();
			}
		} else {
			$this->find($user);
		}
	}

	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	public function find($user = null) {
		// Check if user_id specified and grab details
		if($user) {
			$field = (is_numeric($user)) ? 'id' : 'mail';
			$data = $this->_db->get($this->_userTableName, array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function create($fields = array()) {
		// var_dump($fields);
		// echo"<br/>";
		if(!$this->_db->insert('user', $fields)) {
			throw new Exception('There was a problem creating an account.');
		}
	}

	public function checkIsLoggedIn(){
		//el usuario no esta logeado
		if(!$this->isLoggedIn()) {  
			Redirect::to('index.php');
			exit();
		}

		return true;
	}

	public function checkIsValidUser($role = ""){
		//Si no existe usuario para ese mail o el usuario si existe pero no esta logeado redirigimos a index
		if(!$this->exists() || !$this->isLoggedIn()) {  
			Redirect::to('index.php');
			exit();
		}

		if($role != "" && $this->data()->role != $role){
			Redirect::to('index.php');
			exit();
		}
		return true;
	}

	public function redirectToDashboard(){
		$page = "index.php";
		  if($this->data()->role == "admin"){
		    $page = 'dashboardA.php';
		  }
		  else if($this->data()->role == "teacher"){
		    $page = 'dashboardT.php';
		  }
		  else if($this->data()->role == "student"){
		    $page = 'dashboardS.php';
		  }

		  Redirect::to($page);

	}

	public function update($fields = array(), $id = null) {
		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
		}

		if(!$this->_db->update($this->_userTableName, $id, $fields)) {
			throw new Exception('There was a problem updating.');
		}
	}

	public function login($username = null, $password = null, $remember = false) {
		if(!$username && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->id);
		} else {
			$user = $this->find($username);
			if($user) {
				if($this->data()->password === Hash::make($password, $this->data()->salt)) {
					Session::put($this->_sessionName, $this->data()->mail);
					$this->_isLoggedIn = true;

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

						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}

					return true;
				}
			}
		}

		return false;
	}

	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}

	public function data() {
		return $this->_data;
	}

	public function logout() {
		$this->_db->delete('users_session', array('user_id', '=', $this->data()->id));

		Cookie::delete($this->_cookieName);
		Session::delete($this->_sessionName);
	}


	public function getUsersByRole($role){
		$roles = Config::get('roles');
		$error = "";
		if (! in_array($role, $roles)) {
		    $error = "Invalid role";
		}

		$users = array();

		$db = $this->_db->get($this->_userTableName, array('role', '=', $role));
		if($db->count()) {
				$users = $db->results();
			}

		return $users;

	}
}