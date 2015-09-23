<?php

/** Обязательно к применению */
include "_lib.php";    /// После подключения доступен класс A2D
include "adc/api.php"; /// После подключения доступен класс ADC


/// Устанавливаем объект $oA2D - объект для работы с каталогом Компании АвтоДилер
$oA2D = ADC::instance();

/// Первый запрос к каталогу. Получаем все доступные нам группы техники
$aTypes = $oA2D->getTypeList();
/// Раскомментировав строку нижу, можно посмотреть что вернул сервер
//$oA2D->e($aTypeList);

/// Если есть ошибки, то выводим их через функцию, доступную нашему объекту
if( ($aErrors=A2D::property($aTypes,'errors')) ) $oA2D->error($aErrors,404);

/// Подготавливаем данные для конструктора "хлебных крошек" (helpers/breads.php)
A2D::$aBreads = A2D::toObj([
    'types' => [
        "name" => 'Каталог',
        "breads" => []
    ]
]);

?>

<meta charset="utf-8">
<link href="media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="media/css/adc.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>


<div id="AutoDealer">

    <?php include WWW_ROOT."helpers/breads.php"; /// Продключаем "хлебные крошки"?>
    <?php include WWW_ROOT."helpers/search.php"; /// Подключаем форму поиска?>

    <div id="types">
        <h1><?=A2D::lang('h1')?></h1>
        <?php foreach( $aTypes AS $aType ){?>
            <a class="typeItem" href="marks.php?typeID=<?=$aType->type_id?>">
                <span class="typeLogo"><img src="<?=$aType->type_url?>" alt="<?=$aType->type_name?>"></span>
                <span class="typeName"><?=$aType->type_name?></span>
            </a>
        <?php }?>
    </div>

</div>