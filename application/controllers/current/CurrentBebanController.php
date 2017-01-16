<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class CurrentBebanController extends CIREST_Controller {
	function __construct() {
		parent::__construct();
		$this->setDefaultModel('beban');
	}

	function index() {
		$this->handleRequest('GET');
		$data = $this->defaultModel->findCurrentFromAllUnit();
		$this->render($data);
	}
}

?>