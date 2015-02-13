<?php
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://opendata.cwb.gov.tw/opendata/MFC/F-C0032-009.xml");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);   
	$raw = json_decode(json_encode((array) simplexml_load_string($output)), 1);
	$data = '';
	$index = 0;
	foreach ($raw['dataset']['parameterSet']['parameter'] as $item)
	{
		++$index;
		if ($index == 2)
			continue;
		$data = $data.$item['parameterValue']."\r\n";
	}

	include("plurk.php");
	$qulifier = 'says';
	$message = $data;
	$post_info = do_action("http://www.plurk.com/APP/Timeline/plurkAdd", array("content" => rawurlencode($message), "qualifier" => $qulifier));
	print_r($post_info);
?>