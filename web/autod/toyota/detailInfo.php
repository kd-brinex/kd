<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс TOY

/// Устанавливаем объект $oTOY - объект для работы с каталогом Toyota
$oTOY = TOY::instance();

/// Получаем данные с перехода по ссылке из toyota/illustration.php
$mark    = $oTOY->rcv('mark');
$market  = $oTOY->rcv('market');
$model   = $oTOY->rcv('model');
$compl   = $oTOY->rcv('compl');
$opt     = $oTOY->rcv('opt');
$code    = $oTOY->rcv('code');
$group   = $oTOY->rcv('group');
$graphic = $oTOY->rcv('graphic');
$detail  = $oTOY->rcv('detail');
/// Вспомогательные параметры передаются самостоятельно в метод
$vin       = $oTOY->rcv('vin');
$vdate     = $oTOY->rcv('vdate');
$siyopt    = $oTOY->rcv('siyopt');

/// Запрашиваем информацию о детали согласно переданным критериям
$aPic = $oTOY->getToyPnc($market,$model,$compl,$opt,$code,$group,$graphic,$detail,$vin,$vdate,$siyopt); //$this->e($aPic);

/// Получаем из общего свойства объекта
$aDetInfo  = A2D::property($aPic,'detInfo');   //$this->e($aDetInfo);

/// "Хлебные крошки" тоже есть, но сейчас мы делаем запрос через AJAX

/// Если ничего не вернулось, сообщаем
if( !$aDetInfo ) die( json_encode("NULL REQUEST") );
/// При результате отдаем все в виде JSON строки
die( json_encode($aDetInfo) );