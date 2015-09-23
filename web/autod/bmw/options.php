<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс BMW

/// Устанавливаем объект $oBMW - объект для работы с оригинальным каталогом BMW
$oBMW = BMW::instance();

/// Получаем данные с перехода по ссылке из bmw/models.php
$mark   = $oBMW->rcv('mark');
$type   = $oBMW->rcv('type');
$series = $oBMW->rcv('series');
$body   = $oBMW->rcv('body');
$model  = $oBMW->rcv('model');
$market = $oBMW->rcv('market');

/// Доступные опции на основе выбранных данных
$BMWOptions = $oBMW->getBMWOptions($type,$series,$body,$model,$market); ///$oBMW->e($BMWOptions);
/// Проверяем на ошибки
if( ($aErrors = A2D::property($BMWOptions,'errors')) ) $oBMW->error($aErrors,404);
/// С "хлебными крошками" никто не работает, можно сразу передать в объект для конструктора
A2D::$aBreads = A2D::property($BMWOptions,'aBreads',[]); ///$oBMW->e($aBreads);

/// Выделяем доступные опции из общего объекта
$aData = A2D::property($BMWOptions,'aData',[]);

/// Базовая часть пути для переходя на следующий этап
$url = BASE_PATH."/bmw/production.php?mark={$mark}&type={$type}&series={$series}&body={$body}&model={$model}&market={$market}";

?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/bmw.css" media="all" rel="stylesheet" type="text/css">



<div id="BMWOptions" class="AutoDealer">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <div>

        <h1>Выберите опцию</h1>
        <?php foreach( $aData AS $m ){?>
            <a href="<?=$url?>&rule=<?=$m->RuleCode?>&transmission=<?=$m->GetriebeCode?>" class="getProduction">
                <?=$m->RuleName?> / <?=$m->GetriebeName?>
            </a><br/>
        <?php }?>

    </div>

</div>