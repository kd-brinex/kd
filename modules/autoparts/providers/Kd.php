<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 20.04.15
 * Time: 10:42
 */

namespace app\modules\autoparts\providers;

use app\modules\tovar\models\Tovar;

class Kd extends PartsProvider
{

    public static function nameProvider()
    {
        return 'KD';
    }

    public function getData()
    {

        $data = [
            'value_char' => $this->article,
            'param_id'=>'ЦО00026'
        ];

        return $data;
    }

    public function xmlFindDetail()
    {
        $data = $this->getData();
        return $data;
    }

    public function getResultXML($result, $method)
    {
        $result = parent::getResultXML($result, $method);
        return $result->any;
    }

    public function parseSearchResponseXML($xml)
    {
        $data = [];
//        var_dump($xml);die;
        foreach ($xml as $row) {
            $_row = [];
            foreach ($row as $key => $field) {
                $_row[(string)$key] = (string)$field;
            }
            $data[] = $_row;
        }
        return $data;
    }

    public function soap($method)
    {
        $method_xml = 'xml' . $method;
//        /**        Для получения входящего xml нужно описать функцию, которая возвращает результат для запроса.*/
        $requestData = $this->$method_xml();
////        var_dump($requestData);die;
//        $result = $this->_soap_client->$method(
//            $requestData
//
//        );
        $result = Tovar::find()->where($requestData)->asArray()->all();

        return $result;
    }

    public function update_sklad($value)
    {
//        var_dump($value);die;
        return $value['sklad'] . '-' . $value['skladid'];
    }

    public function update_estimation($value){
        return round( $value['estimation']);
    }
    public function update_srokmin($value)
    {
        return $value['srokmin'] + 4;
    }

    public function update_srokmax($value)
    {
        return $value['srokmax'] + 8;
    }
}