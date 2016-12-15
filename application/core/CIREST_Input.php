<?php
if (!defined('BASEPATH')) exit('No direct script access allowed!');

class CIREST_Input extends CI_Input {
	function __construct() {
		parent::__construct();
	}

	function put($index = NULL, $xss_clean = NULL) {
		parse_str(file_get_contents("php://input"),$data);
		return $this->_fetch_from_array($data, $index, $xss_clean);
	}
}

?>