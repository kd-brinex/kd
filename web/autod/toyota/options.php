<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс TOY

/// Устанавливаем объект $oTOY - объект для работы с каталогом Toyota
$oTOY = TOY::instance();

/// Получаем данные с перехода по ссылке из toyota/models.php
$mark   = $oTOY->rcv('mark');
$market = $oTOY->rcv('market');
$model  = $oTOY->rcv('model');

/// Вернет объект: опции, расшифровка сокращений и "хлебные крошки"
$TOYOptions = $oTOY->getToyModiff($market,$model); ///$oTOY->e($TOYOptions);
/// Останавливаемся при ошибках с сервера
if( ($aErrors = A2D::property($TOYOptions,'errors')) ) $oTOY->error($aErrors,404);

/// Получаем крошки сперва в простую переменную, пригодится для работы
$aBreads = A2D::property($TOYOptions,'aBreads',[]); ///$oTOY->e($aBreads);

/// Получаем доступные опции из общего объекта, что вернул сервер
$aModifs = A2D::property($TOYOptions,'aModif');

/// Формируем заголовок H1 для страницы, используя данные из "хлебных крошек"
$sMarket = $aBreads->models->name;
$sModel  = $aBreads->options->name;
$sMark   = ucfirst($mark);
$h1 = "Запчасти для $sMark $sModel, список комплектаций ($sMarket)";

/// После работы с переменной/крошками, передаем ее в объект для конструктора
A2D::$aBreads = $aBreads;

/// Применяем магию для красивой подачи расшифровок сокращений в комплектации
$aMagic = [
    1 => "Двигатель",
    2 => "Кузов",
    3 => "Класс",
    4 => "КПП",
    5 => "Другое"
];
$aList = []; /// Начинаем формировать свой список
/// Собственно первоначальный список расшифровок. Забираем из общего объекта, что вернул сервер
$_list = A2D::property($TOYOptions,'info'); ///$e($_list);
$i =0; foreach( $_list AS $l ){ ++$i;
    if( $l->type==1 OR $l->type==2)      $k = 1;
    elseif( $l->type==3 )                $k = 2;
    elseif( $l->type==4 )                $k = 3;
    elseif( $l->type==5 OR $l->type==6 ) $k = 4;
    else                                 $k = 5;
    $aList[$aMagic[$k]][$i]['sign'] = $l->sign;
    $aList[$aMagic[$k]][$i]['desc'] = $l->desc_en;
}

/// Базовая часть пути для переходя на следующий этап
$url = BASE_PATH."/toyota/groups.php?mark={$mark}&market={$market}&model={$model}";
?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/toyota.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../media/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../media/js/dataTable.js"></script>



<div id="ToyModifs">

    <?php include WWW_ROOT."helpers/breads.php"; /// Продключаем хлебные крошки?>

    <h1><?=$h1?></h1>

    <table id="dataTable" class="dataTable">
        <thead>
        <tr>
            <th>Комплектация</th>
            <th>Производство</th>
            <th>Двигатель</th>
            <th>Кузов</th>
            <th>Класс</th>
            <th>КПП</th>
            <th>Другое</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach( $aModifs AS $aModif ){ $_url = $url."&compl={$aModif->compl}&opt={$aModif->sysopt}&code={$aModif->code}"?>
            <tr onclick="window.location.href ='<?=$_url?>'">
                <td><a href="<?=$_url?>"><?=$aModif->compl?></a></td>
                <td class="tl"><?=$aModif->prod?></td>
                <td><?=$aModif->engine?></td>
                <td><?=$aModif->body?></td>
                <td><?=$aModif->grade?></td>
                <td><?=$aModif->kpp?></td>
                <td><?=$aModif->other?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>


    <div class="explanation">
        <span class="cBlue">Расшифровка сокращений</span>
        <div class="expTable">
            <?php foreach( $aList AS $n=>$s ){?>
                <div class="eTableHead"><?=$n?></div>
                <div class="eTableBody">
                <?php foreach( $s AS $a ){?>
                    <span class="sign"><?=$a['sign']?></span> = <span class="desc"><?=$a['desc']?></span><br/>
                <?php }?>
                </div>
            <?php }?>
        </div>
    </div>


</div>