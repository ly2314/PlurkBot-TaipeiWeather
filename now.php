<?php
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://opendata.cwb.gov.tw/opendata/DIV2/O-A0003-001.xml");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);   
    $raw = json_decode(json_encode((array) simplexml_load_string($output)), 1);
    $message = '台北市現在氣溫';
    foreach ($raw['location'] as $item)
    {
        if ($item['stationId'] != '466920')
            continue;
        foreach ($item['weatherElement'] as $weather)
        {
            if ($weather['elementName'] == 'TEMP')
            {
                $message = $message.$weather['elementValue']['value'].'°C，';
                break;
            }
        }
        foreach ($item['weatherElement'] as $weather)
        {
            if ($weather['elementName'] == 'HUMD')
            {
                $message = $message.'相對濕度'.((double)$weather['elementValue']['value'] * 100).'%，';
                break;
            }
        }
        foreach ($item['weatherElement'] as $weather)
        {
            if ($weather['elementName'] == '24R')
            {
                $message = $message.'今日累積雨量'.$weather['elementValue']['value'].'毫米。';
                break;
            }
        }
    }
    include("plurk.php");
    $qulifier = 'says';
    $post_info = do_action("http://www.plurk.com/APP/Timeline/plurkAdd", array("content" => rawurlencode($message), "qualifier" => $qulifier, "lang" => 'tr_ch'));
    print_r($post_info);
?>