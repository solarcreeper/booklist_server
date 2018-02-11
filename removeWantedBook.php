<?php
include ("\base\conn.php");
if(!is_null($_POST["user_id"]) && !is_null($_POST["book"])){
	$conn = mysql_open();
	$response = array();
	echo foo();
	mysql_close($conn);
}

function foo(){
	require_once("\base\dieError.php");
	$response = array();
	$user_id = $_POST["user_id"];
	$book = json_decode($_POST["book"]);
	$isbn10 = $book->isbn10;
	$isbn13 = $book->isbn13;
	$query_book = "select book_id from book where isbn10='$isbn10' or isbn13='$isbn13'";
	$book_id = mysql_fetch_object(mysql_query($query_book))->book_id;
	$delete_wanted = "delete from wanted_books where book_id='$book_id' and user_id='$user_id'";
	$query = mysql_query($delete_wanted);
	if(!$query){
		return die_with_response(DATABASE_OPERATION_ERROR, $response);
	}
	else{
		$response['error_code'] = RESULT_OK;
	}
	return json_encode($response, JSON_UNESCAPED_UNICODE);
}
?>