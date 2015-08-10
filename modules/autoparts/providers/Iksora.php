<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 20.04.15
 * Time: 10:42
 */

namespace app\modules\autoparts\providers;

use SimpleXMLElement;
//use SoapClient;

class Iksora extends PartsProvider
{
    public $contractid = "91001";
    public $nodes = [
        ['field' => 'maker', 'title' => 'Производитель', 'enable' => true, 'column' => 1,
            'items' => [
                ['field' => 'id', 'title' => 'код', 'enable' => false,],
                ['field' => 'name', 'title' => 'название', 'enable' => true,]
            ],

        ],
        ['field' => 'detailnumber', 'title' => 'Номер детали', 'enable' => true,],
        ['field' => 'detailname', 'title' => 'Наименование', 'enable' => true, 'option' => 'class="part-name"',],
        ['field' => 'quantity', 'title' => 'Наличие (шт.)', 'enable' => true,],
        ['field' => 'lotquantity', 'title' => 'Заказ от (шт.)', 'enable' => true, 'option' => 'title="Минимальная партия заказа по которой действует цена на товар"'],
        ['field' => 'dayswarranty', 'title' => 'Срок доставки', 'enable' => true, 'column' => 2,],
        ['field' => 'estimation', 'enable' => true,],
        ['field' => 'price', 'title' => 'Цена', 'enable' => true, 'style' => '',],
        ['field' => 'ball', 'title' => 'Баллы', 'enable' => true, 'option' => 'class="part-bonus" title="Количество начисляемых баллов. Баллы начисляются при покупке товара через сайт! Начисленные баллы становятся активными по истечении 14 дней с момента покупки."',],
        ['field' => 'pricedestination', 'title' => 'Источник цен', 'enable' => false,],
        ['field' => 'days', 'title' => 'Срок доставки', 'enable' => false,],
        ['field' => 'regionname', 'title' => 'Регион', 'enable' => false,],
        ['field' => 'orderrefernce', 'title' => 'orderrefernce', 'enable' => false,],
        ['field' => 'pricedate', 'title' => 'Дата цены', 'enable' => false,],
        ['field' => 'groupid', 'title' => 'Группа', 'enable' => false,],
    ];



    public static function nameProvider()
    {
        return 'Нижний Новгород';
    }
    public function getData()
    {

        $data = parent::getData();
        $defaults = array(
            'detailnumber' => (isset($data['article']))?$data['article']:$this->article,
            'makerid' => '',
            'contractid' => '91001',
//            'Login' => $this->login,
//            'Password' => $this->password,
        );
        $data = array_merge($defaults, $data);
        return $data;
    }

    public function xmlFindDetailsXML()
    {
        $data = $this->getData();

        $xml = ['DetailNumber'=> $data['detailnumber'],
                'MakerID'=>$data['makerid'],
                'ContractID'=>$data['contractid'],
                'Login'=>$data['login'],
                'Password'=>$data['password']];


        return $xml;
    }

    public function xmlFindDetailsStockXML(){
        $xml=$this->xmlFindDetailsXML();
        return  $xml;
    }

    public function getResultXML($result,$method){
        $result=parent::getResultXML($result,$method);

        return $result->any;
    }
    public function parseSearchResponseXML($xml) {
        $data = array();

        foreach($xml->row as $row) {
            $_row = array();
            foreach($row as $key => $field) {
                $_row[(string)$key] = (string)$field;
            }
            $data[] = $_row;
        }
//        var_dump($data);die;

        return $data;
    }
    public function update_estimation($value){
        $summa=0;
        $count=0;
        $nval=trim($value['estimation']);
        $n=strlen($nval);
        for($с=0;$с<$n;$с++){
            $b=is_numeric($nval[$с]);
            $summa+=($b)?$nval[$с]:0;
            $count+=($b)?1:0;
        }

        $estimation=round(($count>0)?($summa/$count)*20:0,0);
        return $estimation;
    }
    public function update_groupid($value){

        return $value['groupid'];
    }
    public function soap($method){
        $method_xml='xml'.$method;
        /**        Для получения входящего xml нужно описать функцию, которая возвращает результат для запроса.*/
        $requestData=$this->$method_xml();
        $result = $this->_soap_client->$method($requestData);
//        var_dump($result);die;
        $result = new SimpleXMLElement($this->getResultXML($result, $method));
//        var_dump($result);die;

        return $result;
    }
    public function validate($value){
        if (parent::validate($value)==false){return false;}
        if ($value['sklad'] != 'АВТО-ИКСОРА СКЛАД') {
            return false;
        }
        return true;
    }
}