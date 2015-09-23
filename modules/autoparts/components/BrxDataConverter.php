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
            $index = array_search($key, $config['paramsTemplate']);
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

        $result = $this->{'parse'.$dataType}($data);

        if($toTemplate)
            $result = $this->dataToTemplate($result, $provider, $beforeParse, $afterParse);

        return $result;
    }

    private function dataToTemplate(&$data, $provider = null, $beforeParseData = [], $afterParseData = []){
        $config = \Yii::$app->getModule('autoparts')->params;
        $fromTemplate = $config['providersFieldsParams'][$provider->provider_name]['method'][$provider->method]['params']['out'];
        $data = is_object($data) ? (array)$data : $data;
        $items = [];
        // перебираем все атрибуты шаблона под который идет подгонка данных
//        var_dump($data);
        foreach($config['paramsTemplate'] as $key => $value){
            // ищем параметр шаблона в возвращенных дынных
            if(isset($fromTemplate[$key])){
                if(isset($data[0]) && $data[0] instanceof ActiveRecord){
                   foreach($data as $k => $model){
                       $values[$k] = $model->$fromTemplate[$key];
                   }
                } else
                    $values = BrxArrayHelper::array_search_values_recursive($fromTemplate[$key], $data);
                // забираем его значение
                for($i = 0; $i <= count($values)-1; $i++){
//                создаем массивы и забиваем их параметрами шаблона и соответсвующими данными из пришедших
                    $items[$i][$value] = $values[$i];
                }
            }
        }
//        var_dump($items);die;
        foreach($items as $item){
            foreach($config['paramsTemplate'] as $key => $value){
                if(!array_key_exists($value, $item))
                    $item[$value] = '';
            }
        }

        for($i = 0; $i <= count($items)-1; $i++){
            foreach($config['paramsTemplate'] as $key => $value){
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
        foreach($data as &$item) {
            if (!$item['quantity']){
                unset($item);
                continue;
            }
            foreach($item as $field => &$value){
                if($field == 'groupid'){
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
                            $value = 0;
                            break;
                    }
                }
                if($field == 'name')
                    $value = !empty($item['name']) ? $item['name'] : $item['code'];
                if($field == 'provider')
                    $value = $ParseData['provider']->provider_name;
                if($field == 'sklad')
                    $value = $item['sklad'].'-'.$item['skladid'];
                if($field == 'ball')
                    $value = floor($item['price']*0.05);
                if($field == 'weight')
                    $value = $ParseData['provider']->provider_data->weight;
                if($field == 'price'){
                    $price = $value;
                    $nval = $price + ($price / 100 * (isset($ParseData['provider']->marga) ? $ParseData['provider']->marga : 0));
                    $rval = round($nval);
                    $value = ((($rval > $nval) ? $rval : $rval + 1) == 1 || (($rval > $nval) ? $rval : $rval + 1) <= 0) ? '-' : $value;
                }
                if($field == 'storeid')
                    $value = !empty($item['storeid']) ? $item['storeid'] : (!empty($ParseData['provider']->store_id) ? $ParseData['provider']->store_id : 109);
                if($field == 'pid')
                    $value = !empty($item['pid']) ? $item['pid'] : $ParseData['provider']->provider_data->id;

                if($field == 'flagpostav')
                    $value = $ParseData['provider']->provider_data->flagpostav;
                if($field == 'srokmin')
                    $value += isset($ParseData['provider']->days) ? $ParseData['provider']->days : 0;
                if($field == 'srokmax')
                    $value += isset($ParseData['provider']->days) ? $ParseData['provider']->days : 0;
                if($field == 'srok')
                    $value = $item['srokmin'] . (($item['srokmin'] < $item['srokmax']) ? '-' . $item['srokmax'] : '');

                if($field == 'quantity'){
                    $value = (int)preg_replace('~[^0-9]+~','',$item['quantity']);
//                    $q = '';
//                    $d = 0;
//                    $avalue = str_split($value);
//                    foreach ($avalue as $n) {
//                        $q .= (is_numeric($n)) ? $n : '';
//                        if ($n == '>') {
//                            $d = 1;
//                        }
//                        if ($n == '<') {
//                            $d = -1;
//                        }
//                    }
//                    $q += $d;
//                    $value = $q;
                }

            }
//           foreach($afterParseData as $field => $manipulation){
//               if(isset($item[$field]))
//                   $item[$field] = $this->manipulate($manipulation, $item);
//               else continue;
//           }
        }
//        var_dump($data);

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