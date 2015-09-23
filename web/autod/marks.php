<?php

/** Обязательно к применению */
include "_lib.php";    /// После подключения доступен класс A2D
include "adc/api.php"; /// После подключения доступен класс ADC


/// Устанавливаем объект $oA2D - объект для работы с каталогом Компании АвтоДилер
$oA2D = ADC::instance();

/// Получаем переменную из своего окружения
$sTypeID = $oA2D->rcv('typeID');

/// Получение марок для группы. Если группу не передать, то получим список вообще всех марок
$oMarkList = $oA2D->getMarkList($sTypeID);
/// Раскомментировав строку нижу, можно посмотреть что вернул сервер
//$oA2D->e($oMarkList);

/// Если есть ошибки, то выводим их через функцию доступную нашему объекту
if( ($aErrors=A2D::property($oMarkList,'errors')) ) $oA2D->error($aErrors,404);

/// В ответ вернулся объект с двумя свойствами: Имя группы и Список марок к этой группе.
$aMarkList = A2D::property($oMarkList,'marks');
$sTypeName = A2D::property($oMarkList,'typeName');

/// Подготавливаем данные для конструктора "хлебных крошек" (helpers/breads.php)
A2D::$aBreads = A2D::toObj([
    'types' => [
        "name" => 'Каталог',
        "breads" => []
    ],
    'marks' => [
        "name" => $sTypeName,
        "breads" => []
    ],
]);
?>

<meta charset="utf-8">
<link href="media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="media/css/adc.css" media="all" rel="stylesheet" type="text/css">


<div id="AutoDealer">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <div id="marks">
        <h1>Марки в группе <?=$sTypeName?></h1>
        <?php foreach( $aMarkList AS $oMark ){?>
            <a class="markItem" href="<?=A2D::getMarkUrl($oMark);?>">
                <span class="markLogo"><img src="<?=$oMark->mark_img_url?>" width="32" height="32" alt="<?=$oMark->mark_name?>"></span>
                <span class="markName"><?=$oMark->mark_name?></span>
            </a>
        <?php }?>
    </div>

</div>