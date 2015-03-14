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

	public function getGroupById($groupId= null){
		if ($groupId == null) return false;

		$db = $this->_db->get($this->_tableName, array('id', '=', $groupId));

		if($db && $db->count()) {
			return $db->first();
		}

		return false;
	}

	public function verifyGroupOwnership($groupId, $teacherId){
		$group = $this->getGroupById($groupId);
		if($group->professor == $teacherId){
			return true;
		}
		return false;
	}

	public function getCompetencesForGroup($groupId = null){
		if ($groupId == null) return;

		$competences = new Competence();
		$competencesIds = $competences->getCompetencesIdsForGroup($groupId);
		var_dump($competencesIds);
		$details = $competences->getCompetencesDetails($competencesIds);

		return $details;
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