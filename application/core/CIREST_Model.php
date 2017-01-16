<?php
if (!defined('BASEPATH')) exit('No direct script access allowed!');

class CIREST_Model extends CI_Model {
	var $table;
	var $queries = array(
		'select' => '*',
		'where' => array(),
		'limit' => 0,
		'offset' => 0,
		'order' => '',
		'group_by' => '',
		'with' => array());

	var $relations = array();

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
		$this->db->group_by($this->queries['group_by']);
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
		if ($limitNumber !== null) {
			$this->queries['limit'] = $limitNumber;
			return $this;
		} else {
			return $this->queries['limit'];
		}
	}

	function offset($offsetNumber) {
		if ($offsetNumber !== null) {
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

	function group_by($groupByString) {
		if ($groupByString != null) {
			$this->queries['group_by'] = $groupByString;
			return $this;
		} else {
			return $this->queries['group_by'];
		}
	}	

	function hasOne($tableName = null, $foreignKey = null, $relationTableKey = null)  {
		$relation = array(
			'tableName' => $tableName,
			'currentTableKey' => $foreignKey,
			'relationTableKey' => $relationTableKey,
			'relationType' => 'one-to-one');
		$this->relations[$tableName] = $relation;
	}

	function hasMany($tableName = null, $primaryKey = null, $relationTableForeignKey = null)  {
		$relation = array(
			'tableName' => $tableName,
			'currentTableKey' => $primaryKey,
			'relationTableKey' => $relationTableForeignKey,
			'relationType' => 'one-to-many');
		$this->relations[$tableName] = $relation;
	}

	function with($relations = array()) {
		$this->queries['with'] = $relations;
		return $this;
	}

	function flushQueries() {
		$this->queries = array(
			'select' => '*',
			'where' => array(),
			'limit' => 0,
			'offset' => 0,
			'order' => '',
			'group_by' => '',
			'with' => array());
	}

	function findOneToOne($tableName, $key, $keyValue) {
		$this->db->where(array($key => $keyValue));
		$this->db->limit(1);
		$query = $this->db->get($tableName);
		return $query->row_array();
	}

	function findOneToMany($tableName, $key, $keyValue) {
		$this->db->where(array($key => $keyValue));
		$query = $this->db->get($tableName);
		return $query->result_array();	
	}

	function findRelations($mainTableQueries = array()) {
		foreach ($this->queries['with'] as $relation) {
			if (array_key_exists($relation, $this->relations)) {
				$currentRelation = $this->relations[$relation];
				switch ($currentRelation['relationType']) {
					case 'one-to-one':
						foreach ($mainTableQueries as $key => $mainData) {
							$keyValue = $mainData[$currentRelation['currentTableKey']];
							$relationData = $this->findOneToOne($currentRelation['tableName'], $currentRelation['relationTableKey'], $keyValue);
							$mainTableQueries[$key][$currentRelation['tableName']] = $relationData;
						}
						break;
					case 'one-to-many':
						foreach ($mainTableQueries as $key => $mainData) {
							$keyValue = $mainData[$currentRelation['currentTableKey']];
							$relationData = $this->findOneToMany($currentRelation['tableName'], $currentRelation['relationTableKey'], $keyValue);
							$mainTableQueries[$key][$currentRelation['tableName']] = $relationData;
						}
						break;
					default:
						break;
				}
			}
		}
		return $mainTableQueries;
	}

	function max($column) {
		$this->db->where($this->queries['where']);
		$this->db->limit($this->queries['limit']);
		$this->db->offset($this->queries['offset']);
		$this->db->order_by($this->queries['order']);
		$this->db->group_by($this->queries['group_by']);
		$this->db->select_max($column);
		$query = $this->db->get($this->table);
		$data = $query->row_array();
		$this->flushQueries();
		return $data;
	}

	function findAll() {
		$this->setQueryParameters();
		$query = $this->db->get($this->table);
		$data = $query->result_array();
		if (count($this->queries['with']) > 0) {
			$finalData = $this->findRelations($data);
			$data = $finalData;
		}
		$this->flushQueries();
		return $data;
	}

	function findOne() {
		$this->limit(1)->offset(0);
		$this->setQueryParameters();
		$query = $this->db->get($this->table);
		$this->flushQueries();
		return $query->row_array();	
	}

	function count() {
		$this->setQueryParameters();
		$count = $this->db->count_all_results($this->table);
		$this->flushQueries();
		return $count;
	}

	function save($data = array()) {
		if (count($data) > 0) {
			if($this->db->insert($this->table, $data)) {
				$error = $this->db->error();
				$error['message'] = 'Berhasil menambahkan data.';
				$error['insert_id'] = $this->db->insert_id();
			} else {
				$error = $this->db->error();
			}
		} else {
			$error = array(
				'code' => 1,
				'message' => 'Tidak ada data baru yang ditambahkan.');
		}
		return $error;
	}

	function save_batch($data = array()) {
		if (count($data) > 0) {
			if($this->db->insert_batch($this->table, $data)) {
				$error = $this->db->error();
				$error['message'] = 'Berhasil menambahkan data.';
				$error['insert_id'] = $this->db->insert_id();
			} else {
				$error = $this->db->error();
			}
		} else {
			$error = array(
				'code' => 1,
				'message' => 'Tidak ada data baru yang ditambahkan.');
		}
		return $error;
	}

	function update($data = array()) {
		$where = $this->queries['where'];
		if ( (is_array($where) && (count($where) == 0)) || $where == '') {
			$error = array(
				'code' => 1,
				'message' => 'Update secara masal tidak diijinkan.');
		} else {
			$this->db->where($where);
			$this->db->update($this->table, $data);
			$error = $this->db->error();
			if ($error['code'] == 0) {
				$error['message'] = 'Berhasil melakukan update data.';
			}
		}
		$this->flushQueries();
		return $error;
	}

	function delete() {
		$where = $this->queries['where'];
		if ( (is_array($where) && (count($where) == 0)) || $where == '') {
			$error = array(
				'code' => 1,
				'message' => 'Penghapusan secara masal tidak diijinkan.');
		} else {
			$this->db->where($where);
			$this->db->delete($this->table);
			$error = $this->db->error();
			if ($error['code'] == 0) {
				$error['message'] = 'Berhasil melakukan penghapusan data.';
			}
		}
		$this->flushQueries();
		return $error;
	}

}

?>