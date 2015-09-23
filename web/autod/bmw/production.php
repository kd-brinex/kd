<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс BMW

/// Устанавливаем объект $oBMW - объект для работы с оригинальным каталогом BMW
$oBMW = BMW::instance();

/// Получаем данные с перехода по ссылке из bmw/options.php
$mark   = $oBMW->rcv('mark');
$type   = $oBMW->rcv('type');
$series = $oBMW->rcv('series');
$body   = $oBMW->rcv('body');
$model  = $oBMW->rcv('model');
$market = $oBMW->rcv('market');
$rule   = $oBMW->rcv('rule');
$trans  = $oBMW->rcv('transmission');

/// Получаем период производства на основе выбранных параметров ранее
$BMWProduction = $oBMW->getBMWProduction($type,$series,$body,$model,$market,$rule,$trans); ///$oBMW->e($BMWProduction);
/// Проверяем на ошибки
if( ($aErrors = A2D::property($BMWProduction,'errors')) ) $oBMW->error($aErrors,404);
/// С "хлебными крошками" никто не работает, можно сразу передать в объект для конструктора
A2D::$aBreads = A2D::property($BMWProduction,'aBreads',[]);

/**
 * Данные отдаются ввиде массива/объекта как есть из базы от производителя
 * Мы решили из полученных данных собрать свой массив
 * На основе массива подаем данные, как мы выдим их визульное представление
*/
$aData = A2D::property($BMWProduction,'aData',[]);
$aData = current($aData);
$aData = [
    "DateStart"  => $aData->DateStart,
    "DateEnd"    => $aData->DateEnd,
    "startYear"  => substr($aData->DateStart,0,4),
    "startMonth" => substr($aData->DateStart,4,2),
    "startDay"   => substr($aData->DateStart,6,2),
    "endYear"    => substr($aData->DateEnd,0,4),
    "endMonth"   => substr($aData->DateEnd,4,2),
    "endDay"     => substr($aData->DateEnd,6,2),
];

/// Базовая часть пути для переходя на следующий этап
$url = BASE_PATH."/bmw/groups.php?mark={$mark}&type={$type}&series={$series}&body={$body}&model={$model}&market={$market}&rule={$rule}&transmission={$trans}";

?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/bmw.css" media="all" rel="stylesheet" type="text/css">



<div id="BMWProduction" class="AutoDealer">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <div>

        <h1 class="mb0">Выберите дату производства</h1>

        <table class="dataTable">
            <?php
            $startYear  = $aData['startYear'];
            $startMonth = $aData['startMonth'];
            $startDay   = sprintf("%02d",$aData['startDay']);
            $endYear    = $aData['endYear'];
            $endMonth   = $aData['endMonth'];
            $endDay     = sprintf("%02d",$aData['endDay']);

            $a = [
                "startYear"  => $aData['startYear'],
                "endYear"    => $aData['endYear'],
                "startMohth" => $aData['startYear'],
                "endMonth"   => $aData['endYear'],
            ];
            //print'<pre>';print_r($a);print'</pre>';//exit;

            for( $y = $startYear; $y <= $endYear; $y++ ){?>
                <tr>
                    <td class="yearsTD"><?=$y?></td>
                    <?php for( $m = 1; $m <= 12; $m++ ){?>
                        <td class="pd2">

                            <?php
                            if( $y==$startYear && $m==$startMonth ){
                                $d = $startDay;
                            }
                            elseif( $y==$endYear && $m==$endMonth ){
                                $d = $endDay;
                            }
                            else{
                                $d = "00";
                            }
                            ?>

                            <?php if( ( $y==$startYear && $m<$startMonth ) || ( $y==$endYear && $m>$endMonth) ){?>
                                &emsp;
                            <?php }else{?>
                                <a class="bttnGB anime" href="<?=$url?>&production=<?=$y.sprintf("%02d",$m).$d?>"><?=sprintf("%02d",$m)?></a>
                            <?php }?>

                        </td>
                    <?php }?>
                </tr>
            <?php }?>
        </table>

        <a class="bttnGB anime inlineBlock mt20" href="<?=$url?>&production=any" class="anyYear">Не важно</a>
    </div>

</div>