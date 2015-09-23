<?php

/** Обязательно к применению */
include "../../_lib.php"; /// После подключения доступен класс A2D
include "../api.php";     /// После подключения доступен класс TD

/// Устанавливаем объект $oTD - объект для работы с оригинальным каталоговом TD
$oTD = TD::instance(); ///$oTD->e([$_GET,$_POST,$_SESSION,$_SERVER]);

/// Получаме рефер ссылку, чтобы пользователя можно было вернуть на предыдущую страницу
$refer = A2D::get($_SERVER,'HTTP_REFERER');

/// Получаем переменные с формы поиска
$detail = trim( $oTD->rcv('detail') );
/// Удаляем ненужные пробелы
$number = str_replace(" ", "", $detail);

/// Запрашиваем, где применяется данная деталь
$errMsg = FALSE;
if( $number ){

    $aResult = $oTD->searchTDNumber($number); ///$oTD->e([$number,$aResult]);

    if( ($errors = A2D::property($aResult,'errors')) ){
        if( $errors->msg=="TD::searchTDNumber::Empty_Number" ) $errMsg = "Пустой номер";
        elseif( $errors->msg=="TD::searchTDNumber::Empty_Result" ) $errMsg = "По Вашему запросу ничего не найдено";
        elseif( $errors->msg=="_Mark_isNot_Paid" ) $errMsg = "После оплаты, будет доступен поиск по каталогу";
        elseif( $errors->msg=="_Mark_Off_inLK" ) $errMsg = "Для поиска по марке включите ее в личном кабинете";
        elseif( $bErr ) throw new HTTP_Exception_404($errors->msg,$referrer);
        else $vContent = FALSE;
    }
    else $aResult = A2D::property($aResult,'result');

}
else{
    $errMsg = "Некорректные данные для поиска";
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
    'types' => [
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
<link href="../../media/css/TD.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="searchTDNumber">
    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <?php include WWW_ROOT."helpers/search.php"; /// Подключаем форму поиска?>

    <?php if( $errMsg ){?>
        <h2><?=$errMsg?></h2>
    <?php }else{?>
    <h2>Результат поиска (<?=count($aResult)?>) :</h2>
    <table class="dataTable">
        <tr>
            <th>Поставщик</th>
            <th>Номер</th>
            <th>Наименование</th>
            <th>Копирайт</th>
            <th>Агрегат</th>
            <th>Статус</th>
            <th>Сменная деталь</th>
            <th>Информация</th>
        </tr>
        <?php foreach( $aResult as $_article ){?>
            <tr valign="top">
                <td>
                    <img src='<?=$_article->img?>'>
                    <br/><?=$_article->sup_brand?>
                </td>
                <td>
                    <?=$_article->art_article_nr?>
                </td>
                <td><?=$_article->ga_des?></td>
                <td><?=$_article->art_des?></td>
                <td><?=$_article->ga_assembly_des?></td>
                <td><?=$_article->acs_kv_status_des?></td>
                <td><?=(empty($_article->acs_kv_status_des) ? "*" : ""); ?></td>
                <td>
                    <?php if(!empty($_article->trade_numbers)){ ?>
                        <b>Номера пользователя:</b>
                        <ul>
                            <?php foreach ($_article->trade_numbers as $info){ ?>
                                <li><?=$info->arl_display_nr?></li>
                            <?php } ?>
                        </ul>
                        <?php //var_dump($_article->trade_numbers);
                    } ?>
                    <?php if(!empty($_article->supersedes)){ ?>
                        <b>Запасной номер:</b>
                        <ul>
                            <?php foreach ($_article->supersedes as $info){ ?>
                                <li><?=$info->sua_number?></li>
                            <?php } ?>
                        </ul>
                        <?php //var_dump($_article->supersedes);
                    } ?>
                    <?php if(!empty($_article->supersedes_by)){ ?>
                        <b>Заменен:</b>
                        <ul>
                            <?php foreach ($_article->supersedes_by as $info){ ?>
                                <li><?=$info->art_article_nr?></li>
                            <?php } ?>
                        </ul>
                        <?php //var_dump($_article->supersedes_by);
                    } ?>
                    <?php if(!empty($_article->criteria)){ ?>
                        <b>Критерии:</b>
                        <ul>
                            <?php foreach ($_article->criteria as $info){ ?>
                                <li><?php echo $info->cri_short_des_local;
                                    if(!empty($info->cri_short_des_local) and !empty($info->crit_value)){
                                        echo ": ";
                                    }
                                    echo $info->crit_value . " " . $info->crit_unit
                                    ?></li>
                            <?php } ?>
                        </ul>
                        <?php //var_dump($_article->criteria);
                    } ?>
                    <?php if(!empty($_article->info)){ ?>
                        <?php foreach ($_article->info as $info_gr){ ?>
                            <b><?=$info_gr->kv_type_des?>:</b>
                            <ul>
                                <?php foreach ($info_gr->info as $info){ ?>
                                    <li><?=$info->tmt_text?></li>
                                <?php } ?>
                            </ul>
                            <?php //var_dump($info_gr->info); ?>
                        <?php } ?>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?php }?>
</div>