<?php
    set_time_limit(0);

    function CheckAuthorization()
    {
        $headers = getallheaders();
        if (isset($headers['Authorization']))
        {
            include('config.php');
            if ($headers['Authorization'] == $auth_token)
            {
                return true;
            }
        }
        return false;
    }

    function getData($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url."?time=".time());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);   
        $raw = json_decode(json_encode((array) simplexml_load_string($output)), 1);
        return $raw;
    }

    function do_action($url, $new_parms=array())
    {
        include('config.php');
        $oauth_consumer_key = $plurk_app_key;
        $oauth_consumer_secret = $plurk_app_secret;
        $oauth_token = $plurk_oauth_token;
        $oauth_token_secret = $plurk_oauth_secret;   
        $oauth_nonce = rand(10000000,99999999);     
        $oauth_timestamp = time();
        $parm_array = array("oauth_consumer_key" => $oauth_consumer_key,
                            "oauth_nonce" => $oauth_nonce,
                            "oauth_consumer_key" => $oauth_consumer_key,
                            "oauth_signature_method" => "HMAC-SHA1",
                            "oauth_timestamp" => $oauth_timestamp,
                            "oauth_token" => $oauth_token,
                            "oauth_version" => "1.0");
        $parm_array = array_merge($parm_array, $new_parms);
        $base_string = sort_data($parm_array);
        $base_string = "POST&".rawurlencode($url)."&".rawurlencode($base_string);
        $key = rawurlencode($oauth_consumer_secret)."&".rawurlencode($oauth_token_secret);
        $oauth_signature = rawurlencode(base64_encode(hash_hmac("sha1", $base_string, $key, true)));

        $parm_array = array_merge($parm_array, array("oauth_signature"=>$oauth_signature));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, sort_data($parm_array));
        $data = curl_exec($ch);

        curl_close($ch);
        return json_decode($data, TRUE);
    }
    
    function uploadImageFromFile($imageUrl)
    {
        include('config.php');
        $client_id = $imgur_app_key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $imageUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $img = curl_exec($ch);
        curl_close($ch);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json?time='.time());
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => $img));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($ch);
        echo curl_error($ch);
        curl_close($ch);
        $reply = json_decode($reply, TRUE);
        return $reply['data']['link'];
    }

    function uploadImage($imageUrl)
    {
        include('config.php');
        $client_id = $imgur_app_key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json?time='.time());
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => $imageUrl));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $reply = curl_exec($ch);
        echo curl_error($ch);
        curl_close($ch);
        $reply = json_decode($reply, TRUE);
        return $reply['data']['link'];
    }

    function sort_data($data)
    {
        ksort($data);
        $string="";
        foreach($data as $key=>$val)
        {
            if($string=="")
            {
                $string = $key."=".$val;
            }
            else
            {
                $string .= "&".$key."=".$val;
            }
        }
        return $string;
    }
?>