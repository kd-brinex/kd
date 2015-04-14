<?php
namespace app\modules\auto;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.04.15
 * Time: 9:27
 */
class A2DSearch extends A2D
{

    /// Тип каталога передается на сервер
    private $mark;

    public function __construct($params)
    {
        parent::__construct();
        $this->mark = $params['mark'];
        /// Для "хлебных крошек": на какой каталог ссылаться при построении крошек
//        static::$catalogRoot = $params['root'];///bmw;
        /// Данным значениям (static::$arrActions) в helpers/breads.php сопоставляются последовательно параметрам из A2D::$aBreads
        static::$arrActions = ['type', 'series', 'body', 'model', 'market', 'rule', 'transmission', 'production', 'group', 'graphic'];
        /// Корневой каталог, откуда стартовать скрипты поиска для текущего каталога (используется в конструкторе формы поиска)
//        static::$searchIFace = $params['iface'] "bmw";
        /// Массив для построение формы с поиском (опиание в главном README.MD)
        static::$searchTabs = [[
            'id' => 1,
            'alias' => 'vin',
            'name' => 'VIN', /// В контексте "Укажите ..."
            'tName' => 'Поиск по VIN',
        ], [
            'id' => 2,
            'alias' => 'detail',
            'name' => 'номер детали', /// В контексте "Укажите ..."
            'tName' => 'Поиск по номеру детали',
        ]];
    }

    /// Функция обработки результатов по поиску детали. Можно придумать что-то свое, тогда не забываем изменить код обработки
    public function searchTree($aResult, $number, $mark)
    {
        $aTree = [];
        $i = 0;
        foreach ($aResult AS $r) {
            ++$i;
            $clearCode = str_replace([" ", "."], "_", $r->ModelCode); ///For Grouped
            /// Ссылка на иллюстрацию
            $_url = "/bmw/illustration.php?mark={$mark}&type={$r->Type}&series={$r->SeriesCode}&body={$r->Body}&model={$r->ModelID}&market={$r->Market}" .
                "&rule={$r->RuleCode}&transmission={$r->GetriebeCode}&production={$r->Einsatz}&group={$r->LVL1Code}&graphic={$r->GraficID}";
            /// Добавляем HashTag номера для его подсветки
            $_url = $_url . "#" . substr($number, -7);
            /// Формируемое имя ссылки
            $_name = $r->GraficName . " - " . $r->Body . " - " . $r->Market . " - " . $r->RuleName . " - " . $r->GetriebeName;
            /// Создаем необходимый нам массив
            $aTree[$r->SeriesCode]['name'] = $r->Mark . " " . $r->SeriesName;
            $aTree[$r->SeriesCode]['children'][$clearCode]['name'] = $r->ModelCode;
            $aTree[$r->SeriesCode]['children'][$clearCode]['children'][$i]['name'] = $_name;
            $aTree[$r->SeriesCode]['children'][$clearCode]['children'][$i]['url'] = $_url;
        } ///$this->e($aTree);
        return $aTree;
    }

    /// В планах к следующему обновлению
    public function getSearch($bErr = TRUE)
    {
        /// Для общего поиска по детали сюда выносится код
    }

    /// Функция для получение названия марки. Вдруг захочится что-то сделать с именем
    public function getMarkName()
    {
        return $this->markName;
    }

    /// Функция для получение названия серии. Здесь потребовалось модифицировать
    public function getSeries($s)
    {
        if (strripos($s, "'")) return str_replace("' ", " серии ", $s);
        return preg_replace("/([XZM][0-9])/", "$1 серии", $s);
    }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///   СТАНДАРТНЫЕ ФУНКЦИИ ДЛЯ ПОЛУЧЕНИЯ ДАННЫХ   ///////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Получить список серий для марки
    public function getCatalogs($mark)
    {
        $body = "t='.$this->mark['type'].'&f=" . __FUNCTION__ . $this->_auth . "&mark=$mark";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }

/// Список моделей из данной серии
    public function getBMWModels($vb, $br)
    {
        $body = "t=BMW&f=" . __FUNCTION__ . $this->_auth . "&vb=$vb&br=$br";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }

/// Доступные опции для модели - расположение руля, КПП (механика/автомат)
    public function getBMWOptions($vb, $br, $ks, $ml, $mt)
    {
        $body = "t=BMW&f=" . __FUNCTION__ . $this->_auth . "&vb=$vb&br=$br&ks=$ks&ml=$ml&mt=$mt";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }

/// Дата производства модели
    public function getBMWProduction($vb, $br, $ks, $ml, $mt, $rl, $am)
    {
        $body = "t=BMW&f=" . __FUNCTION__ . $this->_auth . "&vb=$vb&br=$br&ks=$ks&ml=$ml&mt=$mt&rl=$rl&am=$am";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }

/// Основные группы деталей для модели
    public function getBMWGroups($vb, $br, $ks, $ml, $mt, $rl, $am, $pr, $ln = "ru")
    {
        $body = "t=BMW&f=" . __FUNCTION__ . $this->_auth . "&vb=$vb&br=$br&ks=$ks&ml=$ml&mt=$mt&rl=$rl&am=$am&pr=$pr&ln=$ln";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }

/// Список деталей, входящих в группу/узел
    public function getBMWSubGroups($vb, $br, $ks, $ml, $mt, $rl, $am, $pr, $gr, $ln = "ru")
    {
        $body = "t=BMW&f=" . __FUNCTION__ . $this->_auth . "&vb=$vb&br=$br&ks=$ks&ml=$ml&mt=$mt&rl=$rl&am=$am&pr=$pr&gr=$gr&ln=$ln";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }

/// Иллюстрация детали со списком номенклатуры
    public function getBMWDetailsMap($vb, $br, $ks, $ml, $mt, $rl, $am, $pr, $gr, $gid, $ln = "ru")
    {
        $body = "t=BMW&f=" . __FUNCTION__ . $this->_auth .
            "&vb=$vb&br=$br&ks=$ks&ml=$ml&mt=$mt&rl=$rl&am=$am&pr=$pr&gr=$gr&gid=$gid&ln=$ln" .
            "&uIP=" . $this->uIP . "&uAgent=" . $this->uAgent;
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }

/// Поиск модели по VIN
    public function searchBMWVIN($vin)
    {
        $body = "t=BMW&f=" . __FUNCTION__ . $this->_auth . "&vin=$vin";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }

/// Поиска детали
    public function searchBMWNumber($number)
    {
        $body = "t=BMW&f=" . __FUNCTION__ . $this->_auth . "&number=$number";
        $answer = $this->getAnswer($body);
        $r = json_decode($answer);
        return $r;
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////