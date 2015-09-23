<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс TD

/// Устанавливаем объект $oTD - объект для работы с неоригинальными каталогами
$oTD = TD::instance();

/// Получаем данные с перехода по ссылке из td/marks.php
$type = $oTD->rcv('type');
$mark = $oTD->rcv('mark');

/// Запрашиваем модели
$_oModels = $oTD->getTDModels($type,$mark); ///$oTD->e($_oModels);
/// Обработаем ошибки
if( ($aErrors = A2D::property($_oModels,'errors')) ) $oTD->error($aErrors,404);

/// Передаем массив с "хлебными крошками" в конструктор
A2D::$aBreads = A2D::getBreads($_oModels,'breads','td');

/// Получаем модели из ответа
$oModels = A2D::property($_oModels,'models',[]);

/// В модельИнфо доступная информация по выбранной модели, накапливается с каждым шагом
$modelInfo = A2D::property($_oModels,'modelInfo',[]);
/// К примеру, на текущем этапе известно имя марки
$markName = A2D::property($modelInfo,'markName',[]);
$h1 = "Список моделей $markName";

/// Базовая часть пути для перехода на следующий этап
$url = "/td/compl.php?type={$type}&mark={$mark}";
?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/td.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="TDModels">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <h1><?=$h1?></h1>
    <br/>

    <div id="body">

        <table id="dataTable" class="dataTable">
            <thead>
            <tr>
                <th>Название (русский)</th>
                <th>Название (english)</th>
                <th nowrap>Период производства</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach( $oModels AS $oModel ){?>
                <tr onclick="window.location.href='<?=$url?>&model=<?=$oModel->mod_id?>'">
                    <td align="left">
                        <a href="<?=$url?>&model=<?=$oModel->mod_id?>" title="<?=$oModel->mod_name?>" >
                            <?=$oModel->mod_name?>
                        </a>
                    </td>
                    <td align="left"><?=$oModel->mod_name_eng?></td>
                    <td align="left"><?=TD::dateConvert(['date'=>$oModel->mod_pcon_start])?> - <?=TD::dateConvert(['date'=>$oModel->mod_pcon_end])?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>

    </div>

</div>