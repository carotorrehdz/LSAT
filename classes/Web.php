<?php

class Web {
	private $_db,
	$_data = array(),
	$_tableName = "web";

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function getWebName($webId = null) {
		if ($webId == null) return;

		$db = $this->_db->get($this->_tableName, array('id', '=', $webId));

		if($db && $db->count()) {
			return $db->results();
		}

		return array();

	}

	public function getWebsForTeacher($teacherId = null) {
		if ($teacherId == null) return;

		$db = $this->_db->get($this->_tableName, array('professor', '=', $teacherId));

		if($db && $db->count()) {
			return $db->results();
		}

		return array();
	}

	public function getQuestionsInWeb($webId = null) {
		if ($webId == null) return;

		$data = array();

		$db = $this->_db->get("questionsinweb", array('webId', '=', $webId));

		if($db && $db->count()) {
			foreach ($this->_db->results() as $q) {
				$data[$q->questionId] = $q->level;

			}

			return $data;
		}

		return array();

	}

	public function getQuestionsIds($webId = null) {
		if ($webId == null) return;

		$questionsIds = array();

		$sql = "SELECT questionId FROM questionsinweb WHERE webId = $webId";
		if(!$this->_db->query($sql, array())->error()) {
			if($this->_db->count()) {
				foreach ($this->_db->results() as $questionsInWeb) {
					foreach ($questionsInWeb as $q) {
					  array_push($questionsIds, $q);
					}
				}

				return $questionsIds;
			}
		}

		return array();

	}

	public function getLevelsInWeb($webId = null) {
		if ($webId == null) return;

		$levels = array();

		$sql = "SELECT DISTINCT level FROM questionsinweb WHERE webId = $webId";
		if(!$this->_db->query($sql, array())->error()) {
			if($this->_db->count()) {
				foreach ($this->_db->results() as $levelsInWeb) {
					foreach ($levelsInWeb as $l) {
					  array_push($levels, $l);
					}
				}

				return $levels;
			}
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