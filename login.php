<?php
include ("\base\conn.php");
if(!is_null($_POST["username"]) && !is_null($_POST["password"])){
	$conn = mysql_open();
	$response = array();
	echo foo();
	mysql_close($conn);
}

function foo(){
	require_once("\base\dieError.php");
	$response = array();
	$username = $_POST["username"];
	$password = $_POST["password"];
	$sql = "select user_id from user where username='$username' and password='$password'";
	$query = mysql_query($sql);
	if(!$query){
		return die_with_response(DATABASE_OPERATION_ERROR, $response);
	}
	if ($result = mysql_fetch_object($query)){
		$response['error_code'] = RESULT_OK;
		$response['user_id'] = $result->user_id;
		return json_encode($response, JSON_UNESCAPED_UNICODE);
	}else{
		return die_with_response(AUTHORIZATION_ERROR, $response);
	}
}
?>