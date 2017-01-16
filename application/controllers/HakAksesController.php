<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class HakAksesController extends CIREST_Controller {
	function __construct() {
		parent::__construct();
		$this->setDefaultModel('hak_akses');
		$this->setDefaultModelPrimaryKey('no');
	}
}

?>