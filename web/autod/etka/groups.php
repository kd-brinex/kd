<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс ETKA

/// Устанавливаем объект $oETKA - объект для работы с оригинальным каталогом ETKA
$oETKA = ETKA::instance();

/// Получаем данные с перехода по ссылке из etka/production.php
$mark   = $oETKA->rcv('mark');
$market = $oETKA->rcv('market');
$model  = $oETKA->rcv('model');
$year   = $oETKA->rcv('year');
$code   = $oETKA->rcv('code');
$dir    = $oETKA->rcv('dir');

/// Запрос на доступные модели, вошедшие в выбранную серию
$oGroups = $oETKA->getETKAGroups($mark,$market,$model,$year,$code,$dir); ///$oETKA->e($oGroups);
/// Проверим на ошибки и сообщим при наличии
if( ($errors = A2D::property($oGroups,'errors')) ) $oETKA->error($errors,404);

/// Можно воспользоваться данной функцией, если нужно модифицировать "хлебные крошки"
///$aBreads = A2D::getBreads($oGroups,'breads','etka'); ///$oETKA->e($aBreads);
/// На текущий момнет нам достаточно того что есть
$aBreads = A2D::property($oGroups,'breads','etka'); ///$oETKA->e($aBreads);

/// Основные узлы
$hg   = A2D::property($oGroups,'hg','A');
/// (в разработке) Список оригинальных запчастей для сервисного обслуживания и расходных материалов
$faps = A2D::property($oGroups,'faps','A');

///$markName = $aBreads->mark->name;
$markName = ucfirst($mark);
$modelName = $aBreads->groups->name;
$h1 = "Основные группы деталей для $markName $modelName";

/// После работы с "хлебными крошками" передаем их в конструктор
A2D::$aBreads = $aBreads;

/// Базовая часть пути для перехода на следующий этап
$url = BASE_PATH."/etka/subgroups.php?mark={$mark}&market={$market}&model={$model}&year={$year}&code={$code}";

?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/etka.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="ETKAGroups">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <h1><?=$h1?></h1>

    <div id="detailsG1">

        <?php foreach( $hg AS $_hg ){?>
        <a href="<?=$url."&type=G&group=".$_hg->hg?>" class="fl defBorder">
            <img src="/media/images/etka/groups/<?=$_hg->hg?>.png" alt="" /><br />
            <span><?=$_hg->text;?></span>
        </a>
        <?php }?>

        <?php /// Хоть и в разработке, но можно глянуть идею реализации, добавив GET параметр services \\\ ?>
        <?php if( A2D::get($_GET,'services') ) foreach( $faps AS $row ){?>
        <a href="<?=$url."&type=S&group=".$row->oid?>" class="fl defBorder">
            <img src="/media/images/etka/groups/<?=$row->oid?>.png"/><br />
            <span><?=$row->text;?></span>
        </a>
        <?php }?>

    </div>
    <div class="clear"></div>

</div>