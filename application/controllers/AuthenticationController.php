<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class AuthenticationController extends CIREST_Controller {
	function __construct() {
		parent::__construct();
		$this->setDefaultModel('auth');
	}

	function verify() {
		$postData = $this->input->post();
		$result = $this->defaultModel->verify($postData['username'], $postData['password']);
		if ($result['status'] == 'success') {
			$this->render($result['userData']);
		} else {
			$this->renderUnauthorized();
		}
	}
}

?>