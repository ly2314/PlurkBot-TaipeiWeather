<?php
    function getBingData($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url."&time=".time());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);   
        $raw = json_decode(json_encode((array) simplexml_load_string($output)), 1);
        return $raw;
    }

    include("functions.php");
    if (!CheckAuthorization())
    {
        exit;
    }
    date_default_timezone_set("Asia/Taipei");
    $raw = getBingData("http://www.bing.com/HPImageArchive.aspx?format=xml&idx=0&n=1");
    $img_url = uploadImage('http://www.bing.com/'.$raw['image']['urlBase'].'_1920x1080.jpg');
    $message = $img_url."\r\nBing 每日桌布\r\n".$raw['image']['copyright']."\r\n#Bing每日桌布";
    $qulifier = 'shares';
    $post_info = do_action("http://www.plurk.com/APP/Timeline/plurkAdd", array("content" => rawurlencode($message), "qualifier" => $qulifier, "lang" => 'tr_ch'));
    print_r($post_info);
?>