<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class RoleController extends CIREST_Controller {
	function __construct() {
		parent::__construct();
		$this->setDefaultModel('role');
		$this->setDefaultModelPrimaryKey('no');
	}
}

?>