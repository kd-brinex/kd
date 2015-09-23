<?php

/** Обязательно к применению */
include "../../_lib.php"; /// После подключения доступен класс A2D
include "../api.php";     /// После подключения доступен класс ETKA

/// Устанавливаем объект $oETKA - объект для работы с оригинальным каталоговом ETKA
$oETKA = ETKA::instance(); ///$oETKA->e([$_GET,$_POST,$_SESSION,$_SERVER]);

/// Получаме рефер ссылку, чтобы пользователя можно было вернуть на предыдущую страницу
$refer = A2D::get($_SERVER,'HTTP_REFERER');

/// Получаем переменные с формы поиска
$mark   = $oETKA->rcv('mark');
$detail = trim( $oETKA->rcv('detail') );
/// Удаляем ненужные пробелы
$number = str_replace(" ", "", $detail);

/// Запрашиваем, где применяется данная деталь
$aResult = $oETKA->searchETKANumber($number,$mark); ///$oETKA->e($aResult);

/// Если с сервера вернулись ошибки, обрабатываем
if( ($errors = A2D::property($aResult,'errors')) ){
    if( $errors->msg=="_searchETKANumber_Empty_Number" ) $msg = "Пустой номер";
    elseif( $errors->msg=="_searchETKANumber_Empty_Response" ) $msg = "По Вашему запросу ничего не найдено";
    else $msg = $errors->msg;
    $adRef = "<br/><a href=\"$refer\">Вернуться на предыдущую страницу</a>";
    $oETKA->error($msg.$adRef);
}
/// В ETKA может вернуться предупреждение, к примеру, что деталь устарела
elseif( ($warnings = A2D::property($aResult,'warnings')) ){
    if( $warnings->msg=="_searchETKANumber_Outdated" ){
        $newDetailID = $warnings->arr->newDetailID;
        $msg = "".
            "<span class='red'>Внимание: деталь <b>$detail</b> снята с производства!</span>".
            "Новый номер детали: <a class='menuLink' href='/ETKA/search/detail.php?fromDetail=1&detail=$newDetailID'>$newDetailID</a>".
            "";
    }
    else $msg = $errors->msg;
    $oETKA->error($msg);
}
/// Если все хорошо - подготавливаем данные
else{
    /// Мы специально вынесли во внешнию функцию, добавя больше гибкости
    $aTree = $oETKA->searchETKATree($aResult,$number); ///$oETKA->e($aTree);
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
<link href="../../media/css/etka.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="searchETKANumber">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <?php include WWW_ROOT."helpers/search.php"; /// Подключаем форму поиска?>

    <?php foreach( $aTree AS $mark=>$models ){?>
        <div class="treeBranch">
            <div id="plusBranch<?=$mark?>" class="plusBranch anime" onclick="branchToggle('plusBranch<?=$mark?>','itemsBranch<?=$mark?>')">+</div>
            <div class="itemsBranchL">
                <div class="headBranchLVL1 anime" onclick="branchToggle('plusBranch<?=$mark?>','itemsBranch<?=$mark?>')"><?=$models['name']?></div>
                <div id="itemsBranch<?=$mark?>" class="" style="display:none">
                    <?php foreach( $models['children'] AS $model=>$markets ){?>
                        <div class="headBranchLVL2 anime pl40" onclick="$('#market<?=$mark.$model?>').slideToggle(600)"><?=$markets['name']?></div>
                        <div id="market<?=$mark.$model?>" class="text-left ml60 pb20" style="display:none">
                            <?php foreach( $markets['children'] AS $market=>$detail){?>
                                <div class="headBranchLVL3 anime pl40" onclick="$('#detail<?=$model.$market?>').slideToggle(600)"><?=$detail['name']?></div>
                                <div id="detail<?=$model.$market?>" class="text-left ml60 pb20" style="">
                                <?php foreach( $detail['children'] AS $u){?>
                                <a href="<?=$u['url']?>" target="_blank">
                                    <span class="itemDesc" class="anime"><?=$u['name']?></span>
                                    </a><?=( ($desc=A2D::get($u,'desc')) )?$desc:""?><br>
                                <?php }?>
                            </div>
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
            var $plusBranch = $('#'+plusBranch), $itemsBranch = $('#'+itemsBranch);
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