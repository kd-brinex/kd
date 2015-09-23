<?php

/** Обязательно к применению */
include "../../_lib.php"; /// После подключения доступен класс A2D
include "../api.php";     /// После подключения доступен класс TOY

/// Устанавливаем объект $oTOY - объект для работы с каталогом Toyota
$oTOY = TOY::instance(); ///$oTOY->e([$_GET,$_POST,$_SESSION,$_SERVER]);

/// Получаме рефер ссылку, чтобы пользователя можно было вернуть на предыдущую страницу
$refer  = A2D::get($_SERVER,'HTTP_REFERER');

/// Шаблон вида по умолчанию
$viewFile = "Response";

/// Получаем переменные с формы поиска
$mark   = $oTOY->rcv('mark');
$frame  = $oTOY->rcv('frame');
$number = $oTOY->rcv('number');

/// Отправляем запрос на поиск модели по фрейму и номеру
$aResult = $oTOY->searchToyotaFrame($frame,$number); ///$oTOY->e($aResult);

/// Если с сервера вернулись ошибки, обрабатываем
if( ($errors = A2D::property($aResult,'errors')) ){
    if( $errors->msg=="_Toyota_Search_Frame Empty_Response" ) $msg = "По Вашему запросу ничего не найдено";
    else $msg = $errors->msg;
    $adRef = "<br/><a href=\"$refer\">Вернуться на предыдущую страницу</a>";
    $oTOY->error($msg.$adRef);
}
/// При результате обрабатываем полученные данные
else{
    /// Устанавливаем вид для отображения данных
    $viewFile = "Index";

    /**
     * Чтобы вручную не ассоциировать массив для всех форм, мы применили такой хук
     * Вам в продакшене нужно самостоятельно решить какие поля использовать, а какие нет
     * ~ Не забываем, что все же это примеры ~
     */
    $aCurrent = current($aResult);
    $aFields = []; foreach( $aCurrent AS $k=>$v ) $aFields[] = $k;

    /// Получаем данные для построения ссылки для перехода на страницу модели
    $market  = A2D::property($aCurrent,'catalog');
    $catalog = A2D::property($aCurrent,'catalog_code');
    $model   = A2D::property($aCurrent,'model_code');
    $sysopt  = A2D::property($aCurrent,'sysopt',"sysopt");
    $compl   = A2D::property($aCurrent,'compl_code');
    $vdate   = A2D::property($aCurrent,'vdate');
    $siyopt  = A2D::property($aCurrent,'siyopt_code');

    /// Формируем саму ссылку (базовая часть)
    $nextUrl = "/toyota/groups.php?mark=$mark&market=".strtolower($market)."&model=$catalog&compl=$model&opt=$sysopt&code=$compl&vdate=$vdate&siyopt=$siyopt";

    /// Если сервер вернул одну модель, то нет смысла показывать одну строчку, сразу переходим в модель
    if( count($aResult)==1 ) header("Location: $nextUrl");
}

/**
 * Строим две "хлебные крошки": в корень каталога и на прошлую страницу
 * ~ можете что то свое реализовать ~
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
