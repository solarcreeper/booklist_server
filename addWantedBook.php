<?php
include ("\base\conn.php");
require_once("\base\dieError.php");
if(!is_null($_POST["book"]) && !is_null($_POST["user_id"])){
	$conn = mysql_open();
	$result = foo();
	echo $result;
	mysql_close($conn);
	
}

function foo(){
	$user_id = $_POST["user_id"];
	$book = json_decode($_POST["book"]);
	$isbn10 = $book->isbn10;
	$isbn13 = $book->isbn13;
	$title = $book->title;
	$originTitle = $book->originTitle;
	$altTitle = $book->altTitle;
	$subTitle = $book->subTitle;
	$url = $book->url;
	$alt = $book->alt;
	$image = $book->image;
	$publisher = $book->publisher;
	$pubdate = $book->pubdate;
	$binding = $book->binding;
	$price = $book->price;
	$pages = $book->pages;
	$authorIntro = $book->authorIntro;
	$summary = $book->summary;
	$catalog = $book->catalog;
	
	$rating = $book->rating;
	$images = $book->images;
	$author = $book->author;
	$translator = $book->translator;

	$query_book = "select book_id from book where isbn10='$isbn10' or isbn13='$isbn13'";
	$query = mysql_query($query_book);
	if(!$query){
		return die_with_response(DATABASE_OPERATION_ERROR, $response);
	}
	else{
		if($result = mysql_fetch_object($query)){
			$book_id = $result->book_id;
		}
		else{
			$insert_book = "insert into book(isbn10, isbn13, title, origin_title, alt_title, sub_title, url, alt, image, publisher, pubdate, binding, price, pages, author_intro, summary, catalog) values('$isbn10','$isbn13','$title','$originTitle','$altTitle','$subTitle','$url','$alt','$image','$publisher','$pubdate','$binding','$price','$pages','$authorIntro','$summary','$catalog')";
			$query = mysql_query($insert_book);
			if(!$query){
				return die_with_response(DATABASE_OPERATION_ERROR, $response);
			}
			else{
				$book_id = mysql_insert_id();
				$rating_max = $rating->max;
				$rating_num_raters = $rating->numRaters;
				$rating_average = $rating->average;
				$rating_min = $rating->min;
	
				$insert_rating = "insert into rating(book_id, max, num_raters, average, min) values('$book_id', '$rating_max', '$rating_num_raters', '$rating_average', '$rating_min')";
				$image_small = $images->small;
				$image_large = $iamges->large;
				$image_medium = $images->medium;
				$insert_images = "insert into book_images(book_id, small, large, medium) values('$book_id', '$image_small', '$image_large', '$image_medium')";
				$query1 = mysql_query($insert_rating);
				$query2 = mysql_query($insert_images);
				if(!$query1 || !$query2){
					return die_with_response(DATABASE_OPERATION_ERROR, $response);
				}
				if(!empty($author)){
					foreach ($author as $key) {
					if(!mysql_query("insert into author(book_id, author_name) values('$book_id', '$key')")){
						return die_with_response(DATABASE_OPERATION_ERROR, $response);
					}
					}
				}
				
				if(!empty($translator)){
					foreach ($translator as $key) {
					if(!mysql_query("insert into translator(book_id, translator_name) values('$book_id', '$key')")){
						return die_with_response(DATABASE_OPERATION_ERROR, $response);
					}
					}
				}
			}
		}
		$query_wanted = "select wanted_id from wanted_books where user_id = '$user_id' and book_id = '$book_id'";
		$query = mysql_query($query_wanted);
		if($result = mysql_fetch_object($query)){
			$response['error_code'] = RESULT_OK;
			$response['wanted_id'] = $result->wanted_id;
		}
		else{
			$insert_wanted = "insert into wanted_books(user_id, book_id) values('$user_id', '$book_id')";
			$query = mysql_query($insert_wanted);
			if(!$query){
				return die_with_response(DATABASE_OPERATION_ERROR, $response);
			}
			else{
				$response['error_code'] = RESULT_OK;
				$response['wanted_id'] = mysql_insert_id();
			}
		}
	}
	$output = json_encode($response,JSON_UNESCAPED_UNICODE);
	return $output;
}
?>