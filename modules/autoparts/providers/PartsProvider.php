<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 21.04.15
 * Time: 13:35
 */

namespace app\modules\autoparts\providers;

use SoapClient;
use Exception;
use SimpleXMLElement;
use app\modules\autoparts\models\PartProviderUserSearch;

class PartsProvider
{
    public $_wsdl_uri;   //Ссылка на WSDL-документ сервиса
    public $_soap_client = false;                                                    //Объект SOAP-клиента
    public $_errors;
    public $methods;
    public $options = ['soap_version' => SOAP_1_1];
    public $login;
    public $store_id;
    public $password;
    public $find=false;
    public $marga = 1;
    public $errors = [];
    public $article = 'ostatki';
    public $name = '';
    public $id=1;//id провайдера в таблице part_provider
    public $fields = [
        "code" => "",//Номер
        "name" => "", //Информация
        "manufacture" => "", //Производитель
        "price" => "", //Цена
        "quantity" => "", //Количество
        "srok" => "", //Доставка
        "srokmin" => "", //Доставка
        "srokmax" => "", //Доставка
        "provider" => "", //Поставщик
        "pricedate"=>"",//Обновление цены
        "estimation"=>"",//статистика поставок
    ];


    public function __construct($params)
    {
        $this->setData($params);
        //Инициализация
        if ($this->find) {
            if (!$this->init()) {
                $this->errors[] = 'Ошибка соединения с сервером ' . $this->name . ': Не может быть инициализирован класс SoapClient';
//            return false;
            }
        }
    }
public function setData($params){

    foreach ($params as $property => $value) {
        if(property_exists($this,$property)){$this->$property = $value;}
    }

    $puser = new PartProviderUserSearch();

    $p = $puser->getUserProvider(['store_id' => $params['store_id'], 'provider_id' => $this->id]);
//    var_dump($p);die;
    if (count($p)>0) {
        $this->login= $p[0]->attributes['login'];
        $this->store_id= $p[0]->attributes['store_id'];
        $this->password = $p[0]->attributes['password'];
        $this->marga=$p[0]->attributes['marga']/100+1;
        $this->find=true;

    }
    else
    {
        $this->find=false;
    }
}
    public function getData()
    {
        $data = \Yii::$app->request->queryParams;
        $data['login'] = $this->login;
        $data['password'] = $this->password;
     return $data;

    }

    /**
     * Инициализирует класс, создаёт объект SOAP-клиента и открывает соединение
     *
     * @param &array $errors ссылка на текущий массив ошибок
     * @return true в случае успеха, false при ошибке
     */

    public function init()
    {

            try {
                $this->_soap_client = new SoapClient($this->_wsdl_uri, $this->options);
                $this->find = true;
            } catch (Exception $e) {
                $this->errors[] = 'Произошла ошибка связи с сервером ' . $this->name . '. ' . $e->getMessage();
                $this->find= false;
            }
//            var_dump($this->_soap_client,$this->find);die;
        return $this->find;
    }

    /**
     * query
     *
     * Выполняет запрашиваемый метод сервиса
     *
     * @param string $method имя метода
     * @param string $requestData данные запроса
     * @param &array $errors ссылка на текущий массив ошибок
     * @return объект SimpleXMLElement в случае успеха, false при ошибке
     */
    public function soap($method)
    {
        if(!$this->find){return false;}
        $method_xml = 'xml' . $method;
        /** $metod_xml имя функции которая возвращает данные для метода $method*/
        $requestData = $this->$method_xml();
        if ($requestData) {
            $result = $this->_soap_client->$method($requestData);
        } else return false;
        try {
            $result = new SimpleXMLElement($this->getResultXML($result, $method));
        } catch (Exception $e) {
            $this->errors[] = 'Ошибка сервиса ' . $this->name . ': полученные данные не являются корректным XML';
            return false;
        }
        //Проверка ответа на ошибки
        if (isset($result->error)) {
            $this->errors[] = 'Ошибка сервиса ' . $this->name . ': ' . (string)$result->error->message;
            if ((string)$result->error->stacktrace)
                $this->errors[] = 'Отладочная информация: ' . (string)$result->error->stacktrace;
            return false;
        }
        return $result;
    }

    public function query($method)
    {

        //Выполнение запроса
//        var_dump($this->_soap_client->__getFunctions(),$method,$requestData);die;
        $result = $this->soap($method);
        //Закрытие соединение
//        $this->close();
        return $result;
    }

    /**
     * close
     *
     * Закрывает соединение
     *
     * @param void
     * @return void
     */
    public function close()
    {
        if ($this->find) {
            $this->find = false;
            $this->_soap_client = false;
        }
    }

    /**Поиск запчастей*/
    public function findDetails(&$errors)
    {
        if (!$this->find){return [];}
        $xml = $this->query($this->methods['FindDetails'], $errors);

        $data = $this->parseSearchResponseXML($xml);

        return $this->formatSearchResponse($data);

    }

    public function generateRandom($maxlen = 32)
    {
        $code = '';
        while (strlen($code) < $maxlen) {
            $code .= mt_rand(0, 9);
        }
        return $code;
    }

    /**
     * /преобразует XML ответ поиска в массив
     */

    public function parseSearchResponseXML($xml)
    {
        $data = array();
        if (!$xml){return $data;}
        foreach ($xml->rows->row as $row) {
            $_row = array();
            foreach ($row as $key => $field) {
                $_row[(string)$key] = (string)$field;
            }
            $data[] = $_row;
        }
        return $data;
    }

    public function formatSearchResponse($data)
    {
        $ret = [];
        $fields = $this->fields;
//        var_dump($data);die;
        foreach ($data as $key => $row) {
            foreach ($fields as $field => $value) {
                if (isset($row[$value])) {
                    $ret[$key][$field] = $row[$value];
                } else {
                    $ret[$key][$field] = "";
                }
                $method = "update_" . $field;
                $ret[$key][$field] = method_exists($this, $method) ? $this->$method($ret[$key]) : $ret[$key][$field];

            }
        }
        return $ret;
    }

    public function getResultXML($result, $method)
    {
        $resultKey = $method . 'Result';
        return $result->$resultKey;
    }

    public function update_price($value)
    {
        $price=$value['price'];
        $nval = $price * $this->marga;
        $rval = round($nval);
        $nval = ($rval > $nval) ? $rval : $rval + 1;
        return $nval;
    }

    public function update_reference($value)
    {
        return $this->generateRandom(9);
    }

    public function update_provider($value)
    {
        return $this->name.'('.$this->store_id.')';
    }

    public function update_srokmin($value)
    {
        return $value['srokmin'] + 1;
    }

    public function update_srokmax($value)
    {
        return $value['srokmax'] + 1;
    }

    public function update_quantity($value)
    {

        $q = '';
        $d = 0;
        $avalue = str_split($value['quantity']);
        foreach ($avalue as $n) {
            $q .= (is_numeric($n)) ? $n : '';
            if ($n == '>') {
                $d = 1;
            }
            if ($n == '<') {
                $d = -1;
            }
        }
        $q += $d;
        return $q;

    }
    public function update_srok($value){

        return $value['srokmin'] . (($value['srokmin'] < $value['srokmax']) ? '-' . $value['srokmax'] : '');
    }


}
