<?php

/** Обязательно к применению */
include "../../_lib.php"; /// После подключения доступен класс A2D
include "../api.php";     /// После подключения доступен класс ETKA

/// Устанавливаем объект $oETKA - объект для работы с оригинальным каталоговом ETKA
$oETKA = ETKA::instance(); ///$oETKA->e([$_GET,$_POST,$_SESSION,$_SERVER]);

/// Получаме рефер ссылку, чтобы пользователя можно было вернуть на предыдущую страницу
$refer = A2D::get($_SERVER,'HTTP_REFERER');

/// Получаем переменные с формы поиска
$vin = $oETKA->rcv('vin');

/// Отправляем запрос в надежде, что наш VIN отработает
$aResult = $oETKA->searchETKAVIN($vin); ///$oETKA->e($aResult);

/// Если с сервера вернулись ошибки, обрабатываем
if( ($errors = A2D::property($aResult,'errors')) ){
    if( $errors->msg=="_searchETKAVIN_Empty_VIN" ) $msg = "Пустой VIN";
    elseif( $errors->msg=="_searchETKAVIN_Empty_Response" ) $msg = "По Вашему запросу ничего не найдено";
    else $msg = $errors->msg;
    $adRef = "<br/><a href=\"$refer\">Вернуться на предыдущую страницу</a>";
    $oETKA->error($msg.$adRef);
}
/// При результате обрабатываем полученные данные
else{

    /// Если вернулась одна модель, то сразу переходим в нее
    if( count($aResult->vinInfo)==1 ){
        $oModel = current($aResult->vinInfo);
        $nextUrl = "/ETKA/vinInfo.php?vin={$oModel->vin}&vkbz={$oModel->vkbz}";
        header("Location: $nextUrl");
    }

}

/// Наши произвольные "хлебные крошки": Возвращаем пользователя в каталог и уведомляем где он находится
A2D::$aBreads = A2D::toObj([
    0 => [
        "name" => 'Каталог',
        "breads" => [ 0 => 'catalog' ]
    ],
    1 => [
        "name" => 'Поиск по VIN',
        "breads" => []
    ],
]);

?>

<meta charset="utf-8">
<link href="../../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../../media/css/etka.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>


<div id="searchETKAVIN">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <?php include WWW_ROOT."helpers/search.php"; /// Подключаем форму поиска?>

    <div>
        <?php foreach( $aResult->vinInfo AS $k=>$oModel ){
            $nextUrl = "/etka/vinInfo.php?vin=$vin&id={$oModel->vin}&vkbz={$oModel->vkbz}";
            $columns = (count($aResult->vinInfo)==2) ?"columns2" :"columns3";
            $gearboxCode = preg_replace("#(\\*+)#","<span class=\"red ml5\">$1</span>",$oModel->gearboxCode);
            $driveAxles  = preg_replace("#(\\*+)#","<span class=\"red ml5\">$1</span>",$oModel->driveAxles);
            ?>
            <a href="<?=$nextUrl?>" class="<?=$columns?> underlineOff">
                <div class="explanation">
                    <span class="cBlue"></span>
                    <div class="expTable br6 overflowHidden">
                        <div class="eTableHead">Основные характеристики</div>
                        <div class="eTableBody">
                            <span class="sign">Марка</span> : <span class="desc"><?=$oModel->markName?></span><br/>
                            <span class="sign">Модель</span> : <span class="desc"><?=$oModel->modelName?></span><br/>
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
                            <?php }else{?><br/><?php }?>
                        </div>
                    </div>
                </div>
            </a>
        <?php } ?>
    </div>

</div>