<?php

/** Обязательно к применению */
include "../../_lib.php"; /// После подключения доступен класс A2D
include "../api.php";     /// После подключения доступен класс TOY

/// Устанавливаем объект $oTOY - объект для работы с каталогом Toyota
$oTOY = TOY::instance(); ///$oTOY->e([$_GET,$_POST,$_SESSION,$_SERVER]);

/// Получаме рефер ссылку, чтобы пользователя можно было вернуть на предыдущую страницу
$refer  = A2D::get($_SERVER,'HTTP_REFERER');

/// Получаем переменные с формы поиска
$vin = $oTOY->rcv('vin');  /// VIN номер, по которому ищем модель

/// Отправляем запрос, в надежде, что наш VIN отработает
$searchRezult = $oTOY->searchToyotaVIN($vin); ///$oTOY->e($searchRezult);

/// Если с сервера вернулись ошибки, обрабатываем
if( ($errors = A2D::property($searchRezult,'errors')) ){
    if( $errors->msg=="_search_vin empty" ) $msg = "По Вашему запросу ничего не найдено";
    elseif( $errors->msg=="_Toyota_VIN Short" ) $msg = "Минимальное значение 9 символов";
    else $msg = $errors->msg;
    $adRef = "<br/><a href=\"$refer\">Вернуться на предыдущую страницу</a>";
    $oTOY->error($msg.$adRef);
}
/// При результате, обрабатываем полученные данные
else{
    /// Получаем тип возвращаемого результат с поиска (чуть подробнее ниже)
    $sType    = A2D::property($searchRezult,'type');
    /// Сам результат
    $aResult  = A2D::property($searchRezult,'models',[]);

    /**
     * Чтобы вручную не ассоциировать массив для всех форм, мы применили такой хук
     * Вам в продакшене нужно самостоятельно решить какие поля использовать, а какие нет
     * ~ Не забываем, что все же это примеры ~
    */
    $aCurrent = current($aResult);
    $aFields = []; foreach( $aCurrent AS $k=>$v ) $aFields[] = $k;

    /// Получаем данные для построения ссылки для перехода на страницу модели
    $mark    = A2D::get($_SESSION,'mark');
    $market  = A2D::property($aCurrent,'market');
    $catalog = A2D::property($aCurrent,'catalog');
    $model   = A2D::property($aCurrent,'modelCode');
    $sysopt  = A2D::property($aCurrent,'sysopt',"sysopt");

    /// Формируем саму ссылку (базовая часть)
    $_nextUrl = "/toyota/groups.php?mark=$mark&market=".strtolower($market)."&model=$catalog&compl=$model&opt=$sysopt";

    /// В зависимости от вернувшегося типа сообщения с данными, продолжаем формировать ссылки на найденные модели
    switch( $sType ){
        /// Точное совпадение
        case("detailedSearch"):
            $vdate  = A2D::property($aCurrent,'vdate');
            $siyopt = A2D::property($aCurrent,'siyopt_code');
            $gets   = "&vin=$vin&vdate=$vdate&siyopt=$siyopt";

            $compl    = A2D::property($aCurrent,'compl');
            $url      = ( count($aResult)==1 ) ?"$_nextUrl&code=$compl".$gets :$_nextUrl.$gets;
            /// Хоть всегда возращается одно значение, все-равно предусмотрим возврат нескольких моделей
            $viewFile = "DetailedSearch";
            break;
        /// Неточное совпадение, часто возвращается не одно значение
        case("possibleModels"):
            $compl    = A2D::property($aCurrent,'compl');
            $url      = ( count($aResult)==1 ) ?"$_nextUrl&code=$compl" :$_nextUrl;
            $viewFile = "PossibleModels";
            break;
        /// Сервер не нашел совпадений, но попытался что-то вытащить по введенным данным
        /// В результате могут присутсвтовать модели, которые не соответсвуют ожиданиям
        case("noMatch"):
            $url      = $_nextUrl;
            $viewFile = "NoMatch";
            break;
        /// Бывает и совсем плохо
        default:
            $msg = "Сервер не вернул данных";
            $viewFile = "Response";

    } ///$oTOY->e([$viewFile]);
    /// Если сервер вернул одну модель, нет смысла показывать одну строчку, сразу переходим в модель
    if( count($aResult)==1 ) header("Location: $url");

    /// Что тут происходит можно понять из схожей ситуации в toyota/options.php
    $aMagic = [ /// Magic!!!
        1 => "Двигатель",
        2 => "Кузов",
        3 => "Класс",
        4 => "КПП",
        5 => "Другое"
    ];
    $aList = [];
    $_list = A2D::property($searchRezult,'info',[]); ///$oTOY->e($_list);

    /// Переформировываем список расшифровок под свой лад
    $i =0; foreach( $_list AS $l ){ ++$i;
        if( $l->type==1 OR $l->type==2)      $k = 1;
        elseif( $l->type==3 )                $k = 2;
        elseif( $l->type==4 )                $k = 3;
        elseif( $l->type==5 OR $l->type==6 ) $k = 4;
        else                                 $k = 5;
        $aList[$aMagic[$k]][$l->sign]['sign'] = $l->sign;
        $aList[$aMagic[$k]][$l->sign]['desc'] = $l->desc_en;
    }
}

/**
 * Строим две "хлебные крошки": в корень каталога и на прошлую страницу
 * ~ можете что-то свое реализовать ~
 *
 * У нас появился новый параметр, который раньше не описывался (чтобы не путать)
 * Как успели заметить - последняя крошка идет не ссылкой, а текстом, так как нет смысла ссылаться на самого себя
 * Если мы в ключе крошки передадим refer, система обработает эту крошку как ссылку
 * Да и как такого файла на старт нам не надо
 */
A2D::$catalogRoot = "";
A2D::$aBreads = A2D::toObj([
    'series' => [
        "name" => 'Каталог',
        "breads" => []
    ],
    'refer' => [
        'url' => $refer,
        'txt' => 'Вернуться на предыдущую страницу'
    ],
]);

?>

    <meta charset="utf-8">
    <link href="../../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
    <link href="../../media/css/style.css" media="all" rel="stylesheet" type="text/css">
    <link href="../../media/css/toyota.css" media="all" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>

<?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

<?php include WWW_ROOT."helpers/search.php"; /// Подключаем форму поиска?>

<?php include WWW_ROOT."/toyota/search/tpl/vin/{$viewFile}.php";  /// Подключаем файл шаблон в зависимости от результат поиска



