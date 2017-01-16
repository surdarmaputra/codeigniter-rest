<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class UnitController extends CIREST_Controller {
	function __construct() {
		parent::__construct();
		$this->setDefaultModel('unit');
		$this->setDefaultModelPrimaryKey('no');
	}
}

?>