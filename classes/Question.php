<?php

class Question {
	private $_db,
	$_data = array(),
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
		
		$sql = "SELECT * FROM question WHERE topic =  ? AND difficulty = ?";
		if(!$this->_db->query($sql, array($topic, $difficulty))->error()) {
			if($this->_db->count()) {
				//echo "aqui";
				//var_dump($this->_db->results());
				return $this->_db->results();
			}
		}
		//echo "aca";
		return array();
	}

	public function data() {
		return $this->_data;
	}
}