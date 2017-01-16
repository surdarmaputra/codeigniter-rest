<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class UserController extends CIREST_Controller {
	function __construct() {
		parent::__construct();
		$this->setDefaultModel('user');
		$this->setDefaultModelPrimaryKey('no');
	}

	// Overriding create function from CIREST_Controller
	public function create() {
		$this->handleRequest('POST');
		$newData = $this->input->post();
		if (isset($newData['password'])) {
			$secretWord = $this->config->item('secret_word');
			$newData['password'] = sha1($secretWord.$newData['password']);
		}
		$status = $this->defaultModel->save($newData);
		$this->render($status);
	}

	public function update($id) {
		$this->handleRequest('PUT');
		$updateData = $this->input->put();
		if (isset($updateData['password'])) {
			$secretWord = $this->config->item('secret_word');
			$updateData['password'] = sha1($secretWord.$updateData['password']);
		}
		$status = $this->defaultModel
			->where(array($this->defaultModelPrimaryKey => $id))
			->update($updateData);
		$this->render($status);
	}
}

?>