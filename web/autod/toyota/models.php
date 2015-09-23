<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс TOY

/// Устанавливаем объект $oTOY - объект для работы с каталогом Toyota
$oTOY = TOY::instance();

/// Получаем данные с перехода по ссылке из etka/markets.php
$mark   = $oTOY->rcv('mark');
$market = $oTOY->rcv('market');

/// Получаем спосок моделей по марке и рынку. Второй строкой останавливаемся при ошибках с сервера
$TOYModels = $oTOY->getToyModels($mark,$market); ///$oTOY->e($TOYModels);
if( ($aErrors = A2D::property($TOYModels,'errors')) ) $oTOY->error($aErrors,404);

/// С "хлебными крошками" никто не работает можно сразу передать в объект для конструктора
A2D::$aBreads = A2D::property($TOYModels,'aBreads',[]);

/// Получаем модели из общего объекта
$aModels = A2D::property($TOYModels,'aModels',[]);

/// Базовая часть пути для перехода на следующий этап
$url = BASE_PATH."/toyota/options.php?mark={$mark}&market={$market}";

?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/toyota.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../media/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../media/js/dataTable.js"></script>


<div id="TOYModels" class="AutoDealer">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <h1>Список моделей</h1>

    <table id="dataTable" class="dataTable">
        <thead>
            <tr>
                <th>Модель</th>
                <th>Модификации</th>
                <th>Период производства</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach( $aModels AS $_v ){?>
            <tr onclick="window.location.href ='<?=$url?>&model=<?=$_v->modelCode?>';">
                <td><a href="<?=$url?>&model=<?=$_v->modelCode?>"><?=$_v->modelName?></a></td>
                <td><a href="<?=$url?>&model=<?=$_v->modelCode?>"><?=$_v->modifications?></a></td>
                <td><?=$_v->prodaction?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>

</div>