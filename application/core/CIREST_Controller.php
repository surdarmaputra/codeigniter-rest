<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class CIREST_Controller extends CI_Controller {
	var $defaultModelPrimaryKey = '';
	function __construct() {
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, PUT, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
		$this->load->library('middlewares/authentication', NULL, 'auth');
		// $token = $this->checkAuthorization();
	}

	protected function setDefaultModelPrimaryKey($key = '') {
		$this->defaultModelPrimaryKey = $key;
	}

	protected function setDefaultModel($modelName = null) {
		if ($modelName != null) {
			$this->load->model($modelName, 'defaultModel');
		}
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
		} else if (($_SERVER['REQUEST_METHOD'] == 'OPTIONS') && ($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == strtoupper($method))){
			die();
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

	protected function renderCustom($statusCode = 200, $data = null) {
		$data['data'] = $data;
		$this->output->set_status_header($statusCode);
		$this->load->view('api/'.$apiFormat, $data);
	}

	protected function defaultQueryParamsHandler($queryParams) {
		if (isset($queryParams['select'])) {
			$this->defaultModel->select($queryParams['select']);
		}

		if (isset($queryParams['limit']) && $queryParams['limit'] > 0) {
			$this->defaultModel->limit($queryParams['limit']);
		}

		if (isset($queryParams['offset']) && $queryParams['offset'] > 0) {
			$this->defaultModel->offset($queryParams['offset']);
		}

		if (isset($queryParams['group_by'])) {
			$this->defaultModel->group_by($queryParams['group_by']);
		}
		
	}

	protected function customQueryParamsHandler($queryParams) {

	}

	protected function handleQueryParams($queryParams) {
		$this->defaultQueryParamsHandler($queryParams);
		$this->customQueryParamsHandler($queryParams);
	}

	protected function populateResult($queryParams) {
		$this->handleQueryParams($queryParams);
		$query = $this->defaultModel->findAll();

		$this->handleQueryParams($queryParams);
		$allRecordsCount = $this->defaultModel->limit(0)->offset(0)->count();

		$data = array(
			'allRecords' => $allRecordsCount,
			'currentRecords' => count($query));
		if (isset($queryParams['limit']) && $queryParams['limit'] > 0) {
			$data['limit'] = $queryParams['limit'];
		}

		if (isset($queryParams['offset']) && $queryParams['offset'] > 0) {
			$data['offset'] = $queryParams['offset'];
		}
		$data['data'] = $query;
		return $data;
	}

	protected function checkDefaultModel() {
		if (!isset($this->defaultModel) || $this->defaultModel == null) {
			$data = array(
				'data' => array('message' => 'Hello! No data to show.'));
			$this->load->view('api/error', $data);
		}
	}

	protected function handleRequest($httpMethod = null) {
		if ($httpMethod != null) {
			$this->checkDefaultModel();
			$this->checkRequestMethod($httpMethod);	
		} else $this->renderUnauthorized();
	}

	public function index() {
		$this->handleRequest('GET');
		$queryParams = $this->input->get();
		$data = $this->populateResult($queryParams);
		$this->render($data);
	}

	public function getById($id = null) {
		$this->handleRequest('GET');
		$data = $this->defaultModel
			->where(array($this->defaultModelPrimaryKey => $id))
			->findOne();
		$this->render($data);
	}

	public function create() {
		$this->handleRequest('POST');
		$newData = $this->input->post();
		$status = $this->defaultModel->save($newData);
		$this->render($status);
	}

	public function update($id) {
		$this->handleRequest('PUT');
		$updateData = $this->input->put();
		$status = $this->defaultModel
			->where(array($this->defaultModelPrimaryKey => $id))
			->update($updateData);
		$this->render($status);
	}

	public function delete($id) {
		$this->handleRequest('DELETE');
		$status = $this->defaultModel
			->where(array($this->defaultModelPrimaryKey => $id))
			->delete();
		$this->render($status);
	}

	public function options() {
		$this->handleRequest('OPTIONS');
	}
}
?>