<?php
include ("\base\conn.php");
require_once("\base\dieError.php");
if(!is_null($_POST["start"]) && !is_null($_POST["count"]) && !is_null($_POST["user_id"])){
	$conn = mysql_open();
	$result = foo();
	echo $result;
	mysql_close($conn);
	
}

function foo(){
	$user_id = $_POST["user_id"];
	$start = $_POST["start"];
	$count = $_POST["count"];
	$sql = "select book_id from wanted_books where user_id='$user_id' limit $count offset $start";
	$query = mysql_query($sql);
	if (!$query){
		return die_with_response(DATABASE_OPERATION_ERROR, $response);
	}
	else{
		$response_book = array();
		while($book_id = mysql_fetch_object($query)){
			$id = $book_id->book_id;
			$sql_book = "select * from book where book_id='$id'";
			$sql_author = "select * from author where book_id='$id'";
			$sql_book_image = "select * from book_images where book_id = '$id'";
			$sql_rating = "select * from rating where book_id = '$id'";
			$sql_tag = "select * from tag where book_id = '$id'";
			$sql_translator = "select * from translator where book_id = '$id'";
			$query_book = mysql_query($sql_book);
			$query_author = mysql_query($sql_author);
			$query_book_image = mysql_query($sql_book_image);
			$query_rating = mysql_query($sql_rating);
			$query_translator = mysql_query($sql_translator);
			if (!$query_book || !$query_author || !$query_book_image || !$query_rating || !$query_translator){
				return die_with_response(DATABASE_OPERATION_ERROR, $response);
			}
			else{
				$book = mysql_fetch_object($query_book);
				$response['isbn10'] = $book->isbn10;
				$response['isbn13'] = $book->isbn13;
				$response['title'] = $book->title;
				$response['origin_title'] = $book->origin_title;
				$response['alt_title'] = $book->alt_title;
				$response['sub_title'] = $book->sub_title;
				$response['url'] = $book->url;
				$response['alt'] = $book->alt;
				$response['image'] = $book->image;
				$response['publisher'] = $book->publisher;
				$response['pubdate'] = $book->pubdate;
				$response['binding'] = $book->binding;
				$response['price'] = $book->price;
				$response['pages'] = $book->pages;
				$response['author_intro'] = $book->author_intro;
				$response['summary'] = $book->summary;
				$response['catalog'] = $book->catalog;

				$author = array();
				while ($book_author = mysql_fetch_object($query_author)){
					array_push($author, $book_author->author_name);
				}
				$response['author'] = $author;

				$book_image = mysql_fetch_object($query_book_image);
				$response['images']['small'] = $book_image->small;
				$response['images']['medium'] = $book_image->medium;
				$response['images']['large'] = $book_image->large;

				$book_rating = mysql_fetch_object($query_rating);
				$response['rating']['max'] = $book_rating->max;
				$response['rating']['num_raters'] = $book_rating->num_raters;
				$response['rating']['average'] = $book_rating->average;
				$response['rating']['min'] = $book_rating->min;

				$translator = array();
				while ($book_translator = mysql_fetch_object($query_translator)){
					array_push($translator, $book_translator->translator);
				}
				$response['translator'] = $translator;
				array_push($response_book, $response);
			}
		}
	}
	$output = json_encode($response_book,JSON_UNESCAPED_UNICODE);
	return $output;
}
?>