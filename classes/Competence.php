<?php

class Competence {
	private $_db,
	$_data = array(),
	$_tableName = 'competence';

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function getCompetencesForTeacher($teacherId = null){
		if ($teacherId == null) return;

		$db = $this->_db->get($this->_tableName, array('professor', '=', $teacherId));

		if($db && $db->count()) {
			return $db->results();
		}

		return array();
	}

	public function getCompetencesIdsForGroup($groupId = null){
		if ($groupId == null) return;

		//$db = $this->_db->get('competenceingroup', array('groupId', '=', $groupId));
		$sql = "SELECT id FROM competenceingroup WHERE groupId = ?";
		
		if(!$this->_db->query($sql, array($groupId))->error()) {
			if($this->_db->count()) {
				return $this->_db->results();
			}
		}

		// if($db && $db->count()) {
		// 	return $db->results();
		// }

		return array();
	}

	public function getCompetencesDetails($competencesIds = null){
		if ($competencesIds == null) return;
		
		$ids = implode(",", $competencesIds);
		$sql = "SELECT * FROM competence C JOIN websincompetence WC ON 
		       C.id = WC.competenceId WHERE C.id IN $ids";
		
		if(!$this->_db->query($sql, array($topic, $difficulty))->error()) {
			if($this->_db->count()) {
				return $this->_db->results();
			}
		}

		return array();
	}

	public function create($fields = array()) {
		if(!$this->_db->insert($this->_tableName, $fields)) {
			throw new Exception('There was a problem creating the competence.');
		}
	}

	public function update($questionId, $fields = array()) {
		if(!$this->_db->update($this->_tableName, $questionId, $fields)) {
			throw new Exception('There was a problem updating.');
		}
	}

	public function data() {
		return $this->_data;
	}
}