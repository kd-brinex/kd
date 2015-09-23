<?php

/** Обязательно к применению */
include "../../_lib.php"; /// После подключения доступен класс A2D
include "../api.php";     /// После подключения доступен класс BMW

/// Устанавливаем объект $oBMW - объект для работы с оригинальным каталоговом BMW
$oBMW = BMW::instance(); ///$oBMW->e([$_GET,$_POST,$_SESSION,$_SERVER]);

/// Получаме рефер ссылку, чтобы пользователя можно было вернуть на предыдущую страницу
$refer = A2D::get($_SERVER,'HTTP_REFERER');

/// Получаем переменные с формы поиска
$vin = $oBMW->rcv('vin');

/// Отправляем запрос в надежде, что наш VIN отработает
$aResult = $oBMW->searchBMWVIN($vin); ///$oBMW->e($aResult);

/// Если с сервера вернулись ошибки, обрабатываем
if( ($errors = A2D::property($aResult,'errors')) ){
    if( $errors->msg=="_BMW_Search_VIN Empty_VIN" ) $msg = "Пустой VIN";
    elseif( $errors->msg=="_BMW_Search_VIN Empty_Response" ) $msg = "По Вашему запросу ничего не найдено";
    else $msg = $errors->msg;
    $adRef = "<br/><a href=\"$refer\">Вернуться на предыдущую страницу</a>";
    $oBMW->error($msg.$adRef);
}
/// При результате обрабатываем полученные данные
else{

    /**
     * В BMW в отличие от того же поиска по VIN в Toyota (toyota/search/vin.php),
     * либо есть модель, либо ее нет
     * Но мы все же решили подстраховаться и отдаем с сервера данные, хоть и одну строку, но в массиве
     * Поэтому сперва выберем ее единственную
     * Если будут замечаться какие то несостыковки отключаем редирект и принтим то что нам пришло с сервера
    */
    $aCurrent = current($aResult);

    /// Получаем данные для построения ссылки для перехода на страницу модели
    $type   = A2D::property($aCurrent,'Katalogumfang');
    $series = A2D::property($aCurrent,'Baureihe');
    $body   = A2D::property($aCurrent,'Karosserie');
    $model  = A2D::property($aCurrent,'Modellspalte');
    $market = A2D::property($aCurrent,'Region');
    $rule   = A2D::property($aCurrent,'Lenkung');
    $trans  = A2D::property($aCurrent,'GetriebeCode');
    $prod   = A2D::property($aCurrent,'Einsatz');

    /**
     * Тут нам нужна марка и пришлось провернуть через сессии.
     * Марка устанавливается в момнет формирования объекта (кроме поисковых скриптов)
     * Форма поиска инклудится в файл, который и пишет в сессию марку
    */
    $mark = A2D::get($_SESSION,'mark');
    /// Если найдена машина, то сразу редиректим к основным группам деталей
    $url = "/bmw/groups.php?mark={$mark}&type={$type}&series={$series}&body={$body}&model={$model}&market={$market}&rule={$rule}&transmission={$trans}&production={$prod}"; ///$oBMW->e($url);
    header("Location: $url");

}
