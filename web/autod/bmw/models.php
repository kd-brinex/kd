<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс BMW

/// Устанавливаем объект $oBMW - объект для работы с оригинальным каталогом BMW
$oBMW = BMW::instance();

/// Получаем данные с перехода по ссылке из bmw/series.php
$mark   = $oBMW->rcv('mark');
$type   = $oBMW->rcv('type');
$series = $oBMW->rcv('series');

/// Запрос на доступные модели, вошедшие в выбранную серию
$BMWModels = $oBMW->getBMWModels($type,$series); ///$oBMW->e($BMWModels);
/// Проверим на ошибки и сообщим при наличии
if( ($aErrors = A2D::property($BMWModels,'errors')) ) $oBMW->error($aErrors,404);

/**
 * Начиная с данного шага "хлебные крошки" уже возвращаются с сервера
 * Работать с простой переменной приятнее, чем со статической. Сформируем сперва ее
*/
$aBreads = A2D::property($BMWModels,'aBreads',[]);

/// Получаем имя серии в нужном нам виде через модифицирующую функцию
$seriesName = $oBMW->_getSeries($aBreads->models->name);
/// Так же получаем имя марки
$markName   = $oBMW->_getMarkName($mark);

/// После работы с переменной/крошками, передаем ее в объект для конструктора крошек
A2D::$aBreads = $aBreads;

/**
 * Получаем из общего объекта список моделей
 * Список разбит на группы по кузовам
 * Для каждой группы присуствует свое изображение кузова
 */
$oModels = A2D::property($BMWModels,'aModels',[]);

/// Базовая часть пути для перехода на следующий этап
$url = BASE_PATH."/bmw/options.php?mark={$mark}&type={$type}&series={$series}";

?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/bmw.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="BMWModels" class="AutoDealer mark<?=$markName?>">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <h1>Список моделей <?=$markName?> <?=$seriesName?></h1>

    <table>
        <?php foreach( $oModels AS $b ){?>
            <tr class="inlineBlock alignTop mb30">
                <td>
                    <img src="<?=$b->image?>"><br>
                    <span><?=ucfirst($b->name)?></span>
                </td>
                <td class="text-left pl20">
                    <?php foreach( $b->models AS $modelCode => $models ){?>
                        <div class="bold mb10"><?=$models->MarketName?></div>
                        <div class="mb20">
                            <?php foreach( $models->ModelInfo AS $m ){?>
                                <a class="bttnGB anime fl mg2 getOptions" href="<?=$url?>&body=<?=$b->code?>&model=<?=$m->ModelID?>&market=<?=$models->MarketCode?>"><?=$m->ModelCode?></a>
                            <?php }?>
                            <div class="clear"></div>
                        </div>
                    <?php }?>
                </td>
            </tr>
        <?php }?>
    </table>

</div>