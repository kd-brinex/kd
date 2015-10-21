<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 13.08.15
 * @time: 11:59
 */

namespace app\modules\autoparts\controllers;

use app\modules\tovar\models\Tovar;

class DefaultController extends ProviderController
{
    public function actionIndex(){
//        ini_set("soap.wsdl_cache_enabled", false);
//        ini_set("soap.wsdl_cache_ttl", 0);
//        ini_set("soap.wsdl_cache", false);
//
//        $soap = new \SoapClient('http://ws.emex.ru/EmExInmotion.asmx?WSDL', ['soap_version' => 2, 'trace' => true]);
//        $soap->InMotion_Consumer_Get(['login' => '490348', 'password' => 'EfFRf0GY', 'globalIds' => [84697563, 84556406]]);
//        var_dump($soap->__getFunctions());
//        var_dump($soap->__getLastRequest());
//        echo $soap->__getLastResponse();
//        var_dump(Tovar::getProviderOrderState(['order_id' => 123]));
    }
}