<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс BMW

/// Устанавливаем объект $oBMW - объект для работы с оригинальным каталогом BMW
$oBMW = BMW::instance();

/// Получаем данные с перехода по ссылке из bmw/production.php
$mark   = $oBMW->rcv('mark');
$type   = $oBMW->rcv('type');
$series = $oBMW->rcv('series');
$body   = $oBMW->rcv('body');
$model  = $oBMW->rcv('model');
$market = $oBMW->rcv('market');
$rule   = $oBMW->rcv('rule');
$trans  = $oBMW->rcv('transmission');
$prod   = $oBMW->rcv('production');

/// Получаем основные узлы деталей на основе выбранных параметров ранее
$BMWGroups = $oBMW->getBMWGroups($type,$series,$body,$model,$market,$rule,$trans,$prod,$lang); ///$oBMW->e($BMWGroups);
/// Проверяем на ошибки
if( ($aErrors = A2D::property($BMWGroups,'errors')) ) $oBMW->error($aErrors,404);

/// Получаем "хлебные крошки" сперва в простую переменную (пригодится для работы)
$aBreads = A2D::property($BMWGroups,'aBreads',[]);

/// Получаем имя серии в нужном нам виде через модифицирующую функцию
$seriesName = $oBMW->_getSeries($aBreads->models->name);
/// Так же получаем имя марки
$markName = $oBMW->_getMarkName($mark);
/// Имя модификации
$modif = $aBreads->groups->name;
/// На основе полученных данных выше строим заголовок для страницы
$h1 = "Основные группы деталей для $markName $seriesName ($modif)";

/// После работы с переменной/крошками, передаем ее в объект для конструктора
A2D::$aBreads = $aBreads;

/// Информация о выбранной модели. В нашем представлении данные расположены в самом верху
$modelInfo = A2D::property($BMWGroups,'modelInfo',[]);
/// Информация по узлам с иллюстрациями
$aData = A2D::property($BMWGroups,'aData',[]);

/// Базовая часть пути для перехода на следующий этап
$url = BASE_PATH."/bmw/subgroups.php?mark={$mark}&type={$type}&series={$series}&body={$body}&model={$model}&market={$market}&rule={$rule}&transmission={$trans}&production={$prod}";

?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/bmw.css" media="all" rel="stylesheet" type="text/css">



<div id="detailsG1" class="AutoDealer">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <div>

        <h1><?=$h1?></h1>

        <?php include "modelInfo.php";?>

        <?php foreach( $aData AS $v ){?>
            <a href="<?=$url?>&group=<?=$v->code?>" class="fl defBorder">
                <img src="<?=$v->imgUrl?>"><br/>
                <span><?=$v->name?></span>
            </a>
        <?php }?>
        <hr class="clear">

    </div>

</div>