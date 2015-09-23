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

/// Запрашиваем модели
$_oTree = $oTD->getTDTree($type,$mark,$model,$compl); ///$oTD->e($_oTree);
/// Проверяем на ошибки
if( ($errors = A2D::property($_oTree,'errors')) ) $oTD->error($errors->msg,404);

/// Передаем массив с "хлебными крошками" в конструктор
A2D::$aBreads = A2D::getBreads($_oTree,'breads','td');

$aTreelList = A2D::property($_oTree,'tree',[]); ///$oTD->e($oTD->aBreads);

$modelInfo = A2D::property($_oTree,'modelInfo',[]);
$fullName = A2D::property($modelInfo,'fullName',[]);

$h1 = "Сборочные группы для $fullName";
$url = "/aftermarket/$type/$mark/$model/$compl/";


$aObj = [];
$root = current($aTreelList)->str_id_parent;
$rootGroups = []; /// Есть ссылки в корне группы, им отдельный стиль
///$motoRoot = 13771; /// Это удалить надо
$dom = new DOMDocument();

$ul  = $dom->appendChild( new DOMElement( "ul" ) );
$ul->setAttribute( "class", "my_tree" );
$ul->setAttribute( "id", "l1" );
$aObj[ $root ] = $ul; /// Если высплывет что то типа: Undefined offset: 3112884, значит $root определен не верно
//while( $f = mysql_fetch_A2Day( $r ) ){

/// Базовая часть пути для перехода на следующий этап
$url = "/td/details.php?type={$type}&mark={$mark}&model={$model}&compl={$compl}";
foreach( $aTreelList AS $f ){ ///$oTD->e($aTreelList);

    $dID = $f->str_id;
    $pID = $f->str_id_parent;///
    $txt = $f->str_des;
    $chl = $f->str_child_exist;
    $nxt = "$url&tree=$f->str_id";

    ///if( $pID==0 ) return;
    /// Ссылка на группу
    //if( !$chld ){

    //}

    if( $pID==$root ){
        $rootGroups += [$dID=>$dID];
        $_lid = "glav$dID";
        $_lcl = "obert4";
        $_uid = "plash_li$dID";
        $_ucl = "plashka2 anime";
        $_sid = "sp$dID";
        $_scl = "sppl plashka anime";
        $_soc = "expand(this)";
    }
    elseif( $chl != 0 ){
        $_lid = "ch$dID";
        $_lcl = "two";
        $_uid = "li$dID";
        $_ucl = "plashka2 anime";
        $_sid = "span$dID";
        $_scl = "plashka2 anime";
        $_soc = "expandLi(this)";
    }
    elseif( in_array($pID,$rootGroups) ){
        $_lid = "nch$dID";
        $_lcl = "rootLink";
        $_uid = "";
        $_ucl = "";
        $_sid = "detal$dID";
        $_scl = "fir";
        $_soc = "";
    }
    else{
        $_lid = "nch$dID";
        $_lcl = "three";
        $_uid = "";
        $_ucl = "";
        $_sid = "detal$dID";
        $_scl = "fir";
        $_soc = "";
    }

    $ul = $aObj[ $pID ]; /// Если высплывет что то типа: Undefined offset: 3112884, значит сперва не парент0
    $li = $ul->appendChild( new DOMElement( "li" ) );
    $li->setAttribute( "id", $_lid );
    $li->setAttribute( "class", $_lcl );
    if( $pID==$root ){
        $div = $li->appendChild( new DOMElement( "div" ) );
        $div->setAttribute( "id", "plash_plus$dID" );
        $div->setAttribute( "class", "plus_tree anime" );
        $div->setAttribute( "onclick", "expand(this)" );
        $div->appendChild( new DOMText( "+" ) );
    }

    $span = $li->appendChild( new DOMElement( "span" ) );
    $span->setAttribute( "id", $_sid );
    $span->setAttribute( "class", $_scl );

    if( $chl != 0 ){

        $span->setAttribute( "onclick", $_soc );
        $span->appendChild( new DOMText( $txt ) );

        $ul = $li->appendChild( new DOMElement( "ul" ) );
        $ul->setAttribute( "id", $_uid );
        $ul->setAttribute( "class", $_ucl );
        $ul->setAttribute( "style", "display:none;" );
        $aObj[ $dID ] = $ul;
    }
    else{
        $a = $span->appendChild( new DOMElement( "a" ) );
        $a->appendChild( new DOMText( $txt ) );
        $a->setAttribute( "href", $nxt );
    }
}
$vTree = $dom->saveHTML();

?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/td.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../media/js/tree.js"></script>



<div id="TDTree">
    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <h1><?=$h1?></h1>
    <br/>

    <div id="modelInfo" class="mb20">
        <table>
            <tr>
                <td class="bold tl pr20">Производитель</td>
                <td align="left"><?=$modelInfo->markName?></td>
            </tr>
            <tr>
                <td class="bold tl pr20">Модель</td>
                <td align="left"><?=$modelInfo->modelName?></td>
            </tr>
            <tr>
                <td class="bold tl pr20">Автомобиль</td>
                <td align="left">
                    <?=$modelInfo->fullName?> [<?=TD::dateConvert(['date'=>$modelInfo->prodStart])?>-<?=TD::dateConvert(['date'=>$modelInfo->prodEnd])?>],
                    <?=$modelInfo->kw?> кВт, <?=$modelInfo->hp?> л.с., <?=$modelInfo->ccm?> кум.см., <?=$modelInfo->body?>
                </td>
            </tr>
        </table>
    </div>

    <div id="tree">
        <?=$vTree?>
    </div>

</div>