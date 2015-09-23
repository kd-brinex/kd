<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс BMW

/// Устанавливаем объект $oBMW - объект для работы с оригинальным каталогом BMW
$oBMW = BMW::instance();

/// Получаем марку с перехода по ссылке из marks.php
$mark = $oBMW->rcv('mark');

/// Запрашиваем выпускаемые серии для выбранной марки (bmw|moto|mini|rr)
$BMWSeries = $oBMW->getBMWCatalogs($mark); ///$oBMW->e($BMWSeries);
/// Сперва проверим на ошибки
if( ($aErrors = A2D::property($BMWSeries,'errors')) ) $oBMW->error($aErrors,404);

/// Мотоциклы находятся в 4 группе (тип) в нашем каталоге, все остальное в 9
/// Узнать можно из адресной строки когда находимся в группе и видим нашу марку
$typeID = ( $mark=="moto" )?4:9;

/// С сервера вернулся объект со следующими свойствами:
$img = A2D::property($BMWSeries,'img');   /// Ссылка на изображение марки
$vt  = A2D::property($BMWSeries,'vt',[]); /// Список техники (мотоциклы или автомобили)
$st  = A2D::property($BMWSeries,'st',[]); /// Список ретро техники (выделенно в отдельную группы в самом каталоге)

/// "Хлебные крошки" не родные - изменяем ассоциативный массив для имен под переменные
A2D::$arrActions = ['typeID','markID'];
/// На точки входа нет переменных для крошек, строим их самостоятельно
A2D::$aBreads = A2D::toObj([
    'types' => [
        "name" => 'Каталог',
        "breads" => []
    ],
    'marks' => [
        "name" => 'Легковые (иномарки)',
        "breads" => [ 0 => $typeID ]
    ],
    'models' => [
        "name" => A2D::$markName,
        "breads" => []
    ],
]); ///$oBMW->e($aBreads);
/// Текущие крошки ведут в корень, зануляем корневой каталог для старта скриптов
A2D::$catalogRoot = "";

/// Базовая часть пути для перехода на следующий этап
$url = BASE_PATH."/bmw/models.php?mark={$mark}";

?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/bmw.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="BMWCatalog" class="AutoDealer">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <?php include WWW_ROOT."helpers/search.php"; /// Подключаем форму поиска?>

    <div>

        <?php foreach( $vt AS $v ){?>
            <a href="<?=$url?>&type=vt&series=<?=$v->Baureihe?>" class="cardLink fl mb10">
                <img src="<?=$v->imgUrl?>"><br/>
                <?=$v->ExtBaureihe?>
            </a>
        <?php }?>
        <div class="clear"></div>

        <br/><br/><br/>

        <?php if( $st ){?>
            <h2>Живая традиция</h2>
            <?php foreach( $st AS $s ){?>
                <a href="<?=$url?>&type=st&series=<?=$s->Baureihe?>" class="cardLink fl mb10">
                    <img src="<?=$s->imgUrl?>"><br/>
                    <?=$s->ExtBaureihe?>
                </a>
            <?php }?>
            <div class="clear"></div>
        <?php }?>

    </div>

</div>