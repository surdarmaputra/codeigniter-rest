<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Unit extends CIREST_Model {
	function __construct() {
		parent::__construct();
		$this->setTable('unit');
	}
}
?>