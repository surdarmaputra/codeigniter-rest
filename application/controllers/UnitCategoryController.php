<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class UnitCategoryController extends CIREST_Controller {
	function __construct() {
		parent::__construct();
		$this->setDefaultModel('unit_category');
		$this->setDefaultModelPrimaryKey('no');
	}
}

?>