<?php defined('LOGIN') or die('No direct script access.');


/**
 * Класс с API и внутренними функциями для оригинального каталога ETKA
 * В каталог входят следующие марки: Audi, Volkswagen, Seat, Skoda
 */
class ETKA extends A2D {

    /// Тип каталога, для АвтоДилер пока не указывается
    protected static $_type = "ETKA";
    /// Производим базовые настройки
    public function __construct(){
        parent::__construct();
        static::$catalogRoot = "/etka";
        static::setMark($this->rcv('mark'));
        /// Для "хлебных крошек", на какой каталог ссылаться при построении крошек
        /// Данным значениям (static::$arrActions) в helpers/breads.php сопоставляются последовательно параметрам из A2D::$aBreads
        static::$arrActions = ['mark','market','model','year','code','dir','type','group','subgroup','graphic'];
        /// Корневой каталог, откуда стартовать скрипты поиска для текущего каталога (используется в конструкторе формы поиска)
        static::$searchIFace = "etka";
        /// Массив для построение формы с поиском (опиание в главном README.MD)
        static::$searchTabs = [[
            'id'    => 1,
            'alias' => 'vin',
            'name'  => 'VIN', /// В контексте "Укажите ..."
            'tName' => 'Поиск по VIN',
        ],[
            'id'    => 2,
            'alias' => 'detail',
            'name'  => 'номер детали', /// В контексте "Укажите ..."
            'tName' => 'Поиск по номеру детали',
        ]];
    }
    /// Функция обработки результатов по поиску детали. Можно придумать что-то свое, тогда не забываем изменить код обработки
    public function searchETKATree($aResult,$number,$market){ /// Происходит перестроение массива с данными под наши нужды
        $aTree = [];
        $i = 0; foreach( $aResult AS $r ){ ++$i;

            /// Необходимые нам переменные из общей кучи
            $mark    = $r->mark;
            $marketCode = $r->marketCode;
            $modelCode  = $r->modelCode;
            $prod    = $r->prod;
            $code    = $r->code;
            $group   = $r->group;
            $sgroup  = $r->sgroup;
            $graphic = $r->graphic;

            /// Ссылка для перехода на страницу с иллюстрацией при клике по нужной модели
            $nextUrl = "/etka/illustration.php?mark={$mark}&market={$marketCode}&model={$modelCode}&year={$prod}&code={$code}&group={$group}&subgroup={$sgroup}&graphic={$graphic}";

            /// Добавим хэштэг для подсветки детали на иллюстрации
            $nextUrl = $nextUrl."#".$number;

            $marketName = $r->marketName;
            $modelName  = $r->modelName;

            /// Как будет выглядит ссылка для пользователя aka анкор (текст ссылки)
            $_name = "{$r->groupName}";

            /// Собственно, построение массива
            $aTree[$mark]['name'] = ucfirst($mark);
            $aTree[$mark]['children'][$modelCode]['name'] = $modelName;
            $aTree[$mark]['children'][$modelCode]['children'][$prod]['name'] = "({$prod} - ".A2D::property($r,"endDate","...").")";
            $aTree[$mark]['children'][$modelCode]['children'][$prod]['children'][$i]['url'] = $nextUrl;
            $aTree[$mark]['children'][$modelCode]['children'][$prod]['children'][$i]['name'] = $_name;
            /// Если нет рынка в запросе, значит нет фильтрации и будут схожие модели по соседним рынкам
            if( !$market ){ /// Просто добавим рынок как дополнительное описание
                $aTree[$mark]['children'][$modelCode]['children'][$prod]['children'][$i]['desc'] = " - $marketName";
            }

        }
        return $aTree;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///   СТАНДАРТНЫЕ ФУНКЦИИ ДЛЯ ПОЛУЧЕНИЯ ДАННЫХ   ///////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /// Получение марок, если нужна не зависимая точка входа (в примерах не рассматривается)
    public function getETKAMarks(){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth;
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Получение рынков
    public function getETKAMarkets($mark){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&mark=$mark";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Модели выпускаемые для данного рынка
    public function getETKAModels($mark,$market){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&mark=$mark&market=$market";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Дата производства выбранной модели
    public function getETKAProduction($mark,$market,$model){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&mark=$mark&market=$market&model=$model";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Основные группы улов для выбранной модели
    public function getETKAGroups($mark,$market,$model,$prod,$cat,$dir){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&mark=$mark&market=$market&model=$model&prod=$prod&cat=$cat&dir=$dir";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Список деталей, входящих в узел/группу
    public function getETKASubGroups($mark,$market,$model,$prod,$cat,$dir,$group){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&mark=$mark&market=$market&model=$model&prod=$prod&cat=$cat&dir=$dir&group=$group";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Иллюстрация и список номенклатуры для выбранной детали
    public function getETKAIllustration($mark,$market,$model,$prod,$cat,$dir,$group,$sgroup,$detail,$zoom){
        $body = "t=".static::$_type."&f=".__FUNCTION__.
            $this->_auth."&mark=$mark&market=$market&model=$model&prod=$prod&cat=$cat&dir=$dir&group=$group&sgroup=$sgroup&detail=$detail&zoom=$zoom".
            "&uIP=".$this->uIP."&uAgent=".$this->uAgent.
            "";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Список оригинальных запчастей для сервисного обслуживания и расходных материалов
    public function getETKAServicesParts($mark,$market,$model,$prod,$cat,$dir,$fps){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&mark=$mark&market=$market&model=$model&prod=$prod&cat=$cat&dir=$dir&fps=$fps";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Список для выбранной категории из getETKAServicesParts
    public function getETKASPList($mark,$market,$model,$prod,$cat,$dir,$group,$sgroup){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&mark=$mark&market=$market&model=$model&prod=$prod&cat=$cat&dir=$dir&group=$group&sgroup=$sgroup";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Поиска детали
    public function searchETKANumber($number,$mark){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&number=$number&mark=$mark";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /**
     * Поиск по VIN происходит в два этапа:
     *      1. Получаем возможные модели с кратким списком характеристик
     *      2. По выбранной модели получаем подробное описание выбранной модели
    */
    /// Первый этап
    public function searchETKAVIN($vin){
        $body = "t=".static::$_type."&f=".__FUNCTION__.$this->_auth."&vin=$vin";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    /// Второй этап
    public function getETKAVinInfo($vin,$vkbz){
        $body = "t=ETKA&f=".__FUNCTION__.$this->_auth."&vin=$vin&vkbz=$vkbz";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}
