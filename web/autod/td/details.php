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
$compl = $oTD->rcv('compl');
$tree  = $oTD->rcv('tree');

/// Запрашиваем модели
$_oDetails = $oTD->getTDDetails($type,$mark,$model,$compl,$tree); ///$oTD->e($_oDetails);
/// Проверяем на ошибки
if( ($errors = A2D::property($_oDetails,'errors')) ) $oTD->error($errors->msg,404);

/// Передаем массив с "хлебными крошками" в конструктор
A2D::$aBreads = A2D::getBreads($_oDetails,'breads','td');


$oDetailsNS = A2D::property($_oDetails,'details',[]); ///$this->e($this->aBreads);
$oDetails = new stdClass;
$i=0; foreach( $oDetailsNS AS $oDetail ){ ++$i;
    $brandID   = $oDetail->brandID;   unset($oDetail->brandID);
    $brandName = $oDetail->brandName; unset($oDetail->brandName);
    $brandLogo = $oDetail->brandLogo; unset($oDetail->brandLogo);
    $oDetails->{$brandID} = ($BID=A2D::property($oDetails,$brandID)) ?$BID: new stdClass;
    $oDetails->{$brandID}->brandName = $brandName;
    $oDetails->{$brandID}->brandLogo = $brandLogo;
    $oDetails->{$brandID}->details[$i] = $oDetail;
} ///$this->e($oDetails);

$modelInfo = A2D::property($_oDetails,'modelInfo',[]);
$fullName = A2D::property($modelInfo,'fullName',[]);

$oGroup = A2D::property($_oDetails,'group',[]);
$goupName = A2D::property($oGroup,'str_des');


$h1 = "$fullName - $goupName";


/// Базовая часть пути для перехода на следующий этап
$url = "/td/detail.php?type={$type}&mark={$mark}&model={$model}&compl={$compl}&tree=$tree";
?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/td.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../media/js/tree.js"></script>



<div id="TDDetails">
    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <h1><?=$h1?></h1>
    <br/>

    <div id="details">

        <?php //*/?>
        <table  class="simpleTable innerTable">
            <tr>
                <th class="brand">Производитель</th>
                <th class="noPad">
                    <table width="100%">
                        <tr>
                            <th class="group">Группа</th>
                            <th class="number">Номер</th>
                        </tr>
                    </table>

                </th>
            </tr>
            <?php foreach( $oDetails as $bid=>$spg ){ ///onlyCellBorder?>
                <tr valign="top">
                    <td class="brand">
                        <img src='<?=$spg->brandLogo?>'>
                        <br/><?=$spg->brandName?>
                    </td>
                    <td class="noPad">
                        <table class="innerTable magic" width="100%">
                            <?php foreach( $spg->details as $d ){
                                $detailID = $d->art_article_nr;
                                $detailName = "$d->ga_des ($spg->brandName)";
                                ?>
                                <tr>
                                    <td align="left" class="group">
                                        <a href='<?=$url."&group=$d->ga_id&vendor=$bid&detail=$d->art_id&image=$d->la_id"?>' title='<?=$d->ga_des?>'>
                                            <?=$d->ga_des?>
                                        </a>
                                    </td>
                                    <td align="left" class="number c2cValue relative">
                                        <?=$detailID?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>

                    </td>
                </tr>
            <?php } ?>
        </table>
        <?php //*/?>

    </div>

</div>