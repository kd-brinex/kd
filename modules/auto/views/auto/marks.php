<?php

/** Обязательно к применению */
//include "_lib.php";    /// После подключения доступен класс A2D
//include "adc/api.php"; /// После подключения доступен класс ADCAPI


/// Устанавливаем объект $oA2D - объект для работы с каталогом Компании АвтоДилер
//$oA2D = ADCAPI::instance();

/// Получаем переменную из своего окружения
//$sTypeID = $oA2D->rcv($typeid);
//
///// Получение марок для группы. Если группу не передать, то получим список вообще всех марок
//$oMarkList = $oAdcpi->getMarkList($sTypeID);

/// Если есть ошибки, то выводим их через функцию доступную нашему объекту
//if( ($aErrors=$oA2D->property($oMarkList,'errors')) ) $oA2D->error($aErrors,404);

/// В ответ вернулся объект с двумя свойствами: Имя группы и Список марок к этой группе.
//$aMarkList =$oAdcpi->property($oMarkList,'marks');
$sTypeName = $oAdcpi->property($oMarkList,'typeName');

/// Подготавливаем данные для конструктора "хлебных крошек" (helpers/breads.php)
//$oAdcpi->aBreads = $oAdcpi->toObj([
//    'types' => [
//        "name" => 'Каталог',
//        "breads" => []
//    ],
//    'marks' => [
//        "name" => $sTypeName,
//        "breads" => []
//    ],
//]);
$this->params['breadcrumbs'][] = ['label'=>'Каталог','url'=>['/auto']];
$this->params['breadcrumbs'][] = $sTypeName;
//Yii::$app->view->registerCssFile('/assets/auto/css/style.css');
//Yii::$app->view->registerCssFile('/assetsname/auto/css/adc.css');
//Yii::$app->view->registerCssFile('/assets/auto/css/fw.css');
//$wroot='../modules/auto/catalogs/auto2d/';
//$cssView = file_get_contents($wroot.'media/css/fw.css');
//$cssView .= file_get_contents($wroot.'media/css/style.css');
//$cssView .= file_get_contents($wroot.'media/css/adc.css');
//$this->registerCss($cssView);
?>




<div id="AutoDealer">

<!--    --><?php //include WWW_ROOT."helpers/breads.php"; /// Продключаем "хлебные крошки"?>

    <div id="marks">
        <h1>Марки в группе <?=$sTypeName?></h1>
        <?php foreach( $aMarkList AS $oMark ){?>
        <div class="col-xs-4 col-md-3 col-lg-2">
        <a class="markItem" href="<?=$oAdcpi->getMarkUrl($oMark);?>">

                <span class="markLogo"><img src="<?=$oMark->mark_img_url?>"  alt="<?=$oMark->mark_name?>"></span>
                <span class="markName"><?=$oMark->mark_name?></span>

            </a>
        </div>
        <?php }?>
    </div>

</div>