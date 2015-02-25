<?php 
	include("plurk.php");

	$raw = getData("http://opendata.cwb.gov.tw/opendata/MFC/F-C0032-009.xml");
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
	}
?>