<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 20.04.15
 * Time: 10:42
 */

namespace app\modules\autoparts\providers;


class Partkom extends PartsProvider
{

 public static function nameProvider()
    {
        return 'Партком';
    }
    public function getData()
    {
        $data = parent::getData();
        $defaults = array(
            'number' => (isset($data['article']))?$data['article']:$this->article,//Номер искомой детали
            'makerid' => '',                                                //Уникальный идентификатор производителя в системе «ПартКом».
                                                                            //Может быть получен из справочника производителей MakersDict.
            'findSubstitutes'=>true,                                        //Флаг для поиска с заменами и аналогами или без них.
            'store'=>false,                                                 //Флаг для поиска только в наличии склада «ПартКом».
            'reCross'=>false,                                               //Флаг для включения в результаты кроссов к найденным заменам и аналогам.
//            'login' => $this->login,
//            'password' => $this->password,
        );
        $data = array_merge($defaults, $data);
        return $data;
    }

    public function xmlFindDetail()
    {
        $data = $this->getData();
//        var_dump($data);die;
        $xml = ['number'=> $data['number'],
                'makerid'=>$data['makerid'],
                'findSubstitutes'=>$data['findSubstitutes'],
                'store'=>$data['store'],
                'reCross'=>$data['reCross'],
                'login'=>$data['login'],
                'password'=>$data['password']];
//       return array('FindDetails' =>$xml);
        return $xml;
    }

    public function getResultXML($result,$method){
        $result=parent::getResultXML($result,$method);
        return $result->any;
    }
    public function parseSearchResponseXML($xml) {
        $data = [];
//        var_dump($xml,1212);die;
        foreach($xml as $row) {
            $_row = [];
            foreach($row as $key => $field) {
                $_row[(string)$key] = (string)$field;
            }
            $data[] = $_row;
        }
        return $data;
    }
    public function soap($method){
        $method_xml='xml'.$method;
        /**        Для получения входящего xml нужно описать функцию, которая возвращает результат для запроса.*/
        $requestData=$this->$method_xml();
        $result = $this->_soap_client->$method(
            $requestData['login'],
            $requestData['password'],
            $requestData['number'],
            $requestData['makerid'],
            $requestData['findSubstitutes'],
            $requestData['store'],
            $requestData['reCross']
        );
        return $result;
    }
    public function update_estimation($value){
        return 50;
    }
    public function update_sklad($value){
//        var_dump($value);die;
        return $value['sklad'].'-'.$value['skladid'];
    }
    public function update_groupid($value){
//0 - Original - Искомая деталь;
//1 - ReplacementOriginal - Оригинальная замена на искомую деталь (замена того же производителя);
//2 - ReplacementNonOriginal - Не оригинальная замена (аналог) на искомую деталь (замена от другого производителя);
//3 - ReCross - Кросс к замене или аналогу искомой детали.
// 0 - оригинальная деталь; 1 - оригинальная замена; 2 - неоригинальная замена
    if ($value['groupid']=='Original'){return 0;}
    if ($value['groupid']=='ReplacementOriginal'){return 1;}
    if ($value['groupid']=='ReplacementNonOriginal'){return 2;}
    if ($value['groupid']=='ReCross'){return 2;}
    }
}