<?php
/**
 * @author: Eugene Brx
 * @email : compuniti@mail.ru
 * @date: 12.08.15
 * @time: 8:08
 */

namespace app\modules\autoparts\components;

use Yii;
use yii\base\Exception;

use app\helpers\BrxArrayHelper;

class BrxProvider extends BrxProviderConnector
{
    /**
     * Создаем объект поставщика Provider и загружаем все параметры указаные в config
     * @param $provider string имя провайдера
     * @param array|null $options массив настроек провайдера (для изменений на лету). По умолчанию все настройки хранятся в конфигурации модуля массиве providers.
     * ВНИМАНИЕ! Массив options имеет приоритет перед массивов указаном в конфигурации, и его параметры переопределяют параметры конфигурации
     * @throws Exception выбрсывается если провайдер не указан
     */
    public function __construct($provider, array $options = []){

        if(empty($provider))
            throw new Exception('Провайдер не указан');

        $params = Yii::$app->getModule('autoparts')->params['providers'][$provider];

        if(!empty($options))
            $params = BrxArrayHelper::array_replace_recursive_ncs($params, $options);
        Yii::configure($this, $params);
    }

    /**
     * PHP волшебный метод: вызывает и передает имя метода и аргументы указнные в его праметрах в генератор runMethod, где создается soap метод и вызывается
     * @param string имя метода
     * @param array $options параметры передаваемые в метод провайдера
     * @return mixed результат выполнения метода провайдера
     * @throws Exception выбрасывается если метод не определен в конфигурации
     */
    public function __call($method, $options){
        if(empty($this->methods[$method]))
            throw new Exception('Метод '.$method.' провайдера '.$this->provider_name.' не определен, либо не описан в конфигурации');

        return $this->runMethod($method, current($options));
    }

    /**
     * Метод генерирует soap метод и запускает его
     * @param $method string имя метода
     * @param array|null $options параметры передаваемые в метод провайдера
     * @return mixed результат выполнения метода провайдера
     * @throws Exception выбрасываеюся если возникает ошибка подключения к еровайдеру (например: неверные логин, либо пароль)
     */
    private function runMethod($method, $options){
        $options = $this->getOptions($method, $options);
        $response = $this->getConnection($method, $options);
//        var_dump($response);
        //ЩАС БУДЕТ КОСТЫЛЬ
        $this->method = $method;
        //ВОТ И КОНЧИЛСЯ КОСТЫЛЬ
        $beforeParse = [

        ];
        $afterParse = [
            'provider' => $this
        ];
        $data = Yii::$app->getModule('autoparts')->converter->parse($response, $this, true, $beforeParse, $afterParse);

        return $data;
    }

    /**
     * Собираем все параметры для выполнения метода по следующему алгоритму: [параметры_указанные_в_конфигурации] <- [параметры_указанные_в_конфигурации_для_всех_методов] <- [парметры_указанные_при_вызове_метода]. Приоритет увеличивается слева на право.
     * @param $method string метод для которого собираются параметры
     * @return array все параметры
     * TODO условие isParamsAsArray - костыль который требует уничтожения на самом глубоком уровне!!!)))
     */
    private function getOptions($method, array $options){
        $options = Yii::$app->getModule('autoparts')->converter->run($this->provider_name, $method, $options);
        $options = BrxArrayHelper::array_replace_recursive_ncs(
            $this->methods[$method]['params'],
            $this->methodsOptions,
            $this->getAccessOptions($method),
            $options
        );
        //видоизменяем параметры по шаблону
        if(isset($this->authParamsTemplate)) {
            foreach ($this->authParamsTemplate as $param => $value) {
                if (isset($options[$param]) && isset($options[$value])) {
                    $options[$param] = $options[$value];
                    unset($options[$value]);
                }
            }
        }

        if($this->isParamsAsArray)
            $options = [$options];

        return $options;
    }

    /**
     * Функция возвращает массив используемых загруженным объектом методов
     * @return array
     */
    public function getMethods(){
        return $this->methods;
    }
}