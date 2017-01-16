<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class UserRoleController extends CIREST_Controller {
	function __construct() {
		parent::__construct();
		$this->setDefaultModel('user_role');
		$this->setDefaultModelPrimaryKey('no');
	}
}

?>