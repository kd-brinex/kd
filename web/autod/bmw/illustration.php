<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс BMW

/// Устанавливаем объект $oBMW - объект для работы с оригинальным каталогом BMW
$oBMW = BMW::instance();

/// Получаем данные с перехода по ссылке из bmw/subgroups.php
$mark    = $oBMW->rcv('mark');
$type    = $oBMW->rcv('type');
$series  = $oBMW->rcv('series');
$body    = $oBMW->rcv('body');
$model   = $oBMW->rcv('model');
$market  = $oBMW->rcv('market');
$rule    = $oBMW->rcv('rule');
$trans   = $oBMW->rcv('transmission');
$prod    = $oBMW->rcv('production');
$group   = $oBMW->rcv('group');
$graphic = $oBMW->rcv('graphic');

/// Получаем необходимые данные для построения страницы. Второй строкой останавливаемся при ошибках с сервера
$BMWIllustration = $oBMW->getBMWDetailsMap($type,$series,$body,$model,$market,$rule,$trans,$prod,$group,$graphic,"ru"); ///$oBMW->e($BMWIllustration);
/// Проверяем на ошибки
if( ($aErrors = A2D::property($BMWIllustration,'errors')) ) $oBMW->error($aErrors,404);

/// Получаем "хлебные крошки" из общего объекта, что вернул сервер
A2D::$aBreads = A2D::property($BMWIllustration,'aBreads',[]); ///$oBMW->e($aBreads);

/// Получаем метки для изображения, связанные со списком номенклатуры
$aLabels   = A2D::property($BMWIllustration,'labels',[]);

/// Получаем список номенклатуры из общего объекта, что вернул сервер
$aDetails  = A2D::property($BMWIllustration,'details',[]);

/// В каталоге BMW доступны некие комментарии для таблицы номенклатуры
$aComments = A2D::property($BMWIllustration,'comments',[]);

/// Получаем данные для построение иллюстрации из общего объекта, что вернул сервер:
$imgInfo = A2D::property($BMWIllustration,'imgInfo'); /// Объект:
$iSID    = A2D::property($imgInfo,'iSID');        /// Ключ, нужен для построение картинки
$imgUrl  = A2D::property($imgInfo,'url');         /// Адрес иллюстрации на сервере
$width   = A2D::property($imgInfo,'width');       /// Ширина изображения
$height  = A2D::property($imgInfo,'height');      /// Высота изображения
$attrs   = A2D::property($imgInfo,'attrs');       /// Те же данные одним атрибутом
$percent = A2D::property($imgInfo,'percent')/100; /// Коэффициент в каком соотношение вернулась иллюстрация, нужно для ограничения показов с одного агента на IP
$limit   = A2D::property($imgInfo,'limit');       /// Ваше число ограничений для отображения пользователю, у которого сработало ограничение


/// Корневой элемент для зума
$rootZoom = "imageLayout";

/// Базовая часть пути для перехода на следующий этап
$url = BASE_PATH."/bmw/illustration.php?mark={$mark}&type={$type}&series={$series}&body={$body}&model={$model}&market={$market}&rule={$rule}&transmission={$trans}&production={$prod}&group={$group}";

?>

<meta charset="utf-8">
<link href="../media/css/bootstrap.min.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/bmw.css" media="all" rel="stylesheet" type="text/css">



