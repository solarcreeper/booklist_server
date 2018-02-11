<?php
define("NO_CONTENT", "1001");
define("RESULT_OK","1000");
define("DATABASE_OPERATION_ERROR", "4001");
define("AUTHORIZATION_ERROR","2001");
define("USERNAME_EXIST","2002");

$action = "";
if($_POST['action'] != ''){
	$action = $_POST['action'];
	if(file_exists($action.'.php')){
		include($action.'.php');
	}
	else{
		$response = array();
		$response['error_code'] = NO_CONTENT;
		return json_decode($response, JSON_UNESCAPED_UNICODE);
	}
}
?>