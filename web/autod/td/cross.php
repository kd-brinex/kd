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

/// Запрашиваем кросы
$_oCross = $oTD->getTDCrossover($type,$mark,$model,$compl,$tree,$group,$vendor,$detail,$image); ///$oTD->e($_oCross);
/// Проверяем на ошибки
if( ($errors = A2D::property($_oCross,'errors')) ){
    if( $errors->msg=="TD::getTDCrossover::Empty_Result" ){
        die('<br /><div class="red bold fs20">Кроссы отсутствуют</div><br />');
    }
    else $oTD->error($errors->msg,FALSE,$this->_404_Filter);
}

/// Передаем массив с "хлебными крошками" в конструктор
A2D::$aBreads = A2D::getBreads($_oCross,'breads','td');

/// Наши кросы
$oCross = A2D::property($_oCross,'cross',[]); ///$this->e($oImages);
?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/td.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="TDCrossover">
    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <br/>
    <div id="crossover">

        <table class="marginAuto">
            <tr>
                <th>Марка</th>
                <th>Номер</th>
            </tr>
            <?php foreach( $oCross AS $c ){?>
                <tr>
                    <td><?=$c->bra_brand?></td>
                    <td><?=$c->arl_display_nr?></td>
                </tr>
            <?php }?>
        </table>

    </div>
    <br/>

</div>