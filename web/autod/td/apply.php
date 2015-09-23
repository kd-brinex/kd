<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс TD

/// Устанавливаем объект $oTD - объект для работы с неоригинальными каталогами
$oTD = TD::instance();

/// Получаем данные с перехода по ссылке из td/marks.php
$type   = $oTD->rcv('type');
$mark   = $oTD->rcv('mark');
$model  = $oTD->rcv('model');
$compl  = $oTD->rcv('compl');
$tree   = $oTD->rcv('tree');
$group  = $oTD->rcv('group');
$vendor = $oTD->rcv('vendor');
$detail = $oTD->rcv('detail');
$image  = $oTD->rcv('image');

/// Запрашиваем модели
$_oApply = $oTD->getTDApplicability($type,$mark,$model,$compl,$tree,$group,$vendor,$detail,$image); ///$oTD->e($_oApply);
/// Проверяем на ошибки
if( ($errors = A2D::property($_oApply,'errors')) ){
    if( $errors->msg == "TD::getTDApplicability::Empty_Result" ){
        $this->auto_render = FALSE;
        die( '<br /><div class="red bold fs20">Информация отсутствуют</div><br />' );
    }
    else $oTD->error( $errors->msg, FALSE, $this->_404_Filter );
}

/// Передаем массив с "хлебными крошками" в конструктор
A2D::$aBreads = A2D::getBreads($_oApply,'breads','td');

/// В каких автомобилях применяется
$oApply = A2D::property($_oApply,'apply',[]); ///$this->e($oImages);
?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/td.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="TDApplicability">
    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <br/>

    <div id="applicability">

        <table class="dataTable">
            <tr>
                <?php /*/?>
                <th>Марка</th>
                <th>Модель</th>
                <th>Л/Г</th>
                <th>Авто</th>
                <?php //*/?>
                <th>Авто</th>
                <th>Период пр-ва</th>
                <th>Мощн. (кВт)</th>
                <th>Мощн. (л.с.)</th>
                <th>Объем куб.см.</th>
                <th>Кузов</th>
                <th>Двигатель/Ось/Тоннаж</th>
            </tr>
            <?php foreach( $oApply AS $a ){?>
                <tr>
                    <?php /*/?>
                <td><?=$a->mfa_brand?></td>
                <td><?=$a->mod_name?></td>
                <td><?=$a->mod_pc_cv?></td>
                <td><?=$a->typ_cds?></td>
                <?php //*/?>
                    <td><?=$a->typ_mmt_cds?></td>
                    <td><?=TD::dateConvert(['date'=>$a->typ_pcon_start])?> - <?=TD::dateConvert(['date'=>$a->typ_pcon_end])?></td>
                    <td><?=$a->typ_kw_from?></td>
                    <td><?=$a->typ_hp_from?></td>
                    <td><?=$a->typ_ccm?></td>
                    <td><?=$a->body_des?></td>
                    <td><?=A2D::property($a,'typ_kv_engine_des','-')?>/<?=A2D::property($a,'typ_kv_axle_des','-')?>/<?=A2D::property($a,'typ_max_weight','-')?></td>
                </tr>
            <?php }?>
        </table>

    </div>

    <br />

</div>