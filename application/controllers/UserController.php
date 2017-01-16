<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class UserController extends CIREST_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('user');
		$this->rest_input = new CIREST_Input();
	}

	function index() {
		$this->checkRequestMethod('GET');
		$data = array(
			'title' => 'Get all users',
			'message' => 'Stay awesome!'
		);
		$this->render($data);
	}

	function getById($id = null) {
		$this->checkRequestMethod('GET');
		$data = array(
			'title' => 'Get user by ID='.$id,
			'message' => 'Stay awesome!'
		);
		$this->render($data);
	}

	function create() {
		$this->checkRequestMethod('POST');
		$data = array(
			'title' => 'Create new user',
			'message' => 'Stay awesome!'
		);
		$this->render($data);	
	}

	function update($id = null) {
		$this->checkRequestMethod('PUT');
		$data = array(
			'subject' => 'Update whole user data by ID='.$id,
			'message'  => 'Good job!');

		$this->render($data);	
	}

	function delete($id = null) {
		$this->checkRequestMethod('DELETE');
		$data = array(
			'subject' => 'Delete user data by ID='.$id,
			'message'  => 'Good job!');

		$this->render($data);		
	}
}

?>