<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class CIREST_Controller extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library('middlewares/authentication', NULL, 'auth');
		// $token = $this->checkAuthorization();
	}	

	protected function checkAuthorization() {
		$authorizationHeader = $this->input->get_request_header('Authorization');
		$header = explode(' ', $authorizationHeader);
		if ($authorizationHeader == null || $header[0] != 'Bearer') {
			$this->renderUnauthorized();
		} else {
			$jwt = $header[1];
			$token = $this->auth->decodeToArray($jwt);
			return $token;
		}
	}

	protected function checkRequestMethod($method) {
		if ($_SERVER['REQUEST_METHOD'] == strtoupper($method)) {
			return true;
		} else {
			$data = array();
			$data['data'] = array(
				'message' => 'Method not allowed!');
			$this->output->set_status_header(405);
			$this->load->view('api/error', $data);
		}
	}

	protected function render($data, $apiFormat = 'default') {
		$data['data'] = $data;
		$this->load->view('api/'.$apiFormat, $data);
	}

	protected function renderUnauthorized() {
		$data['data'] = array(
			'message' => 'Unauthorized access!');
		$this->output->set_status_header(401);
		$this->load->view('api/error', $data);
	}
}
?>