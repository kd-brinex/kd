<?php
namespace app\modules\netcat;

use \yii\base\Module as BaseModule;

class Netcat extends BaseModule
{
    public static function remote_add_catalog($params){
//        var_dump($params);die;
    return self::get_curl('http://www.kolesa-darom.ru/parts/add_catalog.php',$params);

    }
    public static function get_curl($url,$params)
    {
        $url.='?'.http_build_query($params);
//        var_dump($url);die;
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

