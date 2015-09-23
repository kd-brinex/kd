<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс TD

/// Устанавливаем объект $oTD - объект для работы с неоригинальными каталогами
$oTD = TD::instance();

/// Получаем данные с перехода по ссылке из td/marks.php
$compl = $oTD->rcv('compl');

/// Запрашиваем информацию о модели по выбранной комплектации
$_oModelInfo = $oTD->getTDModelInfoFull($compl); ///$this->e($_oModelInfo);
/// Обработаем ошибки
if( ($aErrors = A2D::property($_oModelInfo,'errors')) ) $oTD->error($aErrors,404);

/// Передаем массив с "хлебными крошками" в конструктор
///Нет крошек, окно рассчитано для попап

/// С сервера вернулось два объекта
$oInfo = A2D::property($_oModelInfo,'info',[]); ///$this->e($this->aBreads);
$oEngines = A2D::property($_oModelInfo,'engines',[]); ///$this->e($this->aBreads);
/// Мы использовали оба

$h1 = "Полная информация по автомобилю";

/// Базовая часть пути для перехода на следующий этап
///Конечная страница, одна из
?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/td.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="TDModelInfo">

    <h1><?=$h1?></h1>
    <br/>

    <div id="complects">

        <table border='1' frame='box' rules='all' style='empty-cells:show;' class="marginAuto">
            <tr><td colspan='2' align='center' bgcolor='#5588ee'><span style='color:white;'>Общий</span></td></tr>
            <tr><td align='center'>Тип</td><td align='center'><?php echo $oInfo->typ_cds; ?></td></tr>
            <tr><td align='center'>Описание</td><td align='center'><?php echo $oInfo->typ_mmt_cds; ?></td></tr>
            <tr>
                <td align='center'>Год выпуска (С - По)</td>
                <td align='center'>
                    <?=TD::dateConvert(array('date'=>$oInfo->typ_pcon_start)).
                    " - ".TD::dateConvert(array('date'=>$oInfo->typ_pcon_end)); ?>
                </td>
            </tr>
            <tr><td colspan='2' align='center' bgcolor='#5588ee'><span style='color:white;'>Конструкция</span></td></tr>
            <tr><td align='center'>Вид конструкции</td><td align='center'><?php echo $oInfo->typ_kv_body_des; ?></td></tr>
            <tr><td align='center'>Вид привода</td><td align='center'><?php echo $oInfo->typ_kv_drive_des; ?></td></tr>
            <tr><td align='center'>Вид сборки</td><td align='center'><?php echo $oInfo->typ_kv_model_des; ?></td></tr>
            <tr><td align='center'>Двери</td><td align='center'><?php echo $oInfo->typ_doors; ?></td></tr>
            <tr><td align='center'>Бак</td><td align='center'><?php echo $oInfo->typ_tank; ?></td></tr>
            <tr><td colspan='2' align='center' bgcolor='#5588ee'><span style='color:white;'>Техническая информация</span></td></tr>
            <tr><td align='center'>Мощн. (кВ)</td><td align='center'><?php echo $oInfo->typ_kw_from; ?></td></tr>
            <tr><td align='center'>Мощн. (ЛС)</td><td align='center'><?php echo $oInfo->typ_hp_from; ?></td></tr>
            <tr><td align='center'>Объем куб.см.</td><td align='center'><?php echo $oInfo->typ_ccm; ?></td></tr>
            <tr><td align='center'>Конфигурация оси</td><td align='center'><?php echo $oInfo->typ_kv_axle_des; ?></td></tr>
            <tr><td align='center'>Тоннаж</td><td align='center'><?php echo $oInfo->typ_max_weight; ?></td></tr>
            <tr><td align='center'>Объем двигателя в литрах</td><td align='center'><?php echo $oInfo->typ_litres; ?></td></tr>
            <tr><td align='center'>Цилиндр</td><td align='center'><?php echo $oInfo->typ_cylinders; ?></td></tr>
            <tr><td align='center'>Напряжение</td><td align='center'><?php echo $oInfo->typ_kv_voltage_des; ?></td></tr>
            <tr><td align='center'>ABS</td><td align='center'><?php echo $oInfo->typ_kv_abs_des; ?></td></tr>
            <tr><td align='center'>ASR</td><td align='center'><?php echo $oInfo->typ_kv_asr_des; ?></td></tr>
            <tr><td align='center'>Вид двигателя</td><td align='center'><?php echo $oInfo->typ_kv_engine_des; ?></td></tr>
            <tr><td align='center'>Вид тормозов</td><td align='center'><?php echo $oInfo->typ_kv_brake_type_des; ?></td></tr>
            <tr><td align='center'>Тормозная система</td><td align='center'><?php echo $oInfo->typ_kv_brake_syst_des; ?></td></tr>
            <tr><td align='center'>Вид горючего</td><td align='center'><?php echo $oInfo->typ_kv_fuel_des; ?></td></tr>
            <tr><td align='center'>Вид катализатора</td><td align='center'><?php echo $oInfo->typ_kv_catalyst_des; ?></td></tr>
            <tr><td align='center'>Привод каробка-передач</td><td align='center'><?php echo $oInfo->typ_kv_trans_des; ?></td></tr>
            <tr><td align='center'>Заправка горючего</td><td align='center'><?php echo $oInfo->typ_kv_fuel_supply_des; ?></td></tr>
            <tr><td align='center'>Количество клапанов на одну камеру сгорания</td><td align='center'><?php echo $oInfo->typ_valves; ?></td></tr>
            <tr><td colspan='2' align='center' bgcolor='#5588ee'><span style='color:white;'>Двигатель</span></td></tr>
            <tr>
                <td align='center' bgcolor='#5588ee'>Двигатель (Описание)</td>
                <td align='center' bgcolor='#5588ee'>Год выпуска (С - По)</td>
            </tr>
            <?php foreach($oEngines as $engine) { ?>
                <tr>
                    <td align='center'><?php echo $engine->eng_code; if(!empty($engine->eng_description)) echo " (".$engine->eng_description.")"; ?></td>
                    <td align='center'>
                        <?=TD::dateConvert(array('date'=>$engine->eng_pcon_start)).
                        " - ".TD::dateConvert(array('date'=>$engine->eng_pcon_end)); ?>
                    </td>
                </tr>
            <?php } ?>
        </table>

    </div>

</div>