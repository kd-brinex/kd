<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс ETKA

/// Устанавливаем объект $oETKA - объект для работы с оригинальным каталогом ETKA
$oETKA = ETKA::instance();

/// Получаем данные с перехода по ссылке из etka/markets.php
$mark   = $oETKA->rcv('mark');
$market = $oETKA->rcv('market');

/// Запрос на доступные модели, вошедшие в выбранную серию
$ETKAModels = $oETKA->getETKAModels($mark,$market); ///$oETKA->e($ETKAModels);
/// Проверим на ошибки и сообщим при наличии
if( ($aErrors = A2D::property($ETKAModels,'errors')) ) $oETKA->error($aErrors,404);

/// Передаем массив с "хлебными крошками" в конструктор
A2D::$aBreads = A2D::getBreads($ETKAModels,'breads','etka'); ///$oETKA->e($aBreads);

/// Формируем имя марки для представления
$markName = "&laquo;".A2D::$markName."&raquo;";

/// Получаем из общего объекта список моделей
$oModels = A2D::property($ETKAModels,'models',[]);

/// Базовая часть пути для перехода на следующий этап
$url = BASE_PATH."/etka/production.php?mark={$mark}&market={$market}";

?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/etka.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="ETKAModels" class="AutoDealer mark">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <h1>Список моделей <?=$markName?></h1>

    <table id="dataTable" class="dataTable" width="100%">
        <thead>
            <tr>
                <th>Модель</th>
                <th>Код модели</th>
                <th>Период</th>
                <th>Производство</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($oModels as $m){?>
                <tr class="models">
                    <td align="left">
                    <a href="<?=$url?>&model=<?=$m->modell?>" class="nextStep">
                        <?=$m->bezeichnung?>
                    </a>
                </td>
                <td><?=$m->modell?></td>
                <td><?=$m->einsatz . "-" . ($m->auslauf == 0 ? "" : $m->auslauf)?></td>
                <td><?=A2D::split($m->produktionswerk,';')?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

</div>