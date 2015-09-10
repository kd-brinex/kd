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

    public function parse($data, $provider = null, $toTemplate = true){
        $dataType = BrxDataInspector::getDataFormat($data);
        $result = $this->{'parse'.$dataType}($data);
        if($toTemplate)
            $result = $this->dataToTemplate($result, $provider);

        return $result;
    }

    private function dataToTemplate(&$data, $provider = null){
        $config = \Yii::$app->getModule('autoparts')->params;
        $fromTemplate = $config['providersFieldsParams'][$provider->provider_name]['method'][$provider->method]['params']['out'];
        $data = is_object($data) ? (array)$data : $data;
        $items = [];
        foreach($config['paramsTemplate'] as $key => $value){
            if(isset($fromTemplate[$key])){
                $values = BrxArrayHelper::array_search_values_recursive($fromTemplate[$key], $data);
                for($i = 0; $i <= count($values)-1; $i++){
                    $items[$i][$value] = $values[$i];
                }
            }
        }
        return $items;
    }

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