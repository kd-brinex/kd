<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс ETKA

/// Устанавливаем объект $oETKA - объект для работы с оригинальным каталогом ETKA
$oETKA = ETKA::instance();

/// Получаме рефер ссылку, чтобы пользователя можно было вернуть на предыдущую страницу
$refer = A2D::get($_SERVER,'HTTP_REFERER');

/// Получаем данные с перехода по ссылке из etka/search/vin.php
$vin  = $oETKA->rcv('vin');
$vkbz = $oETKA->rcv('vkbz');


$oETKAVinInfo = $oETKA->getETKAVinInfo($vin,$vkbz); ///$oETKA->e([$vin,$vkbz,$oETKAVinInfo]);
if( ($errors = A2D::property($oETKAVinInfo,'errors')) ) $oETKA->error($errors,404);

///
$vinInfo = A2D::property($oETKAVinInfo,'vinInfo',[]);
$oModels = A2D::property($vinInfo,'models',[]);
$oModel  = A2D::property($vinInfo,'model',[]);
$oMKB    = A2D::property($vinInfo,'mkb',[]);
$oGKB    = A2D::property($vinInfo,'gkb',[]);
$oPRNs   = A2D::property($vinInfo,'pr',[]);

///
A2D::$bLogo = FALSE;
A2D::$aBreads = A2D::toObj([
    0 => [
        "name" => 'Каталог',
        "breads" => [ 0 => 'catalog' ]
    ],
    'refer' => [
        "url" => $refer,
        "txt" => 'Вернуться к результатам поиска',
    ]
]);

?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/etka.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="searchETKAVIN">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <?php include WWW_ROOT."helpers/search.php"; /// Подключаем форму поиска?>

    <div class="columns2 explanation">
        <div class="expTable br6 overflowHidden">
            <div class="eTableHead">Основные характеристики</div>
            <div class="eTableBody">
                <?php
                $gearboxCode = preg_replace("#(\\*+)#","<span class=\"red ml5\">$1</span>",$oModel->gearboxCode);
                $driveAxles  = preg_replace("#(\\*+)#","<span class=\"red ml5\">$1</span>",$oModel->driveAxles);
                ?>
                <span class="sign">Марка</span> : <span class="desc"><?=$oModel->markName?></span><br/>
                <span class="sign">Каталог</span> : <span class="desc"><?=$oModel->catalog?></span><br/>
                <span class="sign">Производство</span> : <span class="desc"><?=$oModel->production?></span><br/>
                <span class="sign">Модельный год</span> : <span class="desc"><?=$oModel->modelYear?></span><br/>
                <span class="sign">ID продавца</span> : <span class="desc"><?=$oModel->merchantID?></span><br/>
                <span class="sign">Двигатель</span> : <span class="desc"><?=$oModel->engineCode?></span><br/>
                <span class="sign">КПП</span> : <span class="desc"><?=$gearboxCode?></span><br/>
                <span class="sign">ID привода осей</span> : <span class="desc"><?=$driveAxles?></span><br/>
                <span class="sign">Оснащение</span> : <span class="desc"><?=$oModel->equipment?></span><br/>
                <span class="sign">Цвет крыши</span> : <span class="desc"><?=$oModel->roofСolor?></span><br/>
                <span class="sign">Цвет кузова</span> : <span class="desc"><?=$oModel->paintColor?></span><br/>
                <span class="sign">Код страны</span> : <span class="desc"><?=$oModel->countryCode?></span><br/>
                <?php if( substr_count($gearboxCode,'*')>0 || substr_count($driveAxles,'*')>0 ){?>
                    <span class="red">*</span><span class="cGrey italic ml5">Классификация двигателя или КП неоднозначна</span><br/>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="columns2 explanation">
        <span class="cBlue"></span>
        <div class="expTable br6 overflowHidden">
            <div class="eTableHead">Основные характеристики</div>
            <div class="eTableBody">
                <span class="cBlue">Обозначение двигателя <?=$oModel->engineCode?></span><br/>
                <span class="sign">Объем, см<sup>3</sup></span> : <span class="desc"><?=$oMKB->volume?></span><br/>
                <span class="sign">Мощность</span> : <span class="desc"><?=$oMKB->kilowatt?>кВт / <?=$oMKB->horsepower?>л.с.</span><br/>
                <span class="sign">Цилиндров</span> : <span class="desc"><?=$oMKB->cylinders?></span><br/>
                <span class="sign">Возможные КП</span> : <span class="desc"><?=$oMKB->models?></span><br/>
                <span class="sign">Время монтажа</span> : <span class="desc"><?=$oMKB->installation?></span><br/>
                <?php if( $oMKB->other ){?>
                    <span class="sign">Примечание</span> : <span class="desc"><?=$oMKB->other?></span>
                <?php }?> <br/>
                <br/>
                <span class="cBlue">Обозначение КПП <?=A2D::property($oGKB,"gearbox","<span class=\"red\">*</span>")?></span><br/>
                <span class="sign">ID привода осей</span> : <span class="desc"><?=A2D::property($oGKB,"model","<span class=\"red\">*</span>")?></span><br/>
                <span class="sign">Время монтажа</span> : <span class="desc"><?=A2D::property($oGKB,"installation","<span class=\"red\">*</span>")?></span><br/>
                <?php if( ($gkbOther=A2D::property($oGKB,"other")) ){?>
                    <span class="sign">Примечание</span> : <span class="desc"><?=$gkbOther?></span>
                <?php }?> <br/>
                <?php if( substr_count($gearboxCode,'*')>0 || substr_count($driveAxles,'*')>0 ){?>
                    <span class="red">*</span><span class="cGrey italic ml5">Классификация двигателя или КП неоднозначна</span><br/>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="clear"></div>

    <div id="catalogMarkets" class="catalogMarkets mb20">
            <?php foreach($oModels as $m){

                $mark    = strtolower($oModel->markName);
                $model   = $m->modelCode;

                $prod    = $oModel->modelYear;
                $catalog = $oModel->catalog;
                $dir     = $oModel->dir;
                ?>
                <h3 class=""><span class="cBlue fs18">Выбранная модель: </span><?=$m->modelName?></h3>
                <ul>
                <?php
                foreach( $m->markets AS $market ){

                    $marketName = $market->marketName;
                    $market  = $market->marketCode;
                    $modelUrl = "/etka/groups.php?mark={$mark}&market={$market}&model={$model}&year={$prod}&code={$catalog}";
                    ?>

                    <li class="fl">
                        <a href="<?=$modelUrl?>">
                            <img src="/media/images/etka/markets/<?=strtolower($market)?>.png" alt="" class="mb5"/><br />
                            <?=@$marketName?>
                        </a>
                    </li>

                <?php } ?>
                </ul>
                <div class="clear"></div>
            <?php } ?>
    </div>

    <div class="mb20">
        <h3 class="">Комплектация автомобиля</h3>
        <table id="dataTable" class="dataTable" width="100%">

            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach( $oPRNs AS $pr ){?>
                    <tr>
                    <td><?=$pr->pr_nummer?></td>
                    <td><?=$pr->pr_familie?></td>
                    <td><?=$pr->pr_text?></td>
                    <td><?=$pr->pr_familie_text?></td>
                </tr>
                <?php }?>
            </tbody>

        </table>
    </div>

</div>