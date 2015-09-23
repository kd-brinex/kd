<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс TD

/// Устанавливаем объект $oTD - объект для работы с неоригинальными каталогами
$oTD = TD::instance();

/// Получаем данные с перехода по ссылке из td/marks.php
$type  = $oTD->rcv('type');
$mark  = $oTD->rcv('mark');
$model = $oTD->rcv('model');

/// Запрашиваем модели
$_oCompl = $oTD->getTDCompl($type,$mark,$model); ///$oTD->e($_oCompl);
/// Обработаем ошибки
if( ($aErrors = A2D::property($_oCompl,'errors')) ) $oTD->error($aErrors,404);

/// Передаем массив с "хлебными крошками" в конструктор
A2D::$aBreads = A2D::getBreads($_oCompl,'breads','td');

/// Получаем комплектации из ответа
$oCompl = A2D::property($_oCompl,'compl',[]); ///$this->e($this->aBreads);
/// В модельИнфо доступная информация по выбранной модели, накапливается с каждым шагом
$modelInfo = A2D::property($_oCompl,'modelInfo',[]);
/// Доступно наименование марки
$markName = A2D::property($modelInfo,'markName',[]);
/// И наименование модели
$modelName = A2D::property($modelInfo,'modelName',[]);

$h1 = "Список комплектаций $markName $modelName";

/// Базовая часть пути для перехода на следующий этап
$url = "/td/tree.php?type={$type}&mark={$mark}&model={$model}";
?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/td.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="TDCompl">
    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <h1><?=$h1?></h1>
    <br/>

    <div id="complects">

        <table class="dataTable">
            <thead>
            <tr>
                <th class="info"></th>
                <th class="type">Тип</th>
                <th>Описание</th>
                <th>Период пр-ва</th>
                <th>Мощн. (кВт)</th>
                <th>Мощн. (л.с.)</th>
                <th>Объем куб.см.</th>
                <th>Вид конструкции</th>
                <?php /*/?>
                <th>Конфигурация оси</th>
                <th>Тоннаж</th>
                <?php //*/?>
            </tr>
            </thead>
            <tbody>
            <?php foreach( $oCompl as $model ){?>
                <tr onclick="window.location.href='<?=$url?>&compl=<?=$model->typ_id?>'">
                    <td>
                        <a class="information anime" href="/td/model.php?compl=<?=$model->typ_id?>" data-url="" target="_blank">i</a>
                    </td>
                    <td align="left"><?=$model->typ_cds?></td>
                    <td align="left">
                        <a href='<?=$url?>&compl=<?=$model->typ_id?>' title='<?=$model->typ_mmt_cds?>'>
                            <?=$model->typ_mmt_cds?>
                        </a>
                    </td>
                    <td><?=TD::dateConvert(['date'=>$model->typ_pcon_start])?> - <?=TD::dateConvert(['date'=>$model->typ_pcon_end])?></td>
                    <td><?=$model->typ_kw_from?></td>
                    <td><?=$model->typ_hp_from?></td>
                    <td><?=$model->typ_ccm?></td>
                    <td><?=$model->typ_kv_body_des?></td>
                    <?php /*/?>
                    <td><?=$model->typ_kv_axle_des?></td>
                    <td><?=$model->typ_max_weight?></td>
                    <?php //*/?>
                </tr>
            <?php }?>
            </tbody>
        </table>

    </div>

</div>