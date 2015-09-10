<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 21.08.15
 * @time: 15:55
 */

namespace app\modules\autoparts\components;

use yii\base\Component;
use yii\base\Exception;

use linslin\yii2\curl\Curl;

class BrxRest extends Component
{
    public $uri = '';
    private $getString = '';
    private $index = 0;

    public function run($uri = null, $method, array $options = null){
        if(empty($options['uri']) && empty($this->uri) && empty($uri))
            throw new Exception('Путь uri к api провайдера не указан.');
        else
            $this->uri = !empty($uri) ? $uri : (!empty($options['uri']) ? $options['uri'] : $this->uri);

        try {
            return $this->runRest($method, $options);
        } catch(Exception $e){
            throw new Exception('Ошибка подключения к REST API провайдера');
        }
    }

    private function runRest($method, $params){
        return $this->$method($params);
    }

    private function get($params){
        $request = $this->uri.'?'.$this->createRequest($params);
        $curl = new Curl();
//        var_dump($request);die;
        return $curl->get($request);
    }

    private function post($params){

        $curl = new Curl();
        $curl->setOption(CURLOPT_POSTFIELDS, //urldecode(http_build_query($params))
               /* 'force=1&order[is_test]=1&order[dispatch_type]=3&order[dispatch_at]=2015-09-12&order[dispatch_time]=1&order[person]=&order[phone]=&order[comment]=&order[shipment_address]=&order[items][1][resource_id]=40386&order[items][1][warehouse_id]=46&order[items][1][quantity]=1&order[items][1][comment]=API Test&key=62224996794244a125b9b3fd734f9dd75dc08d1a59b5a42268bbec9d3e8d7bc3'*/
                  'force=1&order[is_test]=1&order[dispatch_type]=3&order[dispatch_time]=1&order[dispatch_at]=2015-09-12&order[person]=%D0%96%D0%B5%D0%BD%D1%8F+%D0%9B%D1%83%D0%BA%D0%B0%D1%88%D0%B8%D0%BD&order[comment]=%D0%B7%D0%B0%D0%BF%D1%87%D0%B0%D1%81%D1%82%D0%B8+%D0%B4%D0%BB%D1%8F+%D0%98%D0%BF%D0%BF%D0%BE%D0%BB%D0%B8%D1%82%D0%B0&order[shipment_address]=%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0%2C+3-%D1%8F+%D1%83%D0%BB%D0%B8%D1%86%D0%B0+%D0%A1%D1%82%D1%80%D0%BE%D0%B8%D1%82%D0%B5%D0%BB%D0%B5%D0%B9%2C+%D0%B4%D0%BE%D0%BC+25%2C+%D0%BA%D0%B2%D0%B0%D1%80%D1%82%D0%B8%D1%80%D0%B0+12&order[items][1][resource_id]=703&order[items][1][warehouse_id]=46&order[items][1][quantity]=1&order[items][1][comment]=%D0%BF%D1%80%D0%BE%D0%B8%D0%B7%D0%B2%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9+%D0%BA%D0%BE%D0%BC%D0%BC%D0%B5%D0%BD%D1%82%D0%B0%D1%80%D0%B8%D0%B9+1&order[items][2][resource_id]=50716&order[items][2][warehouse_id]=46&order[items][2][quantity]=1&order[items][2][comment]=%D0%BF%D1%80%D0%BE%D0%B8%D0%B7%D0%B2%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9+%D0%BA%D0%BE%D0%BC%D0%BC%D0%B5%D0%BD%D1%82%D0%B0%D1%80%D0%B8%D0%B9+2&&order[items][3][resource_id]=50716&order[items][3][warehouse_id]=6&order[items][3][quantity]=1&order[items][3][comment]=%D0%BF%D1%80%D0%BE%D0%B8%D0%B7%D0%B2%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9+%D0%BA%D0%BE%D0%BC%D0%BC%D0%B5%D0%BD%D1%82%D0%B0%D1%80%D0%B8%D0%B9+3'//http_build_query($params)
        )->post($this->uri.'?key='.$params['key'], false);

//        var_dump($curl);
//        var_dump($curl);die;
        // .'?key='.$params['key']  <- КОСТЫЛЬ
//        return $curl;
    }

    private function createRequest($options){
        return urldecode(http_build_query($options));
    }


}