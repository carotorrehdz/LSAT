<?php

class Groups {
	private $_db,
	$_data = array(),
	$_tableName = "groups";

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function getGroupsForTeacher($teacherId = null){
		if ($teacherId == null) return;

		$db = $this->_db->get($this->_tableName, array('professor', '=', $teacherId));

		if($db && $db->count()) {
			return $db->results();
		}

		return array();
	}

	public function getGroupByName($groupname = null){
		if ($groupname == null) return false;

		$db = $this->_db->get($this->_tableName, array('name', '=', $groupname));

		if($db && $db->count()) {
			return $db->first();
		}

		return false;
	}

	public function create($fields = array()) {
		if(!$this->_db->insert($this->_tableName, $fields)) {
			throw new Exception('There was a problem creating the group.');
		}
	}

	public function data() {
		return $this->_data;
	}

}

?>