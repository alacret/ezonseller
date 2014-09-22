<?php

include '../lib/AmazonECS.class.php';
include 'conf.php';

define('AWS_API_KEY', 'AKIAJOP7VQTHRH2X3JUQ');
define('AWS_API_SECRET_KEY', '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');
define('AWS_ASSOCIATE_TAG', 'ferrecabimas-20');

defined('AWS_API_KEY') or define('AWS_API_KEY', 'API KEY');
defined('AWS_API_SECRET_KEY') or define('AWS_API_SECRET_KEY', 'SECRET KEY');
defined('AWS_ASSOCIATE_TAG') or define('AWS_ASSOCIATE_TAG', 'ASSOCIATE TAG');


header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: text/json');

$country = $_GET["country"];
$keywords = $_GET["keywords"];
$category = $_GET["category"];

$limit = $_GET["limit"];
$page= $_GET["page"];
$start = $_GET["start"];
$sort = isset($_GET["sort"]) ? true: false;

if($category == 'All' && $sort)
	exit("{success:false,error:'You have to choose a category for sort to be available'}"); 
try
{
    // get a new object with your API Key and secret key. Lang is optional.
    // if you leave lang blank it will be US.
    $amazonEcs = new AmazonECS(AWS_API_KEY, AWS_API_SECRET_KEY, $country, AWS_ASSOCIATE_TAG);
	$amazonEcs->returnType(AmazonECS::RETURN_TYPE_ARRAY);
	//$response = $amazonEcs->responseGroup('Large')->search($keywords);
	if($sort)
		$response = $amazonEcs->category($category)->page($page)->responseGroup('Large')->sort("salesrank")->search($keywords);
	else 
		$response = $amazonEcs->category($category)->page($page)->responseGroup('Large')->search($keywords);

	$array =  array();
	$response = $response["Items"];
	//die(json_encode($response));
	//Checking if the request was succesfull
	if(!$response["Request"]["IsValid"] == "True" or isset($response["Request"]["Errors"])){
		$array["success"] = false;
		$array["error"] = $response["Request"]["Errors"]["Error"]["Message"];
	}else{
		$array["success"] = true;
		if(intval($response["TotalPages"]) > 10)
			$array["results"] = 100;
		else
			$array["results"] = $response["TotalResults"];
		$array["TotalPages"] = $response["TotalPages"];
		$i = 0;
		foreach ($response["Item"] as $item) {
			//if($i> 1)
			//if(!isset($item["SalesRank"]))
				//die(json_encode($item));
			if(isset($item["SalesRank"]))
				$rank = $item["SalesRank"];
			else 
				$rank = 0;
			//echo $i . ' : ' . $item["ItemAttributes"]["Binding"];
			
			$array["items"][$i]["amazon_link"] = $item["DetailPageURL"];
			$array["items"][$i]["image_url"] = $item["SmallImage"]["URL"];
			$array["items"][$i]["image_width"] = $item["SmallImage"]["Width"]["_"];
			$array["items"][$i]["image_height"] = $item["SmallImage"]["Height"]["_"];
			$array["items"][$i]["ASIN"] = $item["ASIN"];
			$array["items"][$i]["rank"] = isset($item["SalesRank"]) ? $item["SalesRank"] : $rank;
			$array["items"][$i]["product_name"] = isset($item["ItemAttributes"]["Title"]) ? $item["ItemAttributes"]["Title"] : '';
			$array["items"][$i]["category"] = isset($item["ItemAttributes"]["Binding"]) ? $item["ItemAttributes"]["Binding"] : '';
			$array["items"][$i]["ccc_link"] = "http://camelcamelcamel.com/".$array["items"][$i]["product_name"]."/product/".$item["ASIN"];
			$array["items"][$i]["ebay_link"] = get_ebay_link($country,$array["items"][$i]["product_name"]);
			$array["items"][$i]["amazon_search_link"] = "http://www.amazon.".$country."/s/ref=nb_sb_noss?url=search-alias%3Daps&field-keywords=".str_replace(" ", "+", $array["items"][$i]["product_name"]);
			$i++;
		}
	}
	echo json_encode($array);
	
    // If you are at min version 1.3.3 you can enable the requestdelay.
    // This is usefull to get rid of the api requestlimit.
    // It depends on your current associate status and it is disabled by default.
    // $amazonEcs->requestDelay(true);

    // for the new version of the wsdl its required to provide a associate Tag
    // @see https://affiliate-program.amazon.com/gp/advertising/api/detail/api-changes.html?ie=UTF8&pf_rd_t=501&ref_=amb_link_83957571_2&pf_rd_m=ATVPDKIKX0DER&pf_rd_p=&pf_rd_s=assoc-center-1&pf_rd_r=&pf_rd_i=assoc-api-detail-2-v2
    // you can set it with the setter function or as the fourth paramameter of ther constructor above
    //$amazonEcs->associateTag(AWS_ASSOCIATE_TAG);

    // changing the category to DVD and the response to only images and looking for some matrix stuff.
    //$response = $amazonEcs->category('DVD')->responseGroup('Large')->search("Matrix Revolutions");
   //var_dump($response);

    // from now on you want to have pure arrays as response
    //$amazonEcs->returnType(AmazonECS::RETURN_TYPE_ARRAY);

    // searching again
    //$response = $amazonEcs->search('Bud Spencer');
    //var_dump($response);

    // and again... Changing the responsegroup and category before
    //$response = $amazonEcs->responseGroup('Small')->category('Books')->search('PHP 5');
    //var_dump($response);

    // category has been set so lets have a look for another book
    //$response = $amazonEcs->search('MySql');
    //var_dump($response);

    // want to look in the US Database? No Problem
    //$response = $amazonEcs->country('com')->search('MySql');
    //var_dump($response);

    // or Japan?
    //$response = $amazonEcs->country('co.jp')->search('MySql');
    //var_dump($response);

   // Back to DE and looking for some Music !! Warning "Large" produces a lot of Response
   //$response = $amazonEcs->country('de')->category('Music')->responseGroup('Small')->search('The Beatles');
   //var_dump($response);

   // Or doing searchs in a loop?
   //for ($i = 1; $i < 4; $i++){
     //$response = $amazonEcs->search('Matrix ' . $i);
     //var_dump($response);
   //}

   // Want to have more Repsonsegroups?                         And Maybe you want to start with resultpage 2?
   //$response = $amazonEcs->responseGroup('Small,Images')->optionalParameters(array('ItemPage' => 2))->search('Bruce Willis');
   //var_dump($response);

   // With version 1.2 you can use the page function to set up the page of the resultset
   //$response = $amazonEcs->responseGroup('Small,Images')->page(3)->search('Bruce Willis');
   //var_dump($response);
}
catch(Exception $e){
  echo $e->getMessage();
}