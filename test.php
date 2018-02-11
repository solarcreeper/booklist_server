<?php
function send_post($url, $post_data) {  
  
  $postdata = http_build_query($post_data);  
  $options = array(  
    'http' => array(  
      'method' => 'POST',  
      'header' => 'Content-type:application/x-www-form-urlencoded',  
      'content' => $postdata,  
      'timeout' => 3 // 超时时间（单位:s）  
    )  
  );  
  $context = stream_context_create($options);  
  $result = file_get_contents($url, false, $context);  
  return $result;  
}

$response["isbn10"] ="711123183X";
$response["isbn13"] ="9787111231837";
$response["title"] ="数据结构与算法分析";
$response["originTitle"] ="Data Structures and Algorithm Analysis in Java";
$response["altTitle"] ="Data Structures and Algorithm Analysis in Java";
$response["subTitle"] ="Java语言描述";
$response["url"] ="https:\/\/api.douban.com\/v2\/book\/26296119";
$response["alt"] ="https:\/\/book.douban.com\/subject\/26926119\/";
$response["image"] ="https:\/\/img3.doubanio.com\/mpic\/s32259732.jpg";
$response["images"]["small"] ="https:\/\/img3.doubanio.com\/spic\/s32592732.jpg";
$response["images"]["large"] ="https:\/\/img3.doubanio.com\/lpic\/s32592732.jpg";
$response["images"]["medium"] ="https:\/\/img3.doubanio.com\/mpic\/s32259732.jpg";
$response["author"] = ["Joshua Bloch"];
$response["translator"] =["test"];
$response["publisher"] ="Addison-Wesley";
$response["pubdate"] ="2008-05-28";
$response["rating"]["max"] ="10";
$response["rating"]["num_raters"] ="245";
$response["rating"]["average"] ="9.3";
$response["rating"]["min"] ="0";
$response["binding"] ="Paperback";
$response["price"] ="USD 54.99";
$response["pages"] ="346";
$response["authorIntro"] ="Joshua Bloch";
$response["summary "] ="Written";
$response["catalog "] ="Index";

$data = array();
// $data['book'] = json_encode($response);
$data['action'] = "getBookList";
// $data['action'] = "addWantedBook";
// $data['action'] = "removeWantedBook";

// $data['book'] = json_encode($response);
$data['user_id'] = "1";
$data['start'] = "0";
$data['count'] = "5";

$url = "http://localhost:8080/booklist/service.php";
$result = send_post($url, $data);
echo $result;
?>