<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hak_akses extends CIREST_Model {
	function __construct() {
		parent::__construct();
		$this->setTable('hak_akses');
	}
}
?>