<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class HomeController extends CIREST_Controller {
	function __construct() {
		parent::__construct();
	}

	function index() {
		$data = array(
			'subject' => 'Welcome Message',
			'message'  => 'Hello! Stay Awesome!');

		$this->render($data);
	}
}

?>