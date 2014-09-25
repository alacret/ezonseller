<?php

if(!isset($_GET["country"]))
    die("");

include '../lib/AmazonECS.class.php';
include '../php_app/conf.php';

define('AWS_API_KEY', 'AKIAJOP7VQTHRH2X3JUQ');
define('AWS_API_SECRET_KEY', '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');
define('AWS_ASSOCIATE_TAG', 'ferrecabimas-20');

defined('AWS_API_KEY') or define('AWS_API_KEY', 'API KEY');
defined('AWS_API_SECRET_KEY') or define('AWS_API_SECRET_KEY', 'SECRET KEY');
defined('AWS_ASSOCIATE_TAG') or define('AWS_ASSOCIATE_TAG', 'ASSOCIATE TAG');

$country = $_GET["country"];
$keywords = $_GET["keywords"];
$category = $_GET["category"];

$limit = 10;
$page = 1;
$start = 0;
$sort = isset($_GET["sort"]) ? true : false;

if ($category == 'All' && $sort){
    exit("{success:false,error:'You have to choose a category for sort to be available'}");
}

try {
    // if you leave lang blank it will be US.
    $amazonEcs = new AmazonECS(AWS_API_KEY, AWS_API_SECRET_KEY, $country, AWS_ASSOCIATE_TAG);
    $amazonEcs->returnType(AmazonECS::RETURN_TYPE_ARRAY);
    //$response = $amazonEcs->responseGroup('Large')->search($keywords);
    if ($sort)
        $response = $amazonEcs->category($category)->page($page)->responseGroup('Large')->sort("salesrank")->search($keywords);
    else
        $response = $amazonEcs->category($category)->page($page)->responseGroup('Large')->search($keywords);

    $array = array();
    $response = $response["Items"];
    //die(json_encode($response));
    //Checking if the request was succesfull    
    if (!$response["Request"]["IsValid"] == "True" or isset($response["Request"]["Errors"])) {
        $array["success"] = false;
        $array["error"] = $response["Request"]["Errors"]["Error"]["Message"];
    } else {
        $array["success"] = true;
        if (intval($response["TotalPages"]) > 10)
            $array["results"] = 100;
        else
            $array["results"] = $response["TotalResults"];
        $array["TotalPages"] = $response["TotalPages"];
        $i = 0;
        foreach ($response["Item"] as $item) {
            //if($i> 1)
            //if(!isset($item["SalesRank"]))
            //die(json_encode($item));
            if (isset($item["SalesRank"]))
                $rank = $item["SalesRank"];
            else
                $rank = 0;
            
            $array["items"][$i]["amazon_link"] = $item["DetailPageURL"];
            $array["items"][$i]["image_url"] = $item["SmallImage"]["URL"];
            $array["items"][$i]["image_width"] = $item["SmallImage"]["Width"]["_"];
            $array["items"][$i]["image_height"] = $item["SmallImage"]["Height"]["_"];
            $array["items"][$i]["ASIN"] = $item["ASIN"];
            $array["items"][$i]["country"] = $country;
            $array["items"][$i]["price"] = $item["OfferSummary"]["LowestNewPrice"]["FormattedPrice"];
            $array["items"][$i]["total_new"] = $item["OfferSummary"]["TotalNew"];
            $array["items"][$i]["_category"] = $category;
            $array["items"][$i]["rank"] = isset($item["SalesRank"]) ? $item["SalesRank"] : $rank;
            $array["items"][$i]["product_name"] = isset($item["ItemAttributes"]["Title"]) ? $item["ItemAttributes"]["Title"] : '';
            $array["items"][$i]["category"] = isset($item["ItemAttributes"]["Binding"]) ? $item["ItemAttributes"]["Binding"] : '';
            $array["items"][$i]["ccc_link"] = "http://camelcamelcamel.com/" . $array["items"][$i]["product_name"] . "/product/" . $item["ASIN"];
            $array["items"][$i]["ebay_link"] = get_ebay_link($country, $array["items"][$i]["product_name"]);
            $array["items"][$i]["amazon_search_link"] = "http://www.amazon." . $country . "/s/ref=nb_sb_noss?url=search-alias%3Daps&field-keywords=" . str_replace(" ", "+", $array["items"][$i]["product_name"]);
            $i++;
        }
    }
    $items = $array["items"];
} catch (Exception $e) {
    echo $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Amazon Search results</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            *{
                font-family: Arial;
            }
            .text-red{
                color: red;
                font-weight: bold;
                width: 100px;
                vertical-align: top;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h2>Amazon results</h2>
        <table>
            <?php foreach ($items as $item):?>
            <tr>
                <td><img width="50" height="50" src="<?= $item["image_url"]?>" /></td>
                <td>
                    <a href="<?= $item["amazon_link"]?>" target="_blank"><?= $item["product_name"]?></a><br/>
                    <small>Total new: <?= $item["total_new"]?></small>
                </td>
                <td class="text-red"><?= $item["price"]?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </body>
</html>