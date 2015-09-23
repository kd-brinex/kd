<?php  header('Access-Control-Allow-Origin: *');

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс ADC

/// Устанавливаем объект $oA2D - объект для работы с АвтоКаталогом
$oA2D = ADC::instance();

/// Получаем переменные из своего окружения
$sTreeID  = $oA2D->rcv('tree');
$sModelID = $oA2D->rcv('model');

/// Получаем информацию о модели
$aInfo = $oA2D->getDetailInfo($sModelID,$sTreeID); ///$oA2D->e($aInfo);

/// Проверим на ошибки
if( ($aErrors=A2D::property($aInfo,'errors')) ) $oA2D->error($aErrors,404);

/// Тут "хлебные крошки" не нужны, так как используем в Ajax запросе со страницы иллюстрации adc/map.php
?>



<meta charset="utf-8">
<TITLE><?=A2D::lang('title')?></TITLE>
<style type="text/css">
BODY, TABLE, TD {font-family: Tahoma; font-size: 8pt;}
A {color: #555555; text-decoration: none;}
A:HOVER { text-decoration: underline;}
a.close{font-size:20px;}
a.close:hover{text-decoration:none;cursor:pointer;color:black;}
TABLE.BRD {border: #DDDDDD 1px Solid; background: #FFFFFF;}
TD.LBRD {width: 150px; border: #DDDDDD 1px Solid; background: #F5F5F5; font-size: 8pt;}
TD.RBRD {background: #FAFAFA; font-size: 8pt;}
H2 {width: 100%; height: 26px; background: #EEEEEE; font-size: 14pt; color: #999999; font-weight: bold; text-align: center; margin-bottom: 0px;}
</style>

</HEAD>

<BODY oncontextmenu="return false;" topmargin="2" bottommargin="2" leftmargin="2" rightmargin="2" bgcolor="#F5F5F5">



<TABLE cellpadding="2" width="100%" align="center" class="brd">
    <TR><TD colspan="2"><H1><?=A2D::lang('h1')?></H1></TD></TR>
    <TR><TD class="lbrd"><B><?=A2D::lang('name')?></B></TD><TD class="rbrd"> <?=$aInfo->auto?></TD></TR>
    <?php if ($aInfo->modification) {?>
    <TR><TD class="lbrd"><B><?=A2D::lang('mod')?></B></TD><TD class="rbrd"> <?=$aInfo->modification?></TD></TR>
    <?php }?>
    <?php if ($aInfo->actual) {?>
    <TR><TD class="lbrd"><B><?=A2D::lang('act')?></B></TD><TD class="rbrd"> <?=$aInfo->actual?></TD></TR>
    <?php }?>
    <TR><TD class="lbrd"><B><?=A2D::lang('dname')?></B></TD><TD class="rbrd"> <?=$aInfo->detail_name?></TD></TR>
    <TR><TD class="lbrd"><B><?=A2D::lang('dnum')?></B></TD><TD class="rbrd"> <?=$aInfo->detail_no?></TD></TR>
    
    <TR><TD colspan="2" class="lbrd"><B><?=A2D::lang('count')?>:</B></TD></TR>
    <?php foreach( $aInfo->count AS $v ){?>
        <TR><TD colspan="2"><?=$v?></TD></TR>
    <?php }?>
</TABLE>
    
<CENTER><br><A href=# onclick="top.fc();" class="close"><b><?=A2D::lang('close')?></b></A><BR><BR></CENTER>

</BODY>

</HTML>