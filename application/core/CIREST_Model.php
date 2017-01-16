<?php
if (!defined('BASEPATH')) exit('No direct script access allowed!');

class CIREST_Model extends CI_Model {
	var $table;
	var $queries = array(
		'select' => '*',
		'where' => array(),
		'limit' => 0,
		'offset' => 0,
		'order' => '');

	function __construct() {
		parent::__construct();
		$this->setTable();
	}

	protected function setQueryParameters() {
		$this->db->select($this->queries['select']);
		$this->db->where($this->queries['where']);
		$this->db->limit($this->queries['limit']);
		$this->db->offset($this->queries['offset']);
		$this->db->order_by($this->queries['order']);
	}

	function getClassName() {
		return get_class($this);
	}

	function getTable() {
		return $this->table;	
	}

	function setTable($tableName = null) {
		if ($tableName == null) {
			$this->table = $this->getClassName() . 's';
		} else {
			$this->table = $tableName;
		}
		$this->table = strtolower($this->table);
	}

	function select($selectString = null) {
		if ($selectString != null) {
			$this->queries['select'] = $selectString;
			return $this;
		} else {
			return $this->queries['select'];
		}
	}

	function where($whereArray = null) {
		if ($whereArray != null) {
			$this->queries['where'] = $whereArray;
			return $this;
		} else {
			return $this->queries['where'];
		}
	}

	function limit($limitNumber) {
		if ($limitNumber != null) {
			$this->queries['limit'] = $limitNumber;
			return $this;
		} else {
			return $this->queries['limit'];
		}
	}

	function offset($offsetNumber) {
		if ($offsetNumber != null) {
			$this->queries['offset'] = $offsetNumber;
			return $this;
		} else {
			return $this->queries['offset'];
		}
			
	}

	function order($orderString) {
		if ($orderString != null) {
			$this->queries['order'] = $orderString;
			return $this;
		} else {
			return $this->queries['order'];
		}
			
	}

	function findAll() {
		$this->setQueryParameters();
		$query = $this->db->get($this->table);
		return $query->result_array();
	}

	function findOne() {
		$this->limit(1)->offset(0);
		$this->setQueryParameters();
		$query = $this->db->get($this->table);
		return $query->row_array();	
	}

	function save($data = array()) {
		if (count($data) > 0) {
			if($this->db->insert($this->table, $data)) {
				$error = $this->db->error();
				$error['message'] = 'Insert data success.';
				$error['insert_id'] = $this->db->insert_id();
			} else {
				$error = $this->db->error();
			}
		} else {
			$error = array(
				'code' => 1,
				'message' => 'No data to insert.');
		}
		return $error;
	}

	function update($data = array()) {
		$where = $this->queries['where'];
		if ( (is_array($where) && (count($where) == 0)) || $where == '') {
			$error = array(
				'code' => 1,
				'message' => 'Where statement is empty. Bulk update prevented.');
		} else {
			$this->db->where($where);
			$this->db->update($this->table, $data);
			$error = $this->db->error();
			if ($error['code'] == 0) {
				$error['message'] = 'Update data success.';
			}
		}
		return $error;
	}

	function delete() {
		$where = $this->queries['where'];
		if ( (is_array($where) && (count($where) == 0)) || $where == '') {
			$error = array(
				'code' => 1,
				'message' => 'Where statement is empty. Bulk delete prevented.');
		} else {
			$this->db->where($where);
			$this->db->delete($this->table);
			$error = $this->db->error();
			if ($error['code'] == 0) {
				$error['message'] = 'Delete data success.';
			}
		}
		return $error;
	}

}

?>