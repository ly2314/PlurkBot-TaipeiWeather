<?php
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://opendata.cwb.gov.tw/opendata/MFC/F-C0032-009.xml");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);   
	$raw = json_decode(json_encode((array) simplexml_load_string($output)), 1);

	include("plurk.php");
	$qulifier = 'says';
	$message = array_values($raw['dataset']['parameterSet']['parameter'])[0]['parameterValue'];
	$post_info = do_action("http://www.plurk.com/APP/Timeline/plurkAdd", array("content" => rawurlencode($message), "qualifier" => $qulifier, "lang" => 'tr_ch'));
	print_r($post_info);
	$plurk_id = $post_info['plurk_id'];
	$id = 0;
	foreach ($raw['dataset']['parameterSet']['parameter'] as $item)
	{
		++$id;
		if ($id == 1)
			continue;
		$post_info = do_action("http://www.plurk.com/APP/Responses/responseAdd", array("content" => rawurlencode($item['parameterValue']), "qualifier" => $qulifier, "plurk_id" => $plurk_id, "lang" => 'tr_ch'));
		print_r($post_info);
	}
?>