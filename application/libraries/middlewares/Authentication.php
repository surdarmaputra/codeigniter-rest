<?php
if (!defined('BASEPATH')) exit('No direct script access allowed!');

use \Firebase\JWT\JWT;

class Authentication {
	var $key;

	function __construct() {
		$this->setKey('nasi90r3n9!!!');
	}

	function setKey($key) {
		return $this->key = $key;
	}

	function getKey() {
		return $this->key;
	}

	function encode($token = array()) {
		return JWT::encode($token, $this->getKey());
	}

	function decode($jwt = null) {
		return JWT::decode($jwt, $this->getKey(), array('HS256'));
	}

	function decodeToArray($jwt = null) {
		return (array) JWT::decode($jwt, $this->getKey(), array('HS256'));
	}
}
?>