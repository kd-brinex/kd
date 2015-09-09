<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 21.08.15
 * @time: 14:40
 */

namespace app\modules\autoparts\components;

use Yii;
use yii\base\Exception;

class BrxProviderConnector
{
    /**
     * Метод возвращает тип подключения к провайдеру
     * @return string тип подключения
     * @throws Exception выбрасывается если тип подключения не определен в конфигурации
     */
    private function getApiType(){
        if(!isset($this->apiType))
            throw new Exception('Не указан тип подключения к провайдеру');

        return $this->apiType;
    }

    /**
     * Метод соединяет с провайдером
     * @param $uri string
     * @return object soap
     */
    protected function getConnection($method, $options){
        return Yii::$app->getModule('autoparts')
                    ->{$this->getApiType()}
                    ->run($this->getMethodUri($method), $this->getProviderMethod($method), $options);
    }

    /**
     * Метод определяет uri с которым будем работать
     * @param $method string имя метода
     * @return mixed uri
     */
    protected function getMethodUri($method){
        return $this->uri[$this->methods[$method]['uri_index']];
    }

    /**
     * Функция находит определение указанного метода у провайдера. Если таковой отутствует выбрасывает исключение
     * @param $method string название метода
     * @return mixed метод провайдера
     */
    private function getProviderMethod($method){
        return $this->methods[$method]['name'];
    }

    /**
     * Метод забирает данные авторизации для провайдера
     * @return array с логином и паролем
     */
    protected function getAccessOptions($method){
        if(isset($this->authParamsTemplate))
            $this->getAuthParamsByTemplate($method);

        if(isset($this->authParams)){
            foreach($this->authParams as $param){
                $params[$param] = $this->$param;
            }
        }
        return $params;
    }


    private function getAuthParamsByTemplate($method){
        foreach($this->authParamsTemplate as $key => $value){
            if(($index = array_search($key, $this->authParams)) !== false)
                $this->authParams[$index] = $value;

            if(($index = array_search($key, $this->methods[$method]['params']))){
                unset($this->methods[$method]['params'][$index]);
                $this->methods[$method]['params'][$value];
            }
        }
    }
}