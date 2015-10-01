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
        foreach($data as $key => &$item) {
            if (!$item['quantity']){
                unset($data[$key]);
                continue;
            }
            if(!isset($ParseData['provider']->days)){
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
//                var_dump($value,$ParseData['provider']->days);die;
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
                    }
                }
                if($field == 'srok')
                    $value = $item['srokmin'] . (($item['srokmin'] < $item['srokmax']) ? '-' . $item['srokmax'] : '');

                if($field == 'quantity'){
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