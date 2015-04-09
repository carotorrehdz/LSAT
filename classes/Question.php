<?php

class Question {
	private $_db,
	$_tableName = 'question';

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function create($fields = array()) {
		if(!$this->_db->insert($this->_tableName, $fields)) {
			throw new Exception('There was a problem creating the question.');
		}
	}

	public function update($questionId, $fields = array()) {
		if(!$this->_db->update($this->_tableName, $questionId, $fields)) {
			throw new Exception('There was a problem updating.');
		}
	}

	public function getFilteredQuestions($topic, $difficulty){

		$sql = "SELECT id, LEFT(text, 80) as text FROM question WHERE topic =  ? AND difficulty = ?";
		if(!$this->_db->query($sql, array($topic, $difficulty))->error()) {
			if($this->_db->count()) {
				return $this->_db->results();
			}
		}
		return array();
	}

	public function getQuestion($id){

		$sql = "SELECT * FROM question WHERE id = ?";
		if(!$this->_db->query($sql, array($id))->error()) {
			if($this->_db->count()) {
				return $this->_db->results();
			}
		}
		return array();
	}

	public function getQuestions($ids = array()){
		if (count($ids) == 0) return;

		$idList = implode(",", $ids);

		$sql = "SELECT * FROM question WHERE id IN ($idList)";
		if(!$this->_db->query($sql, array())->error()) {
			if($this->_db->count()) {
				return $this->_db->results();
			}
		}

		return array();
	}

	public function getNextQuestion($studentId, $groupId, $competenceId, $grade){
		
		//Traer los registros de studentrecord que cumplan con los tres ids
		$studentrecord = array();
		$sql = "SELECT * FROM studentrecord WHERE studentId = ? AND groupId = ? AND competenceId = ? 
		GROUP_CONCAT(CONVERT(studentProgressId, CHAR(8)) SEPARATOR ', ') as studentProgressIds";

		if(!$this->_db->query($sql, array($studentId, $groupId, $competenceId))->error()) {
			if($this->_db->count()) {
				$studentrecord = $this->_db->results();
			}
		}

		$studentProgressIds = $studentrecord->studentProgressIds;
		echo "studentProgressIds";
		var_dump($studentProgressIds);
		//Despues traer de studentProgress todos los que cumplan con studentProgressId 

		$studentprogress = array();
		$sql = "SELECT * FROM studentprogress WHERE id IN (?)";

		if(!$this->_db->query($sql, array($studentProgressIds))->error()) {
			if($this->_db->count()) {
				$studentprogress = $this->_db->results();
			}
		}
		echo "studentprogress";
		var_dump($studentprogress);
		
		// Si todos los de student progress tienen seteado un finished date 
		// quiere decir que la competencia fue terminada
		$websTerminadas = array();
		$competenciaTerminada = true;
		foreach ($studentprogress as $key => $sp) {
			var_dump($sp);
			if(!isset($sp->finishedDate){
				$competenciaTerminada = false;
			}

			$websTerminadas[$sp->webId] = isset($sp->finishedDate);
		}

		echo "studentprogress";
		var_dump($studentprogress);

		echo "competenciaTerminada";
		var_dump($competenciaTerminada);


		//Si la competencia no esta termianda tenemos que saber el orden de las redes, para saber cual sigue
		//Obtenemos “websincompetence” con el competenceId
		$websincompetence = array();
		$sql = "SELECT * FROM  websincompetence where competenceId = ? ORDER BY order";
		if(!$this->_db->query($sql, array($competenceId))->error()) {
			if($this->_db->count()) {
				$websincompetence = $this->_db->results();
			}
		}
		
		//Cada elemento del arreglo resultante es una red, ordenadas de menor a mayor según el orden
		//iteramos el arreglo vamos viendo los ids de las webs uno por uno
		$webAContestar = 0;
		foreach ($websincompetence as $key => $web) {
			//Si ese webid ya fue terminado, nos pasamos a la siguiente	
			$webId = $web->id;
			if($websTerminadas[$webId] == false){
				// a la primera ocurrencia de web no terminada
				//sabemos que esa es la que tiene que seguir contestando
				$webAContestar = $webId;
				break;
			}
		}

		$lastAnsweredQuestionId = -1;
		$firstQ = -1;
		$lastQ = -1;
		//Vamos a last answeredQuestionId dentro de studentprogress
		foreach ($studentprogress as $key => $sp) {
			if($sp->webId == $webAContestar){
				$lastAnsweredQuestion = $sp->lastQuestion;
				$firstQ = $sp->firstQuestion;
				$lastQ = $sp->lastQuestion;
			}
		}
		
		//Vamos a questionsforstudent y buscamos el nivel en el que se encuentra esa pregunta.
		$lastAnsweredQuestion = array();
		$sql = "SELECT * FROM  questionsforstudent where id = ?";
		if(!$this->_db->query($sql, array($lastAnsweredQuestionId))->error()) {
			if($this->_db->count()) {
				$lastAnsweredQuestion = $this->_db->first();
			}
		}

		$level = intval($lastAnsweredQuestion->level);
		$nextLevel = $level+1;
		

		//Buscamos ahí también la primerpregunta con “answered”=false del siguiente nivel
		$sql = "SELECT * FROM  questionsforstudent where level = ? AND id BETWEEN ? AND ? AND answered = false";
		if(!$this->_db->query($sql, array($nextLevel, $firstQ, $lastQ))->error()) {
			if($this->_db->count()) {
				$lastAnsweredQuestion = $this->_db->first();
			}
		}

		//Si ya no hay otro nivel ¿
		
		//Si ya no hay preguntas libres del nivel que sigue?
			}

	}
	
}
