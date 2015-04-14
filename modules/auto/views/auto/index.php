<?php
$wroot='../modules/auto/catalogs/'.$catalog.'/';
//include $wroot."_lib.php";    /// После подключения доступен класс A2D
//include $wroot."adc/api.php"; /// После подключения доступен класс ADCAPI
///// Устанавливаем объект $oA2D - объект для работы с каталогом Компании АвтоДилер


/// Первый запрос к каталогу. Получаем все доступные нам группы техники
//$aTypes = $oA2D->getTypeList();
/// Раскомментировав строку нижу, можно посмотреть что вернул сервер
//$oA2D->e($aTypeList);

/// Если есть ошибки, то выводим их через функцию, доступную нашему объекту
if( ($aErrors=$oA2D->property($aTypes,'errors')) ) $oAdcpi->error($aErrors,404);

/// Подготавливаем данные для конструктора "хлебных крошек" (helpers/breads.php)
$oAdcpi->aBreads = $oA2D->toObj([
    'types' => [
        "name" => 'Каталог',
        "breads" => []
    ]
]);


$cssView = file_get_contents($wroot.'media/css/fw.css');
$cssView .= file_get_contents($wroot.'media/css/style.css');
$cssView .= file_get_contents($wroot.'media/css/adc.css');
$this->registerCss($cssView);
?>
<div class="auto-default-index">
    <h1><?= $catalog?></h1>
    <div id="AutoDealer">

<!--        --><?php //include $wroot."helpers/breads.php"; /// Продключаем "хлебные крошки"?>

<!--        --><?php //include $wroot."helpers/search.php"; /// Подключаем форму поиска?>

        <div id="types">
            <h1><?=$oA2D->lang('h1')?></h1>
            <?php foreach( $aTypes AS $aType ){?>
                <div class="col-md-6">
                <a class="typeItem" href="auto/marks/<?=$aType->type_id?>">
                    <span class="typeLogo"><img src="<?=$aType->type_url?>" alt="<?=$aType->type_name?>"></span>
                    <span class="typeName"><?=$aType->type_name?></span>
                </a>
                    </div>
            <?php }?>
        </div>
</div>
