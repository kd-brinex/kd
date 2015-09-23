<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс TD

/// Устанавливаем объект $oTD - объект для работы с неоригинальными каталогами
$oTD = TD::instance();


/// Запрос на сервер для получение доступных категорий/типов
$_oTypes = $oTD->getTDTypes(); ///$oTD->e($TDMarks);
/// Обработаем ошибки
if( ($aErrors = A2D::property($_oTypes,'errors')) ) $oTD->error($aErrors,404);

/// Достаем из общих данных наши типы/категории
$oTypes = A2D::property($_oTypes,'types',[]);

/// "Хлебные крошки" не родные - изменяем ассоциативный массив для имен под переменные
A2D::$arrActions = ['typeID','markID'];
/// На точки входа нет переменных для крошек, строим их самостоятельно
A2D::$aBreads = A2D::toObj([
    'types' => [
        "name" => 'Каталог',
        "breads" => []
    ],
    'models' => [
        "name" => "Неоригинальные каталоги",
        "breads" => []
    ],
]); ///$oBMW->e($aBreads);
/// Текущие крошки ведут в корень, зануляем корневой каталог для старта скриптов
A2D::$catalogRoot = "";

///
$h1 = "Доступные категории";

/// Базовая часть пути для перехода на следующий этап
$url = "/td/marks.php";
?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/td.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="TDTypes">
    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <?php include WWW_ROOT."helpers/search.php"; /// Подключаем форму поиска?>

    <h1><?=$h1?></h1>
    <br/>

    <div id="types" class="layout">

        <?php foreach( $oTypes AS $k=>$oType ){?>
            <div class="fl bttnGB anime mr10">
                <a href="<?=$url?>?type=<?=$k?>"><?=$oType?></a>
            </div>
        <?php } ?>
        <div class="clear"></div>

    </div>

</div>