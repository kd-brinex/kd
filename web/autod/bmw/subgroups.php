<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс BMW

/// Устанавливаем объект $oBMW - объект для работы с оригинальным каталогом BMW
$oBMW = BMW::instance();

/// Получаем данные с перехода по ссылке из bmw/groups.php
$mark   = $oBMW->rcv('mark');
$type   = $oBMW->rcv('type');
$series = $oBMW->rcv('series');
$body   = $oBMW->rcv('body');
$model  = $oBMW->rcv('model');
$market = $oBMW->rcv('market');
$rule   = $oBMW->rcv('rule');
$trans  = $oBMW->rcv('transmission');
$prod   = $oBMW->rcv('production');
$group  = $oBMW->rcv('group');

/// Получаем список деталей для выбранного узла нашей модели
$BMWSubGroups = $oBMW->getBMWSubGroups($type,$series,$body,$model,$market,$rule,$trans,$prod,$group,"ru"); ///$oBMW->e($BMWSubGroups);
/// Проверяем на ошибки
if( ($aErrors = A2D::property($BMWSubGroups,'errors')) ) $oBMW->error($aErrors,404);

/// Получаем "хлебные крошки" сперва в простую переменную (пригодится для работы)
$aBreads = A2D::property($BMWSubGroups,'aBreads',[]); ///$oBMW->e($aBreads);

/// Где сейчас находимся? Можно узнать из крошек!
$lvl2Name = $aBreads->subgroups->name;
/// Получаем имя серии в нужном нам виде через модифицирующую функцию
$seriesName = $oBMW->_getSeries($aBreads->models->name);
/// Так же получаем имя марки
$markName = $oBMW->_getMarkName($mark);
/// Имя модификации
$modif = $aBreads->groups->name;
/// На основе полученных данных выше, строим заголовок для страницы
$h1 = "$lvl2Name для $markName $seriesName ($modif)";

/// После работы с переменной/крошками передаем ее в объект для конструктора
A2D::$aBreads = $aBreads;

/// Информация о выбранной модели. В нашем представлении данные расположены в самом верху
$modelInfo = A2D::property($BMWSubGroups,'modelInfo',[]);
/// Информация по деталям выбранного узла с иллюстрациями
$aData = A2D::property($BMWSubGroups,'aData',[]);

/// Базовая часть пути для перехода на следующий этап
$url = BASE_PATH."/bmw/illustration.php?mark={$mark}&type={$type}&series={$series}&body={$body}&model={$model}&market={$market}&rule={$rule}&transmission={$trans}&production={$prod}&group={$group}";

?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/bmw.css" media="all" rel="stylesheet" type="text/css">



<div id="detailsG2" class="AutoDealer">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <div>

        <h1><?=$h1?></h1>

        <?php include "modelInfo.php";?>

        <?php foreach( $aData AS $v ){?>
            <a href="<?=$url?>&graphic=<?=$v->code?>" class="fl defBorder">
                <img src="<?=$v->imgUrl?>"><br/>
                <span><?=$v->name?></span>
            </a>
        <?php }?>
        <hr class="clear">

    </div>

</div>