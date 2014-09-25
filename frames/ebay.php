<?php
if (!isset($_GET["country"]))
    die("");

$country = $_GET["country"];
$keywords = str_replace(" ", "%20", $_GET["keywords"]);
?>
<html>
    <head>
        <title>eBay Search Results</title>
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
        <h2>eBay Search Results</h2>
        <div id="results"></div>

        <script>
            function _cb_findItemsByKeywords(root)
            {
                var items = root.findItemsByKeywordsResponse[0].searchResult[0].item || [];
                var html = [];
                html.push('<table>');

                for (var i = 0; i < items.length; ++i)
                {
                    var item = items[i];
                    var title = item.title;
                    var pic = item.galleryURL;
                    var viewitem = item.viewItemURL;
                    var currentPriceObj = item.sellingStatus[0].currentPrice[0];
                    var priceitem = currentPriceObj["@currencyId"] + " " + currentPriceObj["__value__"];

                    if (null != title && null != viewitem)
                    {
                        html.push('<tr><td>' + '<img width="50" height="50" src="' + pic + '" border="0">' + '</td>' +
                                '<td><a href="' + viewitem + '" target="_blank">' + title + '</a></td><td class="text-red">' +
                                priceitem + '</td></tr>');
                    }
                }
                html.push('</table>');
                document.getElementById("results").innerHTML = html.join("");
            }
        </script>

        <!--
        Use the value of your appid for the appid parameter below.
        -->
        <script src=http://svcs.ebay.<?= $country ?>/services/search/FindingService/v1?SECURITY-APPNAME=crawlera-e2ee-446d-a500-8659146df91c&OPERATION-NAME=findItemsByKeywords&SERVICE-VERSION=1.0.0&RESPONSE-DATA-FORMAT=JSON&callback=_cb_findItemsByKeywords&REST-PAYLOAD&keywords=<?= $keywords ?>&paginationInput.entriesPerPage=10>
        </script>
    </body>
</html>