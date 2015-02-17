<?php

class Groups {
	private $_db,
	$_data = array(),
	$_tableName = 'groups';

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function getGroupsForTeacher($teacherId = null){
		if ($teacherId == null) return;

		$db = $this->_db->get($this->_tableName, array('professor', '=', $teacherId));

		if($db && $db->count()) {
			$this->_data = $db->results();
		}

		return $db->results();
	}

	public function create($fields = array()) {
		// var_dump($fields);
		if(!$this->_db->insert($this->_tableName, $fields)) {
			throw new Exception('There was a problem creating the group.');
		}
	}

	public function data() {
		return $this->_data;
	}

}

?>