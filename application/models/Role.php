<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Role extends CIREST_Model {
	function __construct() {
		parent::__construct();
		$this->setTable('role');
	}
}
?>