<?php
namespace app\modules\netcat;

use \yii\base\Module as BaseModule;

class Netcat extends BaseModule
{
    public static function remote_user_id(){
    return self::get_curl('http://www.kolesa-darom.ru/auto-parts/userlogin/',[]);

    }
    public static function get_curl($url,$params)
    {
//        $url.='?'.http_build_query($params);
        $myCurl = curl_init($url);

        curl_setopt_array($myCurl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER=>false
        ));
        $response = curl_exec($myCurl);
        curl_close($myCurl);
        return $response;
    }
}

