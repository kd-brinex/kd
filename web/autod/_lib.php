<?php

/** Обязательно к применению */
require_once '_cfg.php'; /// Подключаем наш конфигурационный файл
require_once '_lng.php'; /// Подключаем файл мультиязычности. Нужен для цепочки наследования


class A2D extends Language {
    /// Constant
    protected $_login      = LOGIN;           /// Логин
    protected $_passwd     = PASSWD;          /// Пароль
    protected $_host       = API_HOST;        /// Сайт с API
    protected $_api        = API_VERSION;     /// Каталог с API
    protected $_script     = "in.php";        /// Файл на сервере, принимающий парамтеры
    protected $_img        = "img";           /// Названия каталога с иллюстрациями на сервере
    protected static $_lng = "ru";            /// Язык по умолчанию
    protected static $_fln;                   /// Объявление переменной для файла, вызывающего метод русификации
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///   Переменные по умолчанию для "хлебных крошек" переопределяются в процессе      /////////////////////////////////
    static public $aBreads     = [];          /// Массив с "хлебными крошками"
    static public $spBreads    = "&raquo;";   /// Разделитель "хлебных крошек"
    static public $breadsLogo  = FALSE;       /// Лого для "хлебных крошек"
    static public $bLogo       = FALSE;       /// Признак отображать или нет
    static public $breadsClass = "text-left"; /// Custom CSS для "хлебных крошек"
    static public $catalogRoot = FALSE;       /// Корневой каталог
    static public $mark        = FALSE;       /// Определяется в каждом API интерфейсе. Нужно не только в "хлебных крошках"
    static public $markRoute   = FALSE;       /// Дополнительный параметр (пример BMW)
    static public $markName    = FALSE;       /// Имя марки
    static public $arrActions  = [];          /// Массив с доступными скриптами. В "хлебных крошках" на каждый скрипт свой набор переменных
    static public $humanURL    = FALSE;       /// ЧПУ. По умолчанию (в данных примерах) ЧПУ не используется
    static public $callback    = FALSE;       /// Ссылка на проценку
    static public $offline     = FALSE;       /// (в планах) для дуступа к своему сайту из ваших программ
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /// Функция установки объекта
    protected static $_instance;
    public static function instance(){
        if( !isset(static::$_instance) ) static::$_instance = new static();
        return static::$_instance;
    }
    /// Установка значений по умолчанию
    public function __construct() {
        session_start();
        date_default_timezone_set(TIMEZONE);
        $this->sUrl   = $this->_host.DS.$this->_api.DS.$this->_script;
        $this->_auth  = $this->getAuth();            /// Собираем пользовательские данные в одну переменную
        $this->uIP    = $_SERVER['REMOTE_ADDR'];     /// Передаем если нужно, чтобы работало ограничение на просмотры с одного клиента
        $this->uAgent = $_SERVER['HTTP_USER_AGENT']; /// Передаем если нужно, чтобы работало ограничение на просмотры с одного клиента
        static::$_fln = basename($_SERVER['PHP_SELF'],".php");
    }
    /// Пример, как можно организовать мультиязычность
    /// Ввели $file, так как иногда необходимо использовать код одного файла в другом (к пр начать каталог с определенной модели)
    public static function lang($param,$file=FALSE){
        $lng = static::$_lng; /// Используемый язык
        $fln = ($file)?$file:static::$_fln; /// В каком файле вызвали функцию
        return static::${$lng}[$fln][$param];
    }
    /// Отправляем запрос на сервер
    public function getAnswer($body,$url=FALSE){
        $url = ($url)?$url:$this->sUrl;
        $ch = curl_init($url);      
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,80);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);      
        $result=curl_exec($ch); 
        return $result; 
    } 
    /// Получаем переменную из POST. Если пусто, то пытаемся найти ее в GET, иначе отдаем значение по умолчанию
    public function rcv($name,$def=""){
        $dt=htmlentities(@$_POST[$name],ENT_QUOTES,"UTF-8");
        if(!$dt)$dt=htmlentities(@$_GET[$name],ENT_QUOTES,"UTF-8");
        return ($dt)?$dt:$def;
    }
    /// Получить свойство массива
    public static function get($array, $key, $default = NULL){
        return isset($array[$key]) ? $array[$key] : $default;
    }
    /// Получить свойство объекта
    public static function property($obj, $name, $default = NULL){
        return ( isset($obj->{$name}) && !empty($obj->{$name}) ) ? $obj->{$name} : $default;
    }
    /// Вырезать из массива свойсво и вернуть в переменную
    public static function cut(&$array, $key, $default = NULL){
        if( isset($array[$key]) ){ $r = $array[$key]; unset($array[$key]); }else{ $r = $default; }
        return $r;
    }
    /// Преобразовать массив в объект
    public static function toObj($arr){
        return json_decode( json_encode($arr,JSON_FORCE_OBJECT) );
    }
    /// Сперва превращает строку в массив по буквенно, потом через ";" превращает в строку (используется в ETKA в таблице с моделями)
    public static function split($str,$del){
        return implode($del,preg_split('//',$str,-1,PREG_SPLIT_NO_EMPTY));
    }
    /// Вывести содержимое переменнй на экран
    public function p($arg='EMPTY',$title=NULL){
        header('Content-Type: text/html; charset=utf-8');
        if(is_array($arg)) $br='=='; else $br='';
        if($title==NULL) echo $br;
        elseif($title==1) echo 'Contents: '.$br;
        else echo $title.': '.$br;
        print'<pre>';print_r($arg);print'</pre>';
    }
    /// Вывести содержимое переменнй на экран и прекратить выполнение сценария
    public function e($arg='EMPTY',$title=NULL){ $this->p($arg,$title);exit; }
    /// Простая реализация 404 ошибки
    public function error($txt, $errorCode = null){
        if(!is_null($errorCode)){
            $errorCode = (int) $errorCode;
            if ($errorCode == 404) header('HTTP/1.1 404 Not Found');
            else header('HTTP/1.1 '.$errorCode);
        }
        $o1="<HTML><HEAD><META http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"><TITLE>УПС! Ошибка!</TITLE><META name=\"robots\" content=\"noindex,follow\"></HEAD><BODY><H1>УПС! Ошибка!</H1><BLOCKQUOTE style='font-size: 14pt'><B style='color: Red'>";
        $o2="</B></BLOCKQUOTE><I>".date("H:i:s d-m-Y")."</I></BODY></HTML>";
        echo $o1; print'<pre>'; print_r($txt); print'</pre>'; echo $o2; exit;
    }
    /// Функция проверят был ли это AJAX запрос
    static public function ajaxRequest(){
        if( !isset( $_SERVER[ "HTTP_X_REQUESTED_WITH" ] ) || $_SERVER[ "HTTP_X_REQUESTED_WITH" ] != "XMLHttpRequest" ) return FALSE;
        return TRUE;
    }
    /// Преобразовать данные переменной или массива из UTF8 в CP1251
    static function GetTextCP1251($text){
		if(is_array($text)){
			foreach ($text as &$value){ $value = self::GetTextCP1251($value); }
			return $text;
		}
		if(mb_check_encoding($text, "UTF-8")){
			$text = mb_convert_encoding($text, "WINDOWS-1251", "UTF-8");
		}
		return $text;
	}
    /// Преобразовать данные переменной или массива из CP1251 в UTF8
	static function GetTextUTF8($text){
        if(is_array($text)){
			foreach ($text as &$value){ $value = self::GetTextUTF8($value); }
			return $text;
		}
		if(mb_check_encoding($text, "WINDOWS-1251")){
			$text = mb_convert_encoding($text, "UTF-8", "WINDOWS-1251");
		}
		return $text;
	}
    /// Создание переменноей с авторизационными данными
    public function getAuth(){
        $info = "&u=".$this->_login."&p=".$this->_passwd."&h=".$_SERVER['HTTP_HOST'];
        return $info;
    }
    /// Получаем полный адрес с интерфейсом API
    public function getHost(){ return $this->_host.DS.$this->_api; }
    /// Получаем полный адрес до изображения на сервере
    public function getImgPath(){ return $this->_host.DS.$this->_img; }

    /// Поучаем путь для марок. Если марка с оригинального каталога, переключаемся каталог, указанный в переменной у марки
    static function getMarkUrl($oMark){
        if( $oMark->external ){ /// Признак, что нужно переключиться в оригинальный каталог
            $arr = explode('/',$oMark->route);
            $mark = end( $arr );
            $action = "main";
            $iface = NULL;
            $var  = 'mark';   /// Передаваемая переменная
            if( in_array($mark,['bmw','mini','moto','rr']) ){
                $iface  = 'bmw';    /// Директория со скриптами
                $action = 'series'; /// Входная точка
            }
            if( in_array($mark,['toyota','lexus']) ){
                $iface  = 'toyota';  /// Директория со скриптами
                $action = 'markets'; /// Входная точка
            }
            if( in_array($mark,['audi','volkswagen','seat','skoda']) ){
                $iface  = 'etka';    /// Директория со скриптами
                $action = 'markets'; /// Входная точка
            }
            if( in_array($mark,['pc','cv']) ){
                $iface  = 'td'; /// Директория со скриптами
                $action = 'marks';       /// Входная точка
                $var    = 'type';        /// Передаваемая переменная
            }
            $url = "$iface/{$action}.php?{$var}={$mark}";
        }
        else /// Для каталогов от АвтоДилер продолжаем схему, но уже из директории adc
            $url = "adc/models.php?typeID={$oMark->type_id}&markID={$oMark->mark_id}&flag={$oMark->flags}";
        return $url;
    }
    /// "Хлебные крошки"
    static function getBreadsName($mark,$k,$b){
        $mark = strtolower($mark);
        switch( $mark ){
            case "bmw":
                $name = ( $k=="models" ) ?BMW::instance()->_getSeries($b->name) :$b->name;
                break;
            case "toyota":
            case "lexus":
                $name = ( $k=="getToyModels" ) ?ucfirst($mark) :$b->name;
                break;
            default:
                $name = static::property($b,'name');
        }
        return $name;
    }
    /// Если нужно пропустить некоторые шаги в "хлебных крошкам", то в данной функции можно это реализовать
    static function getBreads($obj,$name,$iface){
        $aBreads = static::property($obj,$name,[]);
        switch( strtolower($iface) ){
            case "etka":
                unset($aBreads->mark);///unset($aBreads->market);unset($aBreads->production);
                break;
            case "bmw":
                ///unset($aBreads->options);unset($aBreads->production);
                break;
            case "td":
                unset($aBreads->types);///unset($aBreads->production);
                break;
        }
        return $aBreads;
    }
    /// Для марки устанавливаем нужные нам параметры, описание выше
    static public function setMark($mark){
        if( !$mark ) return FALSE;
        switch( $mark ){
            case "bmw":
                static::$mark = "bmw";
                static::$markName = "BMW";
                static::$markRoute = "";
                static::$breadsLogo  = "/media/images/bmw/logo/bmw.png";
                break;
            case "mini":
                static::$mark = "mini";
                static::$markName = "Mini";
                static::$markRoute = "/mini";
                static::$breadsLogo  = "/media/images/bmw/logo/mini_small.png";
                break;
            case "moto":
                static::$mark = "moto";
                static::$markName = "Moto";
                static::$markRoute = "/moto";
                static::$breadsLogo  = "/media/images/bmw/logo/bmw.png";
                break;
            case "rr":
                static::$mark = "rr";
                static::$markName = "Rolls-Royce";
                static::$markRoute = "/rr";
                static::$breadsLogo  = "/media/images/bmw/logo/rolls_royce_small.png";
                break;
            default:
                static::$mark = $mark;
                static::$markName = ucfirst($mark);
        }
        /// Пока, к примеру для BMW, не поймано для какой модели будут возвращаться детали
        /// Поэтому пока тупо ход конем
        $_SESSION['mark'] = $mark;
    }
    /// Не используется, скопирована для идеи
    static function callBackLink($detail,$callback=NULL,$name="",$onlyLink=FALSE){
        $name = ( !$name ) ?$detail :$name;
        if( $callback ){
            $_callBack = str_replace('{{DetailNumber}}',$detail,$callback);
            $r = "<a target=\"_blank\" href=\"$_callBack\" onclick=\"window.open(this.href, '_blank'); return false;\"><span class=\"c2c detailNumber\">$name</span></a>";
        }
        elseif( !$onlyLink ){
            $r = "<span class='c2c detailNumber'>$detail</span>";
        }
        else $r = FALSE;
        return $r;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///   Search Form Section   ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    static $searchMethod = "GET"; /// Каким способом передавать переменные (на POST не унифицировано)
    static $searchIFace  = "";    /// Определяется в API файла каталога
    static $searchTabs   = [];    /// Определяется в API файла каталога
    static $searchWhere  = [];    /// Определяется, где подключаем форму выбора (не адаптировано для внешних каталогов)
    /// Не используется! Скопировано с рабочего сайта для идеи на будущее. Пока все НУЖНОЕ отрабатывает в helpers/search.php
    public function searchForm( $method="POST", $root="", $arr=[], $from=TRUE ){
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///   For Tabs   ///////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /**
         * по умолчанию скрыта вторая вкладка
         * fromSV[tab1] OR fromSF[tab2]
         *
         * fromSV - From Search VIN
         * fromSN - From Search Number
         *
         * $root: bmw, toyota, etka
         * $arr:
         *      Колличество массивов второго уровня означает сколько будет вкладок
         *      Колличество элементов в таком массиве означает сколько будет полей для поиска
         *      Каждый элемент - имя поля
         *      [
         *          [vin]
         *          [number]
         *      ]
         */
        $a = $tName = FALSE; /// Нет ни одной активной вкладки
        foreach( $arr AS $t ){
            $tab = "from".ucfirst($t['alias']);
            if( static::get($_GET,$tab) ){
                $a = TRUE; /// Выстрелило
                ${"tab".$t['id']}    ='tab_active';
                ${"tabDiv".$t['id']} ='tabkont_active';
                $tName = $t['tName'];
            }
            else{
                ${"tab".$t['id']}    ='tab';
                ${"tabDiv".$t['id']} ='tabkont';
            }
        }
        if( !$a ){ /// Если нет активных вкладок, то включаем первую
            $tab1    ='tab_active';
            $tabDiv1 ='tabkont_active';
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///   Хлебные крошки && CheckBoxes   ///////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $referrer    = $this->request->referrer(); ///$this->e($referrer);
        $whereSearch = static::get($_SESSION,'searchWhere'); ///$this->e(Arr::get($whereSearch,'name'));
        $_active     = ( $this->rcv(static::get($whereSearch,'name')) )?TRUE:FALSE;
        /** Хлебные крошки */
        $_ref  = static::get($_SESSION,'searchReferrer'); ///$this->e($_ref);
        $_name = static::get($_ref,'name',"Последняя страница");
        $_url  = static::get($_ref,'value',$referrer);
        $aBreads = [];
        $aBreads[] = [
            "name" => 'Каталог',
            "breads" => [
                0 => 'catalog'
            ]
        ];
        if( $_active && $_ref ){
            $aBreads[] = [
                "name" => $_name,
                "breads" => explode('/',ltrim($_url,'/'))
            ];
        }
        $aBreads[] = [
            "name" => $tName,
            "breads" => []
        ]; ///$this->e($aBreads);
        static::$aBreads = static::toObj($aBreads);
        /**
         * CheckBox for Where Search
         * Где искать? В пределах марки/модели
         *
         * После крошек, так как зависим от $_ref
         * $_ref не существует, если ищем везде, что бы вернуться в корень каталога
         * А раз ищем везде, то и чекбоксы не нужны
         */
        /// Так боремся с сохранностью всех опций после переходя в контроллер поиска
        if( $_active && $whereSearch ){ ///$this->e($whereSearch);
            $this->whereSearch  = [
                'tabs'  => static::get($whereSearch,'tabs'),
                'name'  => static::get($whereSearch,'name'),
                'value' => static::get($whereSearch,'value'),
                'desc'  => static::get($whereSearch,'desc'),
            ];
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }


}



/**
 * Класс для работы конвертации object<=>xml в обе стороны
 *
 * К примеру получаем список моделей (adc/models.php):
 *      $oModelList = $this->getModelList($typeID,$markID,$flags);
 *
 * В результате получаем объект. Теперь из объекта получим XML:
 *      $obj = new ObjectAndXML();
 *      $xmlModelList = $obj->objToXML($oModelList);
 *
 * Получить из XML объект можно так:
 *      $oModelList = $obj->xmlToObj($xmlModelList);
*/
class ObjectAndXML {
    private $xml;

    // Constructor
    public function __construct() {
        $this->xml = new XmlWriter();
        $this->xml->openMemory();
        $this->xml->startDocument('1.0');
        $this->xml->setIndent(true);
    }

    // Method to convert Object into XML string
    public function objToXML($obj) {
        $obj = ['root'=>$obj];
        $this->getObject2XML($this->xml, $obj);

        $this->xml->endElement();

        return $this->xml->outputMemory(true);
    }

    // Method to convert XML string into Object
    public function xmlToObj($xmlString) {
        return simplexml_load_string($xmlString);
    }

    private function getObject2XML(XMLWriter $xml, $data) {
        foreach($data as $key => $value) {
            if(is_object($value)) {
                if( (int)$key{0}>0 ) $key = "ID_".$key;
                $xml->startElement($key);
                $this->getObject2XML($xml, $value);
                $xml->endElement();
                continue;
            }
            else if(is_array($value)) {
                $this->getArray2XML($xml, $key, $value);
            }

            if (is_string($value)) {
                $xml->writeElement($key, $value);
            }
        }
    }

    private function getArray2XML(XMLWriter $xml, $keyParent, $data) {
        foreach($data as $key => $value) {
            if (is_string($value)) {
                $xml->writeElement($keyParent, $value);
                continue;
            }

            if (is_numeric($key)) {
                $xml->startElement($keyParent);
            }

            if(is_object($value)) {
                $this->getObject2XML($xml, $value);
            }
            else if(is_array($value)) {
                $this->getArray2XML($xml, $key, $value);
                continue;
            }

            if (is_numeric($key)) {
                $xml->endElement();
            }
        }
    }
}