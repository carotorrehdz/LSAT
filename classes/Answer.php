<?php

class Answer {
	private $_db,
	$_data = array(),
	$_tableName = 'answer';

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function create($fields = array()) {
		if(!$this->_db->insert($this->_tableName, $fields)) {
			throw new Exception('There was a problem creating the question.');
		}
	}

	public function update($answerId, $fields = array()) {
		if(!$this->_db->update($this->_tableName, $answerId, $fields)) {
			throw new Exception('There was a problem updating.');
		}
	}

	public function data() {
		return $this->_data;
	}
}