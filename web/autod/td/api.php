<?php defined('LOGIN') or die('No direct script access.');


/**
 * Класс с API и внутренними функциями для неоригинальных каталогов
 * TD - Technical Detail
 */
class TD extends A2D {

    /// Тип каталога, для АвтоДилер пока не указывается
    protected static $_type = "TD";
    /// Производим базовые настройки
    public function __construct(){
        parent::__construct();
        static::$catalogRoot = "/td";
        ///static::setMark($this->rcv('mark'));
        /// Для "хлебных крошек", на какой каталог ссылаться при построении крошек
        /// Данным значениям (static::$arrActions) в helpers/breads.php сопоставляются последовательно параметрам из A2D::$aBreads
        /// Look Names in getTDDetail, getTDCrossover, getTDApplicability
        static::$arrActions = ['type','mark','model','compl','tree','group','vendor','detail','image','cross|apply'];
        /// Корневой каталог, откуда стартовать скрипты поиска для текущего каталога (используется в конструкторе формы поиска)
        static::$searchIFace = "td";
        /// Массив для построение формы с поиском (опиание в главном README.MD)
        static::$searchTabs = [[
            'id'    => 1,
            'alias' => 'detail',
            'name'  => 'номер детали', /// В контексте "Укажите ..."
            'tName' => 'Поиск по номеру детали',
        ]];
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///   ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ   ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    static function dateConvert($prms){
        // дата пустая, т.е. производство до текущего времени
        if( empty($prms['date']) ){
            if( isset($prms['date_current']) )
                return $prms['date_current'];
            else
                return "н.в.";
        }
        return substr($prms['date'],4).".".substr($prms['date'],0,4);
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///   СТАНДАРТНЫЕ ФУНКЦИИ ДЛЯ ПОЛУЧЕНИЯ ДАННЫХ   ///////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /// Получение доступных типов, если нужна не зависимая точка входа (в примерах не рассматривается)
    public function getTDTypes(){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth;
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Получение марок по выбранному типу
    public function getTDMarks($type){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&type=$type";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }

    public function getTDModels($type,$mark){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&type=$type&mark=$mark";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }

    public function getTDCompl($type,$mark,$model){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&type=$type&mark=$mark&model=$model";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }

    public function getTDTree($type,$mark,$model,$compl){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&type=$type&mark=$mark&model=$model&compl=$compl";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }

    /*/ /// На замену появилась getTDDetails
    public function getTDVendors($type,$mark,$model,$compl,$tree){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&type=$type&mark=$mark&model=$model&compl=$compl&tree=$tree";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    public function getTDGroups($type,$mark,$model,$compl,$tree,$group,$vendor){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&type=$type&mark=$mark&model=$model&compl=$compl&tree=$tree&group=$group&vendor=$vendor";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    //*/
    public function getTDDetails($type,$mark,$model,$compl,$tree){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&type=$type&mark=$mark&model=$model&compl=$compl&tree=$tree";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }

    public function getTDDetail($type,$mark,$model,$compl,$tree,$group,$vendor,$detail,$image){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&type=$type&mark=$mark&model=$model&compl=$compl&tree=$tree&group=$group&vendor=$vendor&detail=$detail&image=$image";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    public function getTDCrossover($type,$mark,$model,$compl,$tree,$group,$vendor,$detail,$image){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&type=$type&mark=$mark&model=$model&compl=$compl&tree=$tree&group=$group&vendor=$vendor&detail=$detail&image=$image";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    public function getTDApplicability($type,$mark,$model,$compl,$tree,$group,$vendor,$detail,$image){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&type=$type&mark=$mark&model=$model&compl=$compl&tree=$tree&group=$group&vendor=$vendor&detail=$detail&image=$image";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }

    public function searchTDNumber($number,$filter=FALSE){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&number=$number&filter=$filter";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }

    public function getTDModelInfoFull($compl){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&compl=$compl";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}
