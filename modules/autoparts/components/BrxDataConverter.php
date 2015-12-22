<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 26.08.15
 * @time: 11:46
 */

namespace app\modules\autoparts\components;

use app\helpers\BrxArrayHelper;
use Yii;
use yii\base\Component;

use app\helpers\BrxDataInspector;
use yii\db\ActiveRecord;


class BrxDataConverter extends Component
{
    public function run($provider, $method, $params){
        $config = \Yii::$app->getModule('autoparts')->params;
        $fromTemplate = $config['providersFieldsParams'][$provider]['method'][$method]['params']['in'];
        $result = [];

        foreach($params as $key => $value){
            $index = array_search($key, $config['paramsTemplate'][$method]);
            $result = array_replace_recursive($result, $this->setValueIndex($index, $fromTemplate, $value));
        }
        return $result;
    }


    private function setValueIndex($key, $array, $val){
        foreach($array as $k => $v) {
            if(!is_array($v) && $k == $key){
                $array[$v] = $val ;
                unset($array[$k]);
            } else if(!is_array($v) && $k != $key)
                unset($array[$k]);

            if(is_array($v))
                $array[$k] = $this->setValueIndex($key, $v, $val);
        }
        return $array;
    }

    public function parse($data, $provider = null, $toTemplate = true, $beforeParse = [], $afterParse = []){
        $dataType = BrxDataInspector::getDataFormat($data);
        if(!$dataType)
            return false;


        $result = (is_array($data) && isset($data[0]) && $data[0] instanceof ActiveRecord) ? $data : $this->dataToArrayRecursive($this->{'parse'.$dataType}($data));

        if($toTemplate)
            $result = $this->dataToTemplate($result, $provider, $beforeParse, $afterParse);

        return $result;
    }

    /**
     * Функция ищет массив с запчастями и возвращает только его, без лишних артефактов
     * @param array $array массив - ответ от поставщика
     * @return array результирующий массив
     */
    private function find_details_array($array = [], $one_root_param)
    {
        if(is_array($array) && !empty($array) && is_array(current($array)) && array_key_exists($one_root_param, current($array))) {
            if(count($array) == 1 && !is_int(key($array))){
                $array[0] = current($array);
            }
            return $array;
        } elseif(is_array($array) && !empty($array)) {
            foreach ($array as $innerArray) {
                if(is_array($innerArray) && array_key_exists($one_root_param, $array)){
                    if(count($array) == 1 && !is_int(key($array))){
                        $array[0] = current($array);
                    }
                    return $array;
                } else
                    return $this->find_details_array($innerArray, $one_root_param);
            }
        }
    }


    /**
     * Функция рекурсивный помощник для функции rooting_array_values_recursive()
     * @param $arr
     * @param $result
     * @param $rootKey
     * @return mixed
     */
    private function recursive($arr, &$result, $rootKey)
    {
        static $ak;
        foreach($arr as $k => $v){
            if(is_array($v)) {
                $ak .= $k.':';
                $this->recursive($v, $result, $rootKey);
            } else
                $result[$rootKey][$ak . $k] = $v;
        }
        $ak = '';
        return $arr;
    }

    /**
     * Функция делает многоменрный массив линейным. К ключам вложенных массивов присоединяются ключи родительского
     * массива во избежании перезаписи дублирующихся имен.
     * @param array $array массив для обработки
     * @return array обработанный массив
     */
    private function rooting_array_values_recursive($array = [])
    {
        if(empty($array)) return $array;

        $result = [];
        foreach($array as $key => $value){
            if(!empty($value) && is_array($value))
                $this->recursive($value, $result, $key);
        }

        return $result;
    }

