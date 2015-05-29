<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 20.04.15
 * Time: 10:42
 */

namespace app\modules\autoparts\providers;

use yii\db\Query;

class Kd extends PartsProvider
{
    public function init()
    {

        try {
//            $this->_soap_client = new SoapClient($this->_wsdl_uri, $this->options);
            $this->find = true;
        } catch (Exception $e) {
            $this->errors[] = 'Произошла ошибка связи с сервером ' . $this->name . '. ' . $e->getMessage();
            $this->find = false;
        }
//            var_dump($this->_soap_client,$this->find);die;
        return $this->find;
    }
    public static function nameProvider()
    {
        return 'KD';
    }

    public function getData()
    {
        $data = parent::getData();
        $p['storeid']=(isset($data['store_id'])?$data['store_id']:109);
        $article=(isset($data['article'])?$data['article']:$this->article);
        $article=strtolower($article);
        $p['detailnumber']=str_replace([' ','-'],[],$article);
//        var_dump($p);die;
        return $p;
    }

    public function xmlFindDetails()
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
//        var_dump($data);die;
        return $data;
    }

    public function soap($method)
    {
        $method_xml = 'xml' . $method;
//        /**        Для получения входящего xml нужно описать функцию, которая возвращает результат для запроса.*/
        $requestData = $this->$method_xml();
//        var_dump($requestData);die;
//        $result = $this->_soap_client->$method(
//            $requestData
//
//        );

        $query=new Query();

        $result= $query->from('finddetails')->where($requestData)->all();
//var_dump($result,1);die;
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
        return $value['srokmin'] + 2;
    }

    public function update_srokmax($value)
    {
        return $value['srokmax'] + 2;
    }
}