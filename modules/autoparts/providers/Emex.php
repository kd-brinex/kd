<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 20.04.15
 * Time: 10:42
 */

namespace app\modules\autoparts\providers;


class Emex extends PartsProvider
{

    public static function nameProvider()
    {
        return 'Emex';
    }

    public function getData()
    {
        $data = parent::getData();
        $defaults = array(
                            //Номер искомой детали
                        'detailNum' => (isset($data['article'])) ? $data['article'] : $this->article,
                            //Лого фирмы (не обязательно)*
                        'makeLogo' => '',
                            //фильтр по заменам
                            //OriginalOnly - без замен и аналогов;
                            //All - с заменами и аналогами.
                        'substLevel' => 'All',
                            //Фильтр по типу деталей
                            //None - не фильтровать;
                            //FilterOriginalAndReplacements - только искомый номер, новый номер и замены искомого номера;
                            //FilterOriginalAndAnalogs - только искомый номер и аналоги.
                        'substFilter' => 'FilterOriginalAndAnalogs',
                            //PRI; ALT - тип доставки (по умолчанию надо указывать PRI)
                        'deliveryRegionType' => 'PRI',
                            //            'login' => $this->login,
                            //            'password' => $this->password,
            );
            $data = array_merge($defaults, $data);
        return $data;
    }
    public function xmlInsertToBasket2(){
        $data = $this->getData();
        return $data;
    }
    public function xmlFindDetailAdv3()
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
        if (!isset($xml->FindDetailAdv3Result->Details->SoapDetailItem)){return $data;}
        $di=$xml->FindDetailAdv3Result->Details->SoapDetailItem;
        if (!is_array($di)){$rows=[$di];}else{$rows=$di;}
//        var_dump($di, $rows );die;
        foreach ($rows as $row) {
            $_row = [];
//            var_dump($row);die;
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
        /**        Для получения входящего xml нужно описать функцию, которая возвращает результат для запроса.*/
        $requestData = $this->$method_xml();
//        var_dump($requestData);die;
        $result = $this->_soap_client->$method(
            $requestData

        );

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

    public function update_groupid($value){
        switch ($value['groupid']) {
            case 'Original':
                $value['groupid'] = '0';
                break;
            case 'ReplacementOriginal':
                $value['groupid'] = '1';
                break;
            case 'ReplacementNonOriginal':
                $value['groupid'] = '2';
                break;
        }
        return $value['groupid'];
    }
//    public function update_srokmin($value)
//    {
//        return $value['srokmin'] + 4;
//    }
//
//    public function update_srokmax($value)
//    {
//        return $value['srokmax'] + 8;
//    }
}