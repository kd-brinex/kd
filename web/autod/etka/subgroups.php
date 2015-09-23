<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс ETKA

/// Устанавливаем объект $oETKA - объект для работы с оригинальным каталогом ETKA
$oETKA = ETKA::instance();

/// Получаем данные с перехода по ссылке из etka/groups.php
$mark   = $oETKA->rcv('mark');
$market = $oETKA->rcv('market');
$model  = $oETKA->rcv('model');
$year   = $oETKA->rcv('year');
$code   = $oETKA->rcv('code');
$group  = $oETKA->rcv('group');
/// Данную переменную можно не передавать из прошлого скрипта если задать значение по умолчанию
$dir    = $oETKA->rcv('dir','R');


/// Запрос на доступные модели, вошедшие в выбранную серию
$oSubGroups = $oETKA->getETKASubGroups($mark,$market,$model,$year,$code,$dir,$group); ///$oETKA->e($oSubGroups);
///$oSubGroups  = $this->getETKASubGroups($mark,$market,$model,$prod,$cat,$dir,$group);  ///$this->e($oSubGroups);
/// Проверим на ошибки и сообщим при наличии
if( ($errors = A2D::property($oSubGroups,'errors')) ) $oETKA->error($errors,404);

///$aBreads = A2D::getBreads($oGroups,'breads','etka'); ///$oETKA->e($aBreads);
$aBreads = A2D::property($oSubGroups,'breads','etka'); ///$oETKA->e($aBreads);

/// Информация по деталям выбранного узла с иллюстрациями
$ug = A2D::property($oSubGroups,'ug',[]);

/// Строим заголовок H1 для текущей страницы
$lvl2Name  = $aBreads->subgroups->name;
$markName  = ucfirst($mark);
$modelName = $aBreads->groups->name;
$h1 = "$lvl2Name для $markName $modelName";

/// После работы с "хлебными крошками" передаем массив в конструктор
A2D::$aBreads = $aBreads;

/// Базовая часть пути для перехода на следующий этап
$url = BASE_PATH."/etka/illustration.php?mark={$mark}&market={$market}&model={$model}&year={$year}&code={$code}&group={$group}";

?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/etka.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="ETKASubGroups">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>
    
    <h1><?=$h1?></h1>

    <div>
        <?php foreach( $ug AS $_ug ){ if($_ug->ou == 'O') continue; ?>
            <a href="<?=$url?>&subgroup=<?=$_ug->hg_ug?>&graphic=<?=$_ug->bildtafel2?>" class="fl defBorder columns3 mb5 relative">
            <div class="img pd5">
                <img border='1' src='<?=$_ug->img?>' class=""><br />
                <span class="name mt5"><?=$_ug->tsben_text?></span><br />
            </div>

            <span class="options"><?=($o1=$_ug->tsbem_text).(($o1&&$_ug->tsmoa_text)?"; ":"").$_ug->tsmoa_text?></span>
        </a>
        <?php } ?>
    </div>
    <div class="clear"></div>

</div>