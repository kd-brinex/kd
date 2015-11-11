<?php
namespace app\modules\netcat;

use \yii\base\Module as BaseModule;
use linslin\yii2\curl\Curl;
class Netcat extends BaseModule
{
    public static function remote_add_catalog($params){
//        var_dump($params);die;
    return self::get_curl('http://91.218.229.157/parts/add_catalog.php',$params);

    }
    public static function kd_add_catalog($params)
    {
//        var_dump($params);die;
        $url="http://91.218.229.157/kabinet/myautocatalog/";
        $p['marka']=$params['marka'];
        $p['catalog']=$params['region'];
        $p['family']=$params['family'];
        $p['cat_code']=$params['cat_code'];
        $p['option']=$params['option'];
        $p['model_name']=$params['cat_folder'];
        $p['model_code']=$params['cat_folder'];
        $p['version']=1;
        $p['vin']=(!empty($params['vin']))?$params['vin']:'';

        return self::get_curl($url,$p);
    }
    public static function get_curl($url,$params)
    {
        $url.='?'.http_build_query($params);
        $curl = new Curl();
//        var_dump($url);die;
        $curl->setOption('CURLOPT_COOKIESESSION',true);
        return $curl->get($url);
//        return \Yii::$app->getResponse()->redirect($url);



//        var_dump($url);die;
//        $myCurl = curl_init($url);
//
//        curl_setopt_array($myCurl, array(
//            CURLOPT_URL => $url,
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_HEADER=>false
//        ));
//        $response = curl_exec($myCurl);
//        curl_close($myCurl);
//        return $response;
    }
}

