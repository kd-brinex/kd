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
        $this->find = true;
        return $this->find;
    }

    public static function nameProvider()
    {
        return 'KD';
    }

    /**
     * @return mixed
     * getData - формирует параметры WHERE для конструктора запросов
     */
    public function getData()
    {
        $data = parent::getData();
        $p['storeid'] = (isset($data['store_id']) ? $data['store_id'] : 109);
        $article = (isset($data['article']) ? $data['article'] : $this->article);
        $article = strtoupper($article);
        $p['detailnumber'] = str_replace([' ', '-'], [], $article);
        return $p;
    }

//    /**
//     * @return mixed
//     * Вероятно этот метод для этого поставщика можно было не перекрывать
//     */
//    public function xmlFindDetails()
//    {
//        $data = $this->getData();
//        return $data;
//    }
//    /**
//     * @return mixed
//     * Вероятно этот метод для этого поставщика можно было не перекрывать
//     */
//    public function getResultXML($result, $method)
//    {
//        $result = parent::getResultXML($result, $method);
//        return $result->any;
//    }

    /**
     * @param $xml
     * @return array
     * Преобразуем формат данных к единому формату
     */
    public function parseSearchResponseXML($xml)
    {
        $data = [];

        foreach ($xml as $row) {
            $_row = [];
            foreach ($row as $key => $field) {
                $_row[(string)$key] = (string)$field;
            }
            $data[] = $_row;
        }

        return $data;
    }

    /**
     * @param string $method
     * @return array
     * Получаем выборку данных по запросу
     */
    public function soap($method)
    {

        $requestData = $this->getData();

        $query = new Query();

        $result = $query->from('finddetails')->where($requestData)->all();

        return $result;
    }

    public function update_sklad($value)
    {
        return $value['sklad'] . '-' . $value['skladid'];
    }

    public function update_estimation($value)
    {
        return round($value['estimation']);
    }
    public function update_groupid($value){

        return $value['groupid'];
    }
//    public function update_srokmin($value)
//    {
////        return $value['srokmin'] ;
//        return 0;
//    }
//
//    public function update_srokmax($value)
//    {
////        return $value['srokmax'] ;
//        return 0;
//    }
}