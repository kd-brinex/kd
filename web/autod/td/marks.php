<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс TD

/// Устанавливаем объект $oTD - объект для работы с неоригинальными каталогами
$oTD = TD::instance();

/// Получаем тип автомобила с перехода по ссылке из корневого marks.php
$type = $oTD->rcv('type');

/// Запрос на сервер для получение марок по типу
$TDMarks = $oTD->getTDMarks($type); ///$oTD->e($TDMarks);
/// Обработаем ошибки
if( ($aErrors = A2D::property($TDMarks,'errors')) ) $oTD->error($aErrors,404);

/// Определяемся с заголовком и группой
switch( $type ){
    case "pc":
        $h1 = "Неоригинальные каталоги запчастей легковых иномарок";
        $typeID = 9; /// ID можно узнать в ответе от types.php
        $breadMarkName = "Легковые (иномарки)";
        break;
    case "cv":
        $h1 = "Неоригинальные каталоги запчастей грузовых иномарок";
        $typeID = 10; /// ID можно узнать в ответе от types.php
        $breadMarkName = "Грузовые (иномарки)";
        break;
    default:
        $h1 = "Список производителей авто";
}

/// Получаем марки из ответа
$oMarks = A2D::property($TDMarks,'marks',[]); ///$this->e($this->aBreads);

/// "Хлебные крошки" не родные - изменяем ассоциативный массив для имен под переменные
A2D::$arrActions = ['typeID','markID'];
/// На точки входа нет переменных для крошек, строим их самостоятельно
A2D::$aBreads = A2D::toObj([
    'types' => [
        "name" => 'Каталог',
        "breads" => []
    ],
    'marks' => [
        "name" => $breadMarkName,
        "breads" => [ 0 => $typeID ]
    ],
    'models' => [
        "name" => "Неоригинальные каталоги",
        "breads" => []
    ],
]); ///$oBMW->e($aBreads);
/// Текущие крошки ведут в корень, зануляем корневой каталог для старта скриптов
A2D::$catalogRoot = "";

/// Базовая часть пути для перехода на следующий этап
$url = "/td/models.php?type={$type}";
?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/td.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="TDMarks">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <?php include WWW_ROOT."helpers/search.php"; /// Подключаем форму поиска?>

    <h1><?=$h1?></h1>
    <br/>

    <div id="marks">

        <?php foreach( $oMarks AS $oMark ){?>
            <div class="mark fl anime dev" align='center'>
                <a href="<?=$url?>&mark=<?=$oMark->mfa_id?>" title='<?=$oMark->mfa_brand?>'>
                    <img src='<?=$oMark->img_path; ?>' alt='<?=$oMark->mfa_brand?>' border='1' width='36' height="36"/>
                    <?=$oMark->mfa_brand?>
                </a>
            </div>
        <?php } ?>
        <div class="clear"></div>

    </div>

</div>