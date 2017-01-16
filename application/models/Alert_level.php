<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Alert_level extends CIREST_Model {
	function __construct() {
		parent::__construct();
		$this->setTable('alert_level');
	}
}
?>