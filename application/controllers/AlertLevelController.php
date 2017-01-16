<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class AlertLevelController extends CIREST_Controller {
	function __construct() {
		parent::__construct();
		$this->setDefaultModel('alert_level');
		$this->setDefaultModelPrimaryKey('no');
	}
}

?>