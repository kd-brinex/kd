<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 27.08.15
 * @time: 13:38
 */

namespace app\helpers;



class BrxDataInspector{

    const XML_DATA = 'Xml';
    const JSON_DATA = 'Json';
    const OBJECT_DATA = 'Object';
    const ARRAY_DATA = 'Array';

    /**
     * Проверка на форма данных в переменной
     * @param $data mixed данные
     * @return int|mixed|\SimpleXMLElement в случае если строка является XML возвращается объект SimpleXMLElement
     */
    public static function getDataFormat($data){
        if(self::isXML($data))
            return self::XML_DATA;

        if(self::isJSON($data))
            return self::JSON_DATA;

        if(is_object($data))
            return self::OBJECT_DATA;

        if(is_array($data))
            return self::ARRAY_DATA;

        return false;
    }

    /**
     * Проверка на формат данных XML
     * @param $data mixed данные
     * @return bool
     */
    public static function isXML($data){
        if(@simplexml_load_string($data))
            return true;

        return false;
    }

    /**
     * Проверка на формат данных JSON
     * @param $data mixed данные
     * @return bool
     */
    public static function isJSON($data){
        if(@json_decode($data, true, $depth = 10000) && !is_int($data) && !ctype_digit($data))
               return true;

        return false;
    }
}