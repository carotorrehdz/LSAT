<?php

class Competence {
	private $_db,
	$_data = array(),
	$_tableName = 'competence';

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function getCompetence($competenceId = null) {
		if ($competenceId == null) return;

		$db = $this->_db->get($this->_tableName, array('id', '=', $competenceId));

		if($db && $db->count()) {
			return $db->first();
		}

		return null;

	}

	public function getWebsInCompetence($competenceId = null){
		if ($competenceId == null) return;

		$sql = "SELECT * FROM web W JOIN websincompetence WC ON
		W.id = WC.webId WHERE WC.competenceId = $competenceId";

		if(!$this->_db->query($sql)->error()) {
			if($this->_db->count()) {
				return $this->_db->results();
			}
		}

	}

	public function getCompetencesForTeacher($teacherId = null){
		if ($teacherId == null) return;

		$sql = "SELECT * FROM competence WHERE professor = ? and isPublished = ?";

		if(!$this->_db->query($sql, array($teacherId, true))->error()) {
			if($this->_db->count()) {
				return $this->_db->results();
			}
		}

		return array();
	}

	public function getCompetencesByGroupOfTeacher($teacherId = null){
		if ($teacherId == null) return;

		$sql = "SELECT * FROM competence C JOIN competenceingroup CG ON
		C.id = CG.competenceId WHERE C.professor = $teacherId";

		if(!$this->_db->query($sql, array($teacherId, true))->error()) {
			if($this->_db->count()) {
				return $this->_db->results();
			}
		}

		return array();
	}

	//Regresa un arreglo con los ids de todas las competencias del grupo
	public function getCompetencesIdsForGroup($groupId = null){
		if ($groupId == null) return;

		//$db = $this->_db->get('competenceingroup', array('groupId', '=', $groupId));
		$sql = "SELECT * FROM competenceingroup WHERE groupId = ?";

		if(!$this->_db->query($sql, array($groupId))->error()) {
			if($this->_db->count()) {
				$results = $this->_db->results();
				$ids = array();

				foreach ($results as $key => $value) {
					array_push($ids, $value->competenceId);
				}

				return $ids;
			}
		}

		return array();
	}

	//Le llega un arreglo de ids de competencias y regresa sus detalles
	public function getCompetencesDetails($competencesIds = null){
		if ($competencesIds == null) return;

		$ids = implode(",", $competencesIds);
		$sql = "SELECT * FROM competence C JOIN websincompetence WC ON
		C.id = WC.competenceId WHERE C.id IN ($ids)";

		if(!$this->_db->query($sql, array())->error()) {
			if($this->_db->count()) {
				return $this->_db->results();
			}
		}

		return array();
	}

	public function createNewCompetence($name, $professor, $webIds = array()){
		//Crear el registro de la nueva competencia en la BD
		$this->create(array("name"=>$name, "professor"=> $professor));

		$competenceId = intval($this->_db->lastInsertId());
		try{
			//Agregar todas las redes para la competencia
			$table = "websincompetence";
			foreach ($webIds as $key => $value) {
				$fields = array("order"=> (intval($key)+1), "webId"=>$value, "competenceId"=> $competenceId);
				if(!$this->_db->insert($table, $fields)) {
					throw new Exception('There was a problem creating websincompetence.');
				}
			}
			return $competenceId;
		}
		catch(PDOException $e){
			return false;
		}
	}

	public function create($fields = array()) {
		if(!$this->_db->insert($this->_tableName, $fields)) {
			throw new Exception('There was a problem creating the competence.');
		}
	}

	public function update($competenceId, $fields = array()) {
		if(!$this->_db->update($this->_tableName, $competenceId, $fields)) {
			throw new Exception('There was a problem updating.');
		}
	}

	public function data() {
		return $this->_data;
	}
}
