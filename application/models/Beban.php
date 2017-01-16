<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Beban extends CIREST_Model {
	function __construct() {
		parent::__construct();
		$this->setTable('unit');
	}

	function findCurrentFromAllUnit() {
		$this->masterDb = $this->load->database('default', TRUE);
		$this->unitDb = array();
		$this->masterDb->where('unit_connection', 1);
		$units = $this->masterDb->get('unit')->result_array();
		$readings = array();
		
		foreach ($units as $key => $unit) {
			$this->unitDb[$unit['db_identifier']] = $this->load->database($unit['db_identifier'], TRUE);
			$selection = array(
				'address_no' => $unit['mw_name']);

			$this->unitDb[$unit['db_identifier']]->limit(1);
			$this->unitDb[$unit['db_identifier']]->order_by('date_stamp desc');
			$reading = $this->unitDb[$unit['db_identifier']]->get_where('current', $selection)->row_array();

			$readings[$unit['no']] = $reading;
		}

		return $readings;
	}
}
?>