<?php include WWW_ROOT."helpers/illustration.php"; /// Подключаем функции для иллюстрации?>
<div id="detailsMap" class="AutoDealer">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <div>

        <?php $px = 5; $py = 1; ///(оступы по умолчанию внутри метки на иллюстрации)?>

        <div class="defBorder imgArea mb30" id="imageArea">
            <?php if( $percent<1 ){?>
                <div class="isLimit">Превышен лимит показов в сутки (<?=$limit?>)</div>
            <?php }?>
            <div id="imageLayout" style="position:absolute;left:0;top:0;width:<?=$width?>px;height:<?=$height?>px">
                <canvas id="canvas" <?=$attrs?> style="margin:0;padding:0;"></canvas>
                <?php //*/
                $prevNamber = FALSE;
                foreach( $aLabels AS $_v ){

                    $title   = $_v->Name;
                    $lLeft   = $_v->TopLeft_x*$percent - $px*$percent;
                    $lTop    = $_v->TopLeft_y*$percent - $py*$percent;
                    $lWidth  = ( $_v->BottomRight_x*$percent - $lLeft ) + $px*$percent*2;
                    $lHeight = ( $_v->BottomRight_y*$percent - $lTop ) + $py*$percent*2;

                    $currNumber = $_v->Bildnummer;
                    $number = ( $currNumber==$prevNamber ) ?$currNumber :$currNumber;
                    $prevNamber = $currNumber;
                    ?>
                    <div id="l<?=$number?>" class="l<?=$number?> mapLabel" title="<?=$title?>"
                         style="
                             position:absolute;
                             left:<?=$lLeft?>px;
                             top:<?=$lTop?>px;
                             min-width:<?=$lWidth?>px;
                             min-height:<?=$lHeight?>px;
                             padding:<?=$py?>px <?=$px?>px;
                             "
                         onclick="labelClick(this,false)"
                         ondblclick="labelClick(this,true)"
                        >
                        <?//=$_v->number?>
                    </div>
                <?php } //*/?>
            </div>
            <?php include WWW_ROOT."helpers/zoomer.php"; /// Подключаем функцию панели с зумером?>
        </div>

        <div id="detailsList">
            <table class="simpleTable innerTable">
                <thead>
                <tr>
                    <th class="BMWDetailPosition">№</th>
                    <th class="BMWDetailNumber">Номер детали</th>
                    <th class="BMWDetailOptions">Дополнительно</th>
                    <th class="BMWDetailQuantity">Кол-во</th>
                    <!--<th class="BMWDetailQuantity"></th>-->
                    <th class="BMWDetailProduction">Производство</th>
                    <th>Наименование</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $nc = [];
                $countDetails = count($aDetails)-1;
                for( $i=0; $i<=$countDetails ; ++$i ){ $_v = $aDetails[$i];

                    $currNumber = $_v->Number;

                    $detailID = ( $percent==1 ) ?$_v->DetailID :"*******";

                    $prevBlock = ( $i>1 ) ?$aDetails[($i-1)]->BlockNr :$_v->BlockNr-1;
                    $currBlock = $_v->BlockNr;
                    $nextBlock = ( $i<$countDetails ) ?$aDetails[($i+1)]->BlockNr :$_v->BlockNr+1;

                    ?>

                    <?php if( $currBlock>$prevBlock && ( $preComm = A2D::property($aComments,$_v->KommVor) ) ){?>
                        <tr style="background:lightgreen">
                            <td colspan="6">
                                <?php foreach( $preComm AS $comm ) echo $comm->Text." ";?>
                            </td>
                        </tr>
                    <?php }?>
                    <tr id="d<?=$currNumber?>" data-position="<?=$currNumber?>" class="none anime pointer" onclick = "trClick(this,0)" ondblclick = "trClick(this,1)">
                        <td class="BMWDetailPosition"><?=$_v->Number?></td>
                        <td class="BMWDetailNumber c2cValue" id="c2cValue_<?=$currNumber?>">
                            <?=A2D::callBackLink($detailID,A2D::$callback)?>
                            &ensp;<img title="Скопировать" id="c2cBttn_<?=$currNumber?>" src="/media/images/copy_20x20.png">
                        </td>
                        <td class="BMWDetailOptions"><?=$_v->Options?></td>
                        <td class="BMWDetailQuantity"><?=$_v->Quantity?></td>
                        <td class="BMWDetailProduction"><?=( $_v->End ) ?date('d.m.Y',strtotime($_v->End)) :"-"?></td>
                        <td class="tl"><?=$_v->Name?></td>
                    </tr>
                    <?php if( $currBlock<$nextBlock && ( $postComm = A2D::property($aComments,$_v->KommNach) ) ){//die('after')?>
                        <tr style="background: lightcoral">
                            <td colspan="6">
                                <?php foreach( $postComm AS $comm ) echo $comm->Text." ";?>
                            </td>
                        </tr>
                    <?php }?>
                <?php }?>

                </tbody>
            </table>
        </div>

    </div>

</div>