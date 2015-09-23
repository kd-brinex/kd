<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс ETKA

/// Устанавливаем объект $oETKA - объект для работы с каталогом ETKA
$oETKA = ETKA::instance();

/// Получаем марку с перехода по ссылке из marks.php
$mark = $oETKA->rcv('mark');

/// Получаем доступные рынки. Второй строкой останавливаемся при ошибках с сервера
$ETKAMarkets = $oETKA->getETKAMarkets($mark); ///$oETKA->e($ETKAMarkets);
if( ($aErrors = A2D::property($ETKAMarkets,'errors')) ) $oETKA->error($aErrors,404);

/// В отличие от Toyota здесь доступный список рынков мы получаем в свойстве возвращаемого объекта с сервера
$oMarkets = A2D::property($ETKAMarkets,'markets',[]);
/// Вторым свойством идут хлебные крошки, нужный когда используем отдельную точку входа

/// В ETKA в отличии от BMW (bmw/series.php) все марки принадлежат группе с ID=9 в нашем каталоге
/// Узнать можно из адресной строки когда находимся в группе и видим нашу марку
$typeID = 9;

/// "Хлебные крошки" не родные - изменяем ассоциативный массив для имен под переменные
A2D::$arrActions = ['typeID'];
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
]);
/// Текущии крошки ведут в корень, зануляем корневой каталог для старта скриптов
A2D::$catalogRoot = "";

/// Базовая часть пути для переходя на следующий этап
$url = BASE_PATH."/etka/models.php?mark={$mark}";
?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/etka.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="ETKACatalog" class="AutoDealer">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <?php include WWW_ROOT."helpers/search.php"; /// Подключаем форму поиска?>

    <div id="catalogMarkets" class="catalogMarkets mb20">
        <ul>
        <?php foreach( $oMarkets AS $m ){?>
            <li class="fl">
            <a href="<?=$url?>&market=<?=$m->code?>">
                <img src="/media/images/etka/markets/<?=strtolower($m->code)?>.png" alt="" class="mb5"/><br />
                <?=$m->ru?>
            </a>
            </li>
        <?php } ?>
        </ul>
    </div>

</div>