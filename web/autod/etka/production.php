<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс ETKA

/// Устанавливаем объект $oETKA - объект для работы с оригинальным каталогом ETKA
$oETKA = ETKA::instance();

/// Получаем данные с перехода по ссылке из etka/markets.php
$mark   = $oETKA->rcv('mark');
$market = $oETKA->rcv('market');
$model  = $oETKA->rcv('model');
$dir    = ( ($d=$oETKA->rcv('dir')) ) ? $d : 'R'; /// вин точно знает dir_name

/// Запрос на доступные модели, вошедшие в выбранную серию
$oProduction = $oETKA->getETKAProduction($mark,$market,$model); ///$oETKA->e($oProduction);
/// Проверим на ошибки и сообщим при наличии
if( ($errors = A2D::property($oProduction,'errors')) ) $oETKA->error($errors,404);

/// Передаем массив с "хлебными крошками" в конструктор
A2D::$aBreads = A2D::property($oProduction,'breads','etka'); ///$oETKA->e(A2D::$aBreads);

/**
 * Данные отдаются ввиде массива/объекта как есть из базы от производителя
 * Мы решили из полученных данных собрать свой массив
 * На основе массива подаем данные, как мы выдим их визульное представление
 */
$oProdNS = A2D::property($oProduction,'prod',"A"); ///$this->e($oProdNS);
$oProd = [];
foreach( $oProdNS AS $oPr ){
    $oProd[$oPr->einsatz][$oPr->id] = [
        "einsatz"     => $oPr->einsatz,
        "epis_typ"    => $oPr->epis_typ,
        "bezeichnung" => $oPr->bezeichnung
    ];
}
/**
 * Для некоторых моделей в один год могли выпустить не одну модель, к пр:
 *      WWW.SITE.RU/etka/production.php?mark=audi&market=RDW&model=A6
 * В 2005 и 2011 году было выпущенно по 2-е модели
 * В общем мы так увидели подачу данных, что бы понять о чем речь, загрузите данную модель в браузере
 *
 * Ниже заголовки для таких ситуаций
*/
$h11 = "Выберите модельный год";
$h12 = "Укажите ограничение";

/// Базовая часть пути для перехода на следующий этап
$url = BASE_PATH."/etka/groups.php?mark={$mark}&market={$market}&model={$model}";

?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/etka.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>



<div id="ETKAProduction">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <h1><?=$h11?></h1>

    <div id="content">
    <?php foreach( $oProd AS $p ){?>

        <?php if( count($p)==1 ){ $p=current($p)?>
            <a class="bttnGB anime mb5" href="<?=$url?>&year=<?=$p['einsatz']?>&code=<?=$p['epis_typ']?>&dir=<?=$dir?>">
            <?=$p['einsatz']; ?>
        </a>
        <?php }else{?>

            <?php $years = current($p)['einsatz'];?>
            <div class="dn" data-years="<?=$years?>">
            <span id="toYears" class="mb40 fs20 pointer inlineBlock" onclick="toYears();">&larr; Вернуться к списку</span><br />
                <?php foreach( $p AS $_p ){?>
                    <a class="bttnGB anime mb5" href="<?=$url?>&year=<?=$_p['einsatz']?>&code=<?=$_p['epis_typ']?>&dir=<?=$dir?>">
                <?=$_p['bezeichnung']?>
            </a>
                <?php }?>
        </div>
            <a class="bttnGB anime mb5" onclick="modification(this)" data-years="<?=$years?>">
            <?=$years?>
        </a>

        <?php }?>

    <?php }?>
    </div>

</div>

<script>
    var $contant,$years,$div,
        $h11 = '<?=$h11?>',
        $h12 = '<?=$h12?>',
        $root=$('#ETKAProduction')
        ;
    function modification(a){
        $years   = $(a).data('years');
        $div     = $('div[data-years="'+$years+'"]' ).clone();
        $contant = $('#content' ).detach();
        $('h1' ).text($h12);
        $div.show().appendTo($root);
        return false;
    }
    function toYears(){
        $div.remove();
        $('h1' ).text($h11);
        $contant.appendTo($root);
    }
</script>