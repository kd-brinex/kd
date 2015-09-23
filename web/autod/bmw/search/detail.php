<?php

/** Обязательно к применению */
include "../../_lib.php"; /// После подключения доступен класс A2D
include "../api.php";     /// После подключения доступен класс BMW

/// Устанавливаем объект $oBMW - объект для работы с оригинальным каталоговом BMW
$oBMW = BMW::instance(); ///$oBMW->e([$_GET,$_POST,$_SESSION,$_SERVER]);

/// Получаме рефер ссылку, чтобы пользователя можно было вернуть на предыдущую страницу
$refer = A2D::get($_SERVER,'HTTP_REFERER');

/// Получаем переменные с формы поиска
$detail = trim( $oBMW->rcv('detail') );
/// Удаляем ненужные пробелы, так как номера подаются так: 07 12 9904879
$number = str_replace(" ", "", $detail);

/// Запрашиваем, где применяется данная деталь
$aResult = $oBMW->searchBMWNumber($number); ///$oBMW->e($aResult);

/// Если с сервера вернулись ошибки, обрабатываем
if( ($errors = A2D::property($aResult,'errors')) ){
    if( $errors->msg=="_searchBMWNumber_Empty_Number" ) $msg = "Пустой номер";
    elseif( $errors->msg=="_searchBMWNumber_Empty_Response" ) $msg = "По Вашему запросу ничего не найдено";
    else $msg = $errors->msg;
    $adRef = "<br/><a href=\"$refer\">Вернуться на предыдущую страницу</a>";
    $oBMW->error($msg.$adRef);
}
/// В BMW может вернуться предупреждение, к примеру, что деталь устарела
elseif( ($warnings = A2D::property($aResult,'warnings')) ){
    if( $warnings->msg=="_searchBMWNumber_Outdated" ){
        $newDetailID = $warnings->arr->newDetailID;
        $msg = "".
            "<span class='red'>Внимание: деталь <b>$detail</b> снята с производства!</span>".
            "Новый номер детали: <a class='menuLink' href='/bmw/search/detail.php?fromDetail=1&detail=$newDetailID'>$newDetailID</a>".
            "";
    }
    else $msg = $errors->msg;
    $oBMW->error($msg);
}
/// Если все хорошо - подготавливаем данные
else{
    /// Мы специально вынесли во внешнию форму, так как подача данных пожет измениться
    $aTree = $oBMW->searchBMWTree($aResult,$number,$mark);
}

/**
 * Строим две "хлебные крошки": в корень каталога и на прошлую страницу
 * ~ можете что то свое реализовать ~
 *
 * У нас появился новый параметр, который раньше не описывался, чтобы не путать
 * Как успели заметить - последняя крошка идет не ссылкой, а текстом, так как нет смысла ссылаться на самого себя
 * Если мы в ключе крошки передадим refer, система обработает эту крошку как ссылку
 * Да и как такого файла на старт нам не надо
 */
A2D::$catalogRoot = "";
A2D::$aBreads = A2D::toObj([
    'series' => [
        "name" => 'Каталог',
        "breads" => []
    ],
    'refer' => [
        'url' => $refer,
        'txt' => 'Вернуться на предыдущую страницу'
    ],
]);

?>

<meta charset="utf-8">
<link href="../../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../../media/css/bmw.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="searchBMWNumber">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <?php include WWW_ROOT."helpers/search.php"; /// Подключаем форму поиска?>

    <?php foreach( $aTree AS $k=>$aRow ){?>
        <div class="treeBranch">
            <div id="plusBranch<?=$k?>" class="plusBranch anime" onclick="branchToggle('plusBranch<?=$k?>','itemsBranch<?=$k?>')">+</div>
            <div class="itemsBranchL">
                <div class="headBranchLVL1 anime" onclick="branchToggle('plusBranch<?=$k?>','itemsBranch<?=$k?>')"><?=$aRow['name']?></div>
                <div id="itemsBranch<?=$k?>" class="" style="display:none">
                    <?php foreach( $aRow['children'] AS $m=>$aItem ){?>
                        <div class="headBranchLVL2 anime pl40" onclick="$('#market<?=$k.$m?>').slideToggle(600)"><?=$aItem['name']?></div>
                        <div id="market<?=$k.$m?>" class="text-left ml60 pb20" style="display:none">
                            <?php foreach( $aItem['children'] AS $u){?>
                                <a href="<?=$u['url']?>" target="_blank">
                                    <span class="itemDesc" class="anime"><?=$u['name']?></span>
                                </a><br>
                            <?php }?>
                        </div>
                    <?php }?>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    <?php }?>

    <script>
        function branchToggle(plusBranch,itemsBranch){
            var $plusBranch  = $('#'+plusBranch),
                $itemsBranch = $('#'+itemsBranch);
            if( $plusBranch.html()!='+' ){
                $plusBranch.html('+');
                $itemsBranch.slideUp(700);
            }
            else{
                $plusBranch.html('&ndash;');
                $itemsBranch.slideDown(700);
            }
        }
    </script>

</div>