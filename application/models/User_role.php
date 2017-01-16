<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_role extends CIREST_Model {
	function __construct() {
		parent::__construct();
		$this->setTable('user_role');
	}
}
?>