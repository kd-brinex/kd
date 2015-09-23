<?php defined('LOGIN') or die('No direct script access.');


/**
 * Класс с API и внутренними функциями для оригинального каталога Toyota
 * В каталог входят следующие марки: Toyota, Lexus
 */
class TOY extends A2D {

    /// Тип каталога передается на сервер
    protected static $_type = "TOY";
    /// Производим базовые настройки
    public function __construct(){
        parent::__construct();
        static::$catalogRoot = "/toyota";
        static::setMark($this->rcv('mark'));
        /// Данным значениям (static::$arrActions) в helpers/breads.php сопоставляются последовательно параметрам из A2D::$aBreads
        static::$arrActions = ['market','model','compl','opt','code','graphic'];
        /// Корневой каталог откуда стартовать скрипты поиска для текущего каталога (используется в конструкторе формы поиска)
        static::$searchIFace = "toyota";
        /// Массив для построение формы с поиском (опиание в главном README.MD)
        static::$searchTabs = [[
            'id'    => 1,
            'alias' => 'vin',
            'name'  => 'VIN', /// В контексте "Укажите ..."
            'tName' => 'Поиск по VIN',
        ],[
            'id'     => 2,
            'action' => 'frame',
            'multi'  => [[
                'alias' => 'frame',
                'name'  => 'фрейм', /// В контексте "Укажите ..."
            ],[
                'alias' => 'number',
                'name'  => 'номер', /// В контексте "Укажите ..."
            ]],

            'tName' => 'Поиск по номеру детали',
        ]];
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///   СТАНДАРТНЫЕ ФУНКЦИИ ДЛЯ ПОЛУЧЕНИЯ ДАННЫХ   ///////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /// Получение рынков
    public function getToyMarkets(){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth;
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Модели выпускаемые для данного рынка
    public function getToyModels($mark,$market){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&mark=$mark&market=$market";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Модификации/комплектации для выбранной модели
    public function getToyModiff($market,$catalog){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&market=$market&catalog=$catalog";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Группа деталей для текущей комплектации
    public function getToyModCompl($market,$catalog,$model,$sysopt,$compl,$vin,$vdate,$siyopt){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&market=$market&catalog=$catalog&model=$model&sysopt=$sysopt&compl=$compl&vin=$vin&vdate=$vdate&siyopt=$siyopt";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Иллюстрация и список номенклатуры для выбранной детали
    public function getToyPic($market,$catalog,$model,$sysopt,$compl,$group,$pic,$vin,$vdate,$siyopt,$grFalse){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth.
            "&market=$market&catalog=$catalog&model=$model&sysopt=$sysopt&compl=$compl".
            "&group=$group&pic=$pic&vin=$vin&vdate=$vdate&siyopt=$siyopt&grFalse=$grFalse".
            "&uIP=".$this->uIP."&uAgent=".$this->uAgent;
        //print'<pre>';print_r($body);print'</pre>';exit;
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Информация по детали - под каким номеров в каком годе выпускалась
    public function getToyPnc($market,$catalog,$model,$sysopt,$compl,$group,$pic,$pnc,$vin,$vdate,$siyopt){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&market=$market&catalog=$catalog&model=$model&sysopt=$sysopt&compl=$compl&group=$group&pic=$pic&pnc=$pnc&vin=$vin&vdate=$vdate&siyopt=$siyopt";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Поиск модели по VIN
    public function searchToyotaVIN($vin){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&vin=$vin";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Поиск модели по фрейму и коду
    public function searchToyotaFrame($frame,$number){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&frame=$frame&number=$number";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}
