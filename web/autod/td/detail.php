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
$_oDetail = $oTD->getTDDetail($type,$mark,$model,$compl,$tree,$group,$vendor,$detail,$image); ///$oTD->e($_oDetail);
/// Проверяем на ошибки
if( ($errors = A2D::property($_oDetail,'errors')) ) $oTD->error($errors->msg,404);

/// Передаем массив с "хлебными крошками" в конструктор
A2D::$aBreads = A2D::getBreads($_oDetail,'breads','td');

/// Подробная нформация о детали
$oDetail = A2D::property($_oDetail,'detail'); ///$this->e($this->aBreads);
/// Изображение детали
$oImages = A2D::property($_oDetail,'images'); ///$this->e($oImages);

$detailName = $oDetail->art_complete_des.( ($a=$oDetail->art_des)?" $a":"").( ($b=$oDetail->sup_brand)?" ($b)":"");

$h1 = "Detail Information";

/// Кроссы [crossover]
$cross = "/td/cross.php?type={$type}&mark={$mark}&model={$model}&compl={$compl}&tree=$tree&group=$group&vendor=$vendor&detail=$detail&image=$image";
/// Применяемость [applicability]
$apply = "/td/apply.php?type={$type}&mark={$mark}&model={$model}&compl={$compl}&tree=$tree&group=$group&vendor=$vendor&detail=$detail&image=$image";
?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/td.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../media/js/tree.js"></script>



<div id="TDDetail">
    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <h1><?=$h1?></h1>
    <br/>

    <div id="detail">

        <table border='1' frame='box' rules='all' style='empty-cells:show;' class="marginAuto">
            <tr valign="top">
                <td>
                    <h2><u>Article Information</u></h2>
                    <div class="divlink" style="border:0;">
                        <img src='<?=$oDetail->logo?>'>
                    </div>
                    <div class="divlink" style="border:0;">
                        <b><?=$oDetail->sup_brand?></b>
                        <br/><?=$oDetail->art_article_nr." ".$oDetail->art_description?>
                    </div>
                    <div style="clear:both;"></div>

                    <!-- General -->
                    <table border='1' frame='box' rules='all'  style='empty-cells:show;'>
                        <tr bgcolor="Gainsboro"><td colspan='2'>Общие данные</td></tr>
                        <tr><td><b>Номер EAN</b></td><td><?=$oDetail->ean_ean?></td></tr>
                        <tr><td><b>Статус</b></td><td><?=$oDetail->acs_kv_status_des?></td></tr>
                        <tr><td><b>Упаковачная единица</b></td><td><?=$oDetail->acs_pack_unit?></td></tr>
                        <tr><td><b>Количество в упаковке</b></td><td><?=$oDetail->acs_quantity_per_unit?></td></tr>
                        <tr>
                            <td><b>Номер пользователя</b></td>
                            <td>
                                <ul>
                                    <?php foreach ($oDetail->trade_numbers as $info){ ?>
                                        <li><?=$info->arl_display_nr?></li>
                                    <?php } ?>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Запасной номер</b></td>
                            <td>
                                <ul>
                                    <?php foreach ($oDetail->supersedes as $info){ ?>
                                        <li><?=$info->sua_number?></li>
                                    <?php } ?>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Заменен на</b></td>
                            <td>
                                <ul>
                                    <?php foreach ($oDetail->supersedes_by as $info){ ?>
                                        <li><?=$info->art_article_nr?></li>
                                    <?php } ?>
                                </ul>
                            </td>
                        </tr>
                        <tr><td><b>Сменная деталь</b></td><td><?=(!empty($oDetail->art_replacement) ? "+ Да" : "- Нет")?></td></tr>
                        <tr><td><b>Для самостоятельного применения</b></td><td><?=(!empty($oDetail->art_pack_selfservice) ? "+ Да" : "- Нет")?></td></tr>
                        <tr><td><b>Принадлежности</b></td><td><?=(!empty($oDetail->art_accessory) ? "+ Да" : "- Нет")?></td></tr>
                        <tr><td><b>Требует обязательного обозначения</b></td><td><?=(!empty($oDetail->art_material_mark) ? "+ Да" : "- Нет")?></td></tr>
                        <tr bgcolor="Gainsboro"><td colspan='2'>Критерии</td></tr>
                        <?php foreach ($oDetail->criteria as $info){ ?>
                            <tr>
                                <td><b><?=$info->cri_des_local?></b></td>
                                <td><?=$info->crit_value?></td>
                            </tr>
                        <?php } ?>
                        <tr bgcolor="Gainsboro"><td colspan='2'>Информация</td></tr>
                        <?php foreach ($oDetail->info as $info_gr){ ?>
                            <tr>
                                <td><b><?=$info_gr->kv_type_des?></b></td>
                                <td>
                                    <ul>
                                        <?php foreach ($info_gr->info as $info){ ?>
                                            <li><?=$info->tmt_text?></li>
                                        <?php } ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </td>
                <!-- 2 окно -->
                <td>
                    <div>
                        <?php if( $oImages ) foreach( $oImages AS $img){?>
                            <img src="<?=$img->url?>" alt="<?=$img->alt?>" />
                        <?php }else{?>
                            Изображение отсутствует
                        <?php }?>
                    </div>
                </td>
            </tr>
        </table>

    </div>
    <br />
    <br />
    <div class="center">
        <a href="<?=$cross?>" target="_blank">Кроссы</a>
    </div>
    <div class="center">
        <a href="<?=$apply?>" target="_blank">Применяемость</a>
    </div>

</div>