<?php
function get_ebay_link($country,$product_name){
	switch ($country) {
		case 'com':#USA
			return "http://www.ebay.com/sch/i.html?_trksid=p5197.m570.l1313&_nkw=". str_replace(" ", "+", $product_name);
			break;
		case 'de':#Germany
			return "http://www.ebay.de/sch/i.html?_trksid=p5197.m570.l1313&_nkw=". str_replace(" ", "+", $product_name);
			break;
		case 'co.uk':#United Kingdom
			return "http://www.ebay.co.uk/sch/i.html?_trksid=p5197.m570.l1313&_nkw=". str_replace(" ", "+", $product_name);
			break;
		case 'ca':#Canada
			return "http://www.ebay.ca/sch/i.html?_trksid=p5197.m570.l1313&_nkw=". str_replace(" ", "+", $product_name);
			break;
		case 'fr':#France
			return "http://shop.ebay.fr/i.html?_trksid=m570.l3201&_nkw=". str_replace(" ", "+", $product_name);
			break;
		case 'it':#Italy
			return "http://www.ebay.it/sch/i.html?_trksid=p5197.m570.l1313&_nkw=". str_replace(" ", "+", $product_name);
			break;
		case 'cn':#China
			return "http://www.ebay.cn/gsearch/gsearch.html?ssq= ".str_replace(" ", "+", $product_name)."&sst=all";
			break;
		case 'es':#Spain
			return "http://www.ebay.es/sch/i.html?_trksid=p5197.m570.l1313&_nkw=". str_replace(" ", "+", $product_name);
			break;
		default:
			return "japan.html";
	}
}