<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс TOY

/// Устанавливаем объект $oTOY - объект для работы с каталогом Toyota
$oTOY = TOY::instance();

/// Получаем данные с перехода по ссылке из toyota/options.php
$mark   = $oTOY->rcv('mark');
$market = $oTOY->rcv('market');
$model  = $oTOY->rcv('model');
$compl  = $oTOY->rcv('compl');
$opt    = $oTOY->rcv('opt');
$code   = $oTOY->rcv('code');

/// Вспомогательные данные
$vin    = $oTOY->rcv('vin');
$vdate  = $oTOY->rcv('vdate');
$siyopt = $oTOY->rcv('siyopt');
/// При наличие строим дополнительную строку запроса
$getString = ""
    .(( $vin )    ?"&vin=$vin"      :"")
    .(( $vdate )  ?"&vdate=$vdate"  :"")
    .(( $siyopt ) ?"&siyopt=$siyopt" :"")
;

/// Вернет объект: список деталей с узлами, информацию о выбранной модели и "хлебные крошки"
$TOYGroups = $oTOY->getToyModCompl($market,$model,$compl,$opt,$code,$vin,$vdate,$siyopt); ///$oTOY->e($TOYGroups);
/// Останавливаемся при ошибках с сервера
if( ($aErrors = A2D::property($TOYGroups,'errors')) ) $oTOY->error($aErrors,404);

/// С крошками никто не работает можно сразу передать в объект для конструктора
A2D::$aBreads = A2D::property($TOYGroups,'aBreads',[]); ///$oTOY->e($aBreads);

/// Получаем информацию о модели из общего объекта, что вернул сервер
$aModel = A2D::property($TOYGroups,'aModel'); //$this->e($aModel);
/// Получаем детали с узлами для модели из общего объекта, что вернул сервер
$aCompl = A2D::property($TOYGroups,'aCompl');

/// Формируем заголовок H1 для страницы, используя данные из "хлебных крошек" и название марки
$markName  = ucfirst($mark);
$modelName = $aModel->name;
$complName = $aModel->compl;
$h1 = "$markName $modelName - комплектация $complName";

/// Базовая часть пути для переходя на следующий этап
$url = BASE_PATH."/toyota/illustration.php?mark={$mark}&market={$market}&model={$model}&compl={$compl}&opt={$opt}&code={$code}";

?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/toyota.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../media/js/jquery-1.11.1.min.js"></script>



<div id="ToyCompl">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <h1><?=$h1?></h1>

    <span class="cBlue">Информация об автомобиле</span>

    <table id="modelInfo">

        <?php if( $aModel->name || $aModel->modifs ){?>
            <tr>
                <th>Модель</th>
                <td><?=$aModel->name?> <?=$aModel->modifs?></td>
            </tr>
        <?php }?>

        <?php if( $aModel->compl ){?>
            <tr><th>Код модели</th><td><?=$aModel->compl?></td></tr>
        <?php }?>

        <?php if( $aModel->engine ){?>
            <tr><th>Двигатель</th><td><?=$aModel->engine?></td></tr>
        <?php }?>

        <?php if( $aModel->kpp ){?>
            <tr><th>КПП</th><td><?=$aModel->kpp?></td></tr>
        <?php }?>

        <?php if( $aModel->body ){?>
            <tr><th>Кузов</th><td><?=$aModel->body?></td></tr>
        <?php }?>

        <?php if( $aModel->grade ){?>
            <tr><th>Класс</th><td><?=$aModel->grade?></td></tr>
        <?php }?>

        <?php if( $aModel->prod ){?>
            <tr><th>Период выпуска</th><td><?=$aModel->prod?></td></tr>
        <?php }?>

        <?php if($aModel->other){?>
            <tr>
                <th>Другое</th>
                <td><?=str_replace(',',"<br/>",$aModel->other)?></td>
            </tr>
        <?php }?>

    </table>
    <?php //print'<pre>';print_r( $aModel );print'</pre>';?>

    <?php $tableTH = array(
        1 => "Двигатель, топливная система и инструменты",
        2 => "Трансмиссия и шасси",
        3 => "Кузов и салон",
        4 => "Электрика"
    );?>
    <?php foreach( $aCompl AS $k=>$aItems ){?>
        <div class="treeBranch">
            <div id="plusBranch<?=$k?>" class="plusBranch anime" onclick="branchToggle('plusBranch<?=$k?>','itemsBranch<?=$k?>')">+</div>
            <div class="itemsBranchL">
                <div class="headBranch anime" onclick="branchToggle('plusBranch<?=$k?>','itemsBranch<?=$k?>')"><?=$tableTH[$k]?></div>
                <div id="itemsBranch<?=$k?>" class="itemsBranch" style="display:none">
                    <?php foreach( $aItems AS $aItem ){?>
                        <div class="itemBranch">
                            <a href="<?=$url?>&group=<?=$aItem->part_group?>&graphic=<?=$aItem->pic_code?><?=$getString?>">
                                <img src="<?=$aItem->imgUrl?>" class="anime">
                                <span class="itemDesc" class="anime"><?=$aItem->desc_en?></span>
                            </a>
                        </div>
                    <?php }?>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    <?php }?>
    <script>
        function branchToggle(plusBranch,itemsBranch){
            var $plusBranch  = $('#'+plusBranch),
                $itemsBranch = $('#'+itemsBranch);
            if( $plusBranch.html()!='+' ){
                $plusBranch.html('+');
                $itemsBranch.slideUp(700);
            }
            else{
                $plusBranch.html('&ndash;');
                $itemsBranch.slideDown(700);
            }
        }
    </script>

</div>