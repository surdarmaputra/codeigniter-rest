<?php
	header('Content-Type: application/json');
	if (isset($data)){
		echo json_encode($data);
	} else {
		$data = array();
		echo json_encode($data);
	}

?>