    /**
     * Функция ищет в массиве деталий вложенные повторения в подмассивах и помещает их в корень как уникальные
     * @param array $array массив запчастей
     * @param array $template массив шаблон конфигурации
     */
    private function multiplyIfNeed(&$array, $template)
    {
        if(!empty($array)) {
            $nextIndex = count($array);
            $firstIndex = $nextIndex;
            foreach ($template as $item) {
                $nextIndex = $firstIndex;
                if ($item{0} === ':') {
                    foreach ($array as $partIndex => &$part) {
                        if (is_array($part)) {
                            foreach ($part as $attributeIndex => &$attribute) {
                                if ($needle = stripos($attributeIndex, $item)) {
                                    if ($attributeIndex{$needle - 1} == 0) {
                                        unset($part[$attributeIndex]);
                                        $part[substr($item, 1)] = $attribute;
                                        $array[$nextIndex]['parentArrayIndex'] = $partIndex;
                                    } else {
                                        $array[$nextIndex][substr($item, 1)] = $attribute;
                                        $array[$nextIndex]['parentArrayIndex'] = $partIndex;
                                        unset($part[$attributeIndex]);
                                        $nextIndex++;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Функция заполняет все массивы недостающими атрибутами
     * @param array $array
     */
    private function fillUp(&$array)
    {
        if(!empty($array)) {
            foreach ($array as $key => &$value) {
                if (is_array($value) && array_key_exists('parentArrayIndex', $value)) {
                    foreach ($array[$value['parentArrayIndex']] as $k => $v) {
                        if (!isset($value[$k]))
                            $value[$k] = $v;
                    }
                    unset($value['parentArrayIndex']);
                }
            }
        }
    }

    /**
     * Функция удаляет созданные для обработки метки, ключи, значения
     * @param array $array
     */
    private function removeArtifacts(&$array)
    {
        if(!empty($array)) {
            foreach ($array as $key => &$value) {
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        if (preg_match('/(\d+):/', $k)) {
                            unset($value[$k]);
                        }
                    }
                }
            }
        }
    }
    private function dataToArrayRecursive($data){
        if(!is_array($data) && !is_object($data)) return $data;

        $data = (array)$data;
        foreach($data as $k => $v){
            if(!is_array($v) && !is_object($v))
                $data[$k] = $v;
            else
                $data[$k] = $this->dataToArrayRecursive($v);
        }

        return $data;
    }

    private function dataToTemplate(&$data, $provider = null, $beforeParseData = [], $afterParseData = []){
        if(!is_array($data)) return false;
        $config = \Yii::$app->getModule('autoparts')->params;

        $fromTemplate = $config['providersFieldsParams'][$provider->provider_name]['method'][$provider->method]['params']['out'];

        if(!empty(current($data)) && !(current($data) instanceof ActiveRecord)){
            $root_array = (array)$this->find_details_array($data,current($fromTemplate));
            $data = $this->rooting_array_values_recursive($root_array);
        }

        $items = [];
        // перебираем все атрибуты шаблона под который идет подгонка данных
        foreach($config['paramsTemplate'][$provider->method] as $key => $value){
            // ищем параметр шаблона в возвращенных дынных
            if(isset($fromTemplate[$key])){
                if(isset($data[0]) && $data[0] instanceof ActiveRecord){
                    foreach($data as $k => $model){
                        $values[$k] = $model->$fromTemplate[$key];
                    }
                } else {
                    $this->multiplyIfNeed($data, $fromTemplate);
                    $this->removeArtifacts($data);
                    $this->fillUp($data);
                }
                $data_count = count($data);

                for($i = 0; $i <= $data_count - 1; $i++){
                    $index = $fromTemplate[$key]{0} === ':' ? substr($fromTemplate[$key], 1) : $fromTemplate[$key];
                    if(isset($data[$i]) && isset($data[$i][$index])) {
                        $items[$i][$value] = $data[$i][$index];
                    } else if(!empty($data[key($data)]) && !empty($data[key($data)])){
                        $items[$i][$value] = current($data[$index]);
                    }
                }
            }
        }
        foreach($items as $item){
            foreach($config['paramsTemplate'][$provider->method] as $key => $value){
                if(!array_key_exists($value, $item))
                $item[$value] = '';
            }
        }
        for($i = 0; $i <= count($items)-1; $i++){
            foreach($config['paramsTemplate'][$provider->method] as $key => $value){
                if(!array_key_exists($value, $items[$i]))
                $items[$i][$value] = '';
            }
        }
        if(!empty($beforeParseData))
            $items = $this->beforeParse($beforeParseData, $items);


        if(!empty($afterParseData))
            $items = $this->afterParse($afterParseData, $items);

        return $items;
    }

    private function beforeParse($beforeParseData, &$data){
//        foreach($data as &$item){
//
//        }

        return $data;
    }

    private function afterParse($ParseData, &$data){
        //TODO убрать костыли и поставить нормальную обработку
        foreach($data as $key => &$item) {
//            if (isset($item['quantity']) && !$item['quantity']){
//                unset($data[$key]);
//                continue;
//            }
            if(!isset($ParseData['provider']->days)){
                unset($item);
                continue;
            }
            if(isset($ParseData['provider']->article)) {
                if (strtoupper($item['code']) == strtoupper($ParseData['provider']->article) &&
                    $item['groupid'] == '') {
                    $item['groupid'] = 0;
                }
            }
            if(isset($ParseData['provider']->provider_data) && $ParseData['provider']->provider_data->name == 'Over') {
                    $item['groupid'] = 0;
            }
            foreach($item as $field => &$value){
                if($field == 'groupid'){
                    if(!is_int($value)){
                        switch($value){
                            case 'Original':
                                $value = 0;
                                break;
                            case 'ReplacementOriginal':
                                $value = 1;
                                break;
                            case 'ReplacementNonOriginal':
                                $value = 2;
                                break;
                            case 'ReCross':
                                $value = 2;
                                break;
                            case 'Analog':
                                $value = 2;
                                break;
                            default:
                                $value = 2;
                                break;
                        }
                    }
                }

                if($field == 'name') {
                    $value = !empty($item['name']) ? $item['name'] : $item['code'];
                    $value = preg_replace('/(<|>)/',' ',$value);
                }
                if($field == 'provider')
                    $value = 'KD'.$ParseData['provider']->provider_data->id.'-'.$ParseData['provider']->store_id;
                if($field == 'sklad')
                    $value = $item['sklad'].'-'.$item['skladid'];
                if($field == 'ball')
                    $value = round($item['price']*0.005)*10;
                if($field == 'weight')
                    $value = $ParseData['provider']->provider_data->weight;
                if($field == 'price'){
                    $price = $value;
                    $nval = $price + ($price / 100 * $ParseData['provider']->marga);
                    $value = ceil($nval);
                }
                if($field == 'storeid')
                    $value = !empty($item['storeid']) ? $item['storeid'] : (!empty($ParseData['provider']->store_id) ? $ParseData['provider']->store_id : 109);
                if($field == 'pid')
                    $value = !empty($item['pid']) ? $item['pid'] : $ParseData['provider']->provider_data->id;

                if($field == 'flagpostav')
                    $value = $ParseData['provider']->provider_data->flagpostav;
                if($field == 'srokmin'){
                    $value += $ParseData['provider']->days;
                }
                if($field == 'srokmax')
                    $value += $ParseData['provider']->days;

                if($field == 'estimation'){
                    switch($ParseData['provider']->provider_data->name){
                        case 'Iksora':
                            $summa = 0;
                            $count = 0;
                            $nval = trim($value);
                            $n = strlen($nval);
                            for($с = 0; $с < $n; $с++){
                                $b = is_numeric($nval[$с]);
                                $summa += ($b) ? $nval[$с] : 0;
                                $count += ($b) ? 1 : 0;
                            }

                            $value = round(($count>0)?($summa/$count)*20:0,0);
                            break;
                        case 'Partkom':
                            $value = 50;
                            break;
                        case 'Emex':
                            $value = round($value);
                            break;
                        case 'Kd':
                            $value = round($value);
                            break;
                        case 'Over':
                            $value = ($value > 0) ? $value : 90;
                            break;
                        case 'Moskvorechie':
                            $value += 87;
                            break;
                    }
                }
                if($field == 'srok')
                    $value = $item['srokmin'] . (($item['srokmin'] < $item['srokmax']) ? '-' . $item['srokmax'] : '');

                if($field == 'quantity'){
                    if($value == 0)
                        $value = 'Под заказ';
                    else
                        $value = (int)preg_replace('~[^0-9]+~','',$item['quantity']);
                }

            }
//           foreach($afterParseData as $field => $manipulation){
//               if(isset($item[$field]))
//                   $item[$field] = $this->manipulate($manipulation, $item);
//               else continue;
//           }
        }
        return $data;
    }
    /* ФУНКЦИИ ШАБЛОНА */
    private function manipulate($manipulationTemplate, $data){
//        preg_match_all('/"(.*?)"/si', $manipulationTemplate, $out);
//        var_dump($out);die;
//        $params = explode(' ', $manipulationTemplate);
//        foreach($params as $key => &$param){
//            if(isset($data[$param]))
//                $params[$key] = "'$data[$param]'";
//        }
//        $expression = 'return '.implode($params).';';
//        return eval($expression);
    }
    /* ФУНКЦИИ ШАБЛОНА(КОНЕЦ) */

    private function parseJson($data){
        return json_decode($data, true);
    }

    private function parseXml($data){
        return simplexml_load_string($data);
    }

    private function parseObject($data){
        return $data;
    }

    private function parseArray($data){
        return $data;
    }


}