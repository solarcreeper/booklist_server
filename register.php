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
	$sql = "select user_id from user where username='$username'";
	$query = mysql_query($sql);
	if(!$query){
		return die_with_response(DATABASE_OPERATION_ERROR, $response);
	}
	else{
		if($result = mysql_fetch_object($query)){
			return die_with_response(RESULT_OK, $response);
		}
		else{
			$sql = "insert into user(username, password) values('$username','$password')";
			$query = mysql_query($sql);
			if(!$query){
				return die_with_response(DATABASE_OPERATION_ERROR, $response);
			}
			else{
				$response['error_code'] = RESULT_OK;
				$response['user_id'] = mysql_insert_id();
				return json_encode($response, JSON_UNESCAPED_UNICODE);
			}
		}
	}
}
?>