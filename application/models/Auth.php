<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function verify($username = null, $password = null) {
		$secretWord = $this->config->item('secret_word');
		$status = ['success', 'failed'];
		$result = array(
			'status' => $status[1],
			'userData' => array()
		);
		$this->db->limit(1);
		$query = $this->db->get_where('user', array(
			'username' => $username,
			'password' => sha1($secretWord.$password)
		));
		if ($query->num_rows() > 0) {
			$user = $query->row_array();
			$roleName = $this->getRoleName($user['no']);
			$authorization = $this->getAuthorization($user['no']);
			$token = 'token';

			$result['status'] = $status[0];
			$result['userData'] = array(
				'username' => $user['username'],
				'roleName' => $roleName,
				'token' => $token,
				'authorization' => $authorization
			);
		}
		return $result;
	}

	protected function getRoleName($userNo = null) {
		$roleName = null;
		$this->db->select('role.nama role_nama');
		$this->db->join('role', 'user_role.role_no = role.no', 'left');
		$this->db->where('user_no', $userNo);
		$query = $this->db->get('user_role');
		if ($query->num_rows() > 0) {
			$role = $query->row_array();
			$roleName = $role['role_nama'];
		} 
		return $roleName;
	}

	protected function getAuthorization($userNo = null) {
		$authorization = array();
		$this->db->select('unit.nama fitur_nama, unit.rute fitur_rute, unit.description fitur_deskripsi, unit.mw_name fitur_mw, unit.gatecycle_addresses fitur_gatecycle_addresses, unit_category.nama fitur_jenis');
		$this->db->join('hak_akses', 'user_role.role_no = hak_akses.role_no', 'left');
		$this->db->join('unit', 'hak_akses.unit_no = unit.no', 'left');
		$this->db->join('unit_category', 'unit_category.no = unit.unit_category', 'left');
		$this->db->where('user_no', $userNo);
		$query = $this->db->get('user_role');
		$authorization = $query->result_array();
		return $authorization;
	}
}
?>