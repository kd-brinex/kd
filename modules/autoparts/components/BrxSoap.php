<?php
namespace app\modules\autoparts\components;

use \yii\base\Component;
use yii\base\Exception;
/**
 * Компонент для работы с SOAP клиентом
 *
 * @author Eugene Brx <compuniti@mail.ru>
 *
 */
class BrxSoap extends Component{

    /**
     * Отслеживание запросов Soap
     * @var bool
     */
    public $trace = false;

    /**
     * Версия Soap по умолчанию
     * @var int
     */
    public $soap_version = SOAP_1_2;

    /**
     * @var string путь к файлу wsdl
     */
    public $uri = '';

    /**
     * Параметры кэширования wsdl-запроса
     * @var bool
     */
    public $wsdl_cache = false;

    /**
     * @var string
     */
    public $wsdl_dir = "/runtime/cache/wsdl_tmp";

    /**
     * @var int
     */
    public $wsdl_ttl = 86400;

    /**
     * @var int
     */
    public $wsdl_cache_type = WSDL_CACHE_DISK;

    /**
     * @var int
     */
    public $wsdl_cache_limit = 15;

    /**
     * Массив преднастроек SoapClient
     * @var array
     */
    private $options = [];

    /**
     * Метод предворительно настраивает и инициализирует компонент.
     */
    public function init(){
        ini_set("soap.wsdl_cache_enabled", $this->wsdl_cache);
        ini_set("soap.wsdl_cache_dir", $this->wsdl_dir);
        ini_set("soap.wsdl_cache_ttl", $this->wsdl_ttl);
        ini_set("soap.wsdl_cache", $this->wsdl_cache_type);
        ini_set("soap.wsdl_cache_limit", $this->wsdl_cache_limit);

        $this->options = [
            'soap_version' => $this->soap_version,
            'trace' => $this->trace,
        ];

        parent::init();
    }

    /**
     * Предварительная клиентская настройка SaopClient
     * @param array $options если адрес uri не указан в конфигурации компонента, его нужно обязательно указать в массиве с ключом 'uri' => '<wsdl_uri>'
     * @return \SoapClient объект
     * @throws Exception если uri нигде не указан
     */
    public function run($uri = null, $method, array $options = null){
        if(empty($options['uri']) && empty($this->uri) && empty($uri))
            throw new Exception('Путь uri к файлу wsdl не указан.');
        else
            $this->uri = !empty($uri) ? $uri : (!empty($options['uri']) ? $options['uri'] : $this->uri);

        try {
            return $this->runSoap()->__soapCall($method, $options);
        } catch(\SoapFault $e){
//            var_dump($e->getMessage());
            //throw new Exception('Ошибка подключения к SOAP API провайдера ('.$e->getMessage().')');
        }
    }

    /**
     * Метод запускает веб-службу SoapClient и выбрасывает исключение при неудачной поптыке подключиться
     * @return \SoapClient объект
     */
    private function runSoap(){
        try {
            $soap = new \SoapClient($this->uri, $this->options);
        } catch(\SoapFault $e) {
            throw new Exception('
                             При подключении возникли ошибки.
                             Faultcode: '.$e->faultcode.'
                             Faultstring: '.$e->faultstring.'
                      ');
        }
        return $soap;
    }

    /**
     * Вспомогательный метод для просмотра списка методов объекта SoapClient
     * ВНИМАНИЕ! Метод для удобства тестирования функциональности SOAP. При инициализации создает еще один объект SoapClient. Во избежании можно вызвать __getFunctions() напрямую из компонента.
     * @return array
     */
    //TODO доделать. Что то не хочет роботать с новыми дополнениями
    public function getMethods(){
            return $this->run()->__getFunctions();
    }

}

?>