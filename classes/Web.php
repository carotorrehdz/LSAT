<?php

class Web {
	private $_db,
	$_data = array(),
	$_tableName = "web";

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function getWebsForTeacher($teacherId = null){
		if ($teacherId == null) return;

		$db = $this->_db->get($this->_tableName, array('professor', '=', $teacherId));

		if($db && $db->count()) {
			return $db->results();
		}

		return array();
	}

	public function create($fields = array()) {
		if(!$this->_db->insert($this->_tableName, $fields)) {
			throw new Exception('There was a problem creating the group.');
		}
	}

	public function data() {
		return $this->_data;
	}

	public function addQuestionInWeb($questionId, $webId, $level){
		
		$values = array("questionId" => $questionId,
						"webId" => $webId,
						"level" => $level);

		if($this->_db->insert('questionsinweb', $values)) {
			return true;
		}

		return false;
	}

}

?>