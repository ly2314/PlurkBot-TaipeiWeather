<?php
    include("functions.php");
    if (!CheckAuthorization())
    {
        exit;
    }
    date_default_timezone_set("Asia/Taipei");
    $raw = getData("http://opendata.cwb.gov.tw/opendata/DIV2/O-A0003-001.xml");
    $message = '現在時刻：'.date("A g").":00。\r\n台北市現在氣溫";
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
    $img_raw = getData("http://opendata.cwb.gov.tw/opendata/MSC/O-B0029-003.xml");
    $img_url = uploadImage($img_raw['dataset']['resource']['uri']);
    $qulifier = 'says';
    $post_info = do_action("http://www.plurk.com/APP/Timeline/plurkAdd", array("content" => rawurlencode($img_url."\r\n".$message), "qualifier" => $qulifier, "lang" => 'tr_ch'));
    print_r($post_info);
?>