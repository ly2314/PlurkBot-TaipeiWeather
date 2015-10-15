<?php
	include("functions.php");
    if (!CheckAuthorization())
    {
        exit;
    }
    
    date_default_timezone_set("Asia/Taipei");
    $img_url = uploadImageFromFile('http://yandex.ru/images/today');
    
    $url = "https://www.yandex.com/images/?uinfo=sw-1920-sh-1080-ww-1920-wh-695-pd-1-wp-16x9_1920x1080";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $html = curl_exec($ch);
    curl_close($ch);
    
    $dom = new DOMDocument;
    $dom->loadHTML($html);
    $spans = $dom->getElementsByTagName('span');
    $author = 'test';
    $description = 'test';
    foreach ($spans as $span)
    {
        if ($span->getAttribute('class') == 'b-501px__name')
        {
            $author = $span->textContent;
            break;
        }
    }
    $ps = $dom->getElementsByTagName('p');
    foreach ($ps as $p)
    {
        if ($p->getAttribute('class') == 'b-501px__description')
        {
            $description = $p->textContent;
            break;
        }
    }
    
    $message = $img_url."\r\nYandex每日桌布\r\n**".$author."**\r\n#Yandex每日桌布";
    $qulifier = 'shares';
    print_r($message);
    $post_info = do_action("http://www.plurk.com/APP/Timeline/plurkAdd", array("content" => rawurlencode($message), "qualifier" => $qulifier, "lang" => 'tr_ch'));
    print_r($post_info);    
    
    $plurk_id = $post_info['plurk_id'];
    print_r($description);
    $post_info = do_action("http://www.plurk.com/APP/Responses/responseAdd", array("content" => rawurlencode($description), "qualifier" => $qulifier, "plurk_id" => $plurk_id, "lang" => 'tr_ch'));
?>