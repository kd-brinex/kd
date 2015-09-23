<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс ETKA

/// Устанавливаем объект $oETKA - объект для работы с оригинальным каталогом ETKA
$oETKA = ETKA::instance();

/// Получаем данные с перехода по ссылке из etka/subgroups.php
$mark     = $oETKA->rcv('mark');
$market   = $oETKA->rcv('market');
$model    = $oETKA->rcv('model');
$year     = $oETKA->rcv('year');
$code     = $oETKA->rcv('code');
$group    = $oETKA->rcv('group');
$subgroup = $oETKA->rcv('subgroup');
$graphic  = $oETKA->rcv('graphic');
/// Данную переменную можно не передавать из прошлого скрипта если задать значение по умолчанию
$dir      = $oETKA->rcv('dir','R');
/// По умолчанию из базы иллюстрации возвращаются в крупном размере. Задаем нужный коэффициент.
$zoom     = 0.5;

/// Запрос на доступные модели, вошедшие в выбранную серию
$oIllustration = $oETKA->getETKAIllustration($mark,$market,$model,$year,$code,$dir,$group,$subgroup,$graphic,$zoom); ///$oETKA->e($oIllustration);
/// Проверим на ошибки и сообщим при наличии
if( ($errors = A2D::property($oIllustration,'errors')) ) $oETKA->error($errors,404);

///$aBreads = A2D::getBreads($oGroups,'breads','etka'); ///$oETKA->e($aBreads);
$aBreads = A2D::property($oIllustration,'breads','etka'); ///$oETKA->e($aBreads);

/// Метки на иллюстрации
$aLabels   = A2D::property($oIllustration,'labels',[]);
/// Список номенклатуры к изображению
$aDetails  = A2D::property($oIllustration,'details',[]);

/// Получаем данные для построение иллюстрации из общего объекта, что вернул сервер:
$imgInfo = A2D::property($oIllustration,'imgInfo'); /// Объект:
$iSID    = A2D::property($imgInfo,'iSID');        /// Ключ, нужен для построение картинки
$imgUrl  = A2D::property($imgInfo,'url');         /// Адрес иллюстрации на сервере
$width   = A2D::property($imgInfo,'width');       /// Ширина изображения
$height  = A2D::property($imgInfo,'height');      /// Высота изображения
$attrs   = A2D::property($imgInfo,'attrs');       /// Те же данные одним атрибутом
$percent = A2D::property($imgInfo,'percent')/100; /// Коэффициент в каком соотношение вернулась иллюстрация, нужно для ограничения показов с одного агента на IP
$limit   = A2D::property($imgInfo,'limit');       /// Ваше число ограничений для отображения пользователю, у которого сработало ограничение


/// Корневой элемент для зума
$rootZoom = "imageLayout";

/// Заголовок для страницы. Вполне подходит из "хлебных крошек".
$h1 = $aBreads->illustration->name;


/**
 * В данном каталоге присутствуют детали на общей карте с переходом на другую иллюстрацию (поизиция 40, 41):
 *      http://ВАШ_САЙТ/etka/illustration.php?mark=audi&market=RDW&model=A6&year=2011&code=717&group=1&subgroup103&graphic=103053
 * На соседней иллюстрации деталь может быть рассмотрена с другого ракурса или вообще отдельным видом
*/
$jumpUrl = BASE_PATH."/etka/illustration.php?mark={$mark}&market={$market}&model={$model}&year={$year}&code={$code}";


A2D::$aBreads = $aBreads;
/// Что бы постоянно при обновлении структуры не заменять, позвольте полениться...
$prc = $percent; /// У себя на сайте используем $prc, в примерах решили дать полное название, а стркутуру переносим с рабочего сайта

?>

<meta charset="utf-8">
<link href="../media/css/bootstrap.min.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/etka.css" media="all" rel="stylesheet" type="text/css">



<?php include WWW_ROOT."helpers/illustration.php"; /// Продключаем функции для иллюстрации?>
<div id="detailsMap">

    <?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

    <h1><?=$h1?></h1>

    <?php $px = 5; $py = 1; ?>

    <div class="defBorder imgArea mb30" id="imageArea">
        <?php if( $percent<1 ){ $zoom=$percent; ?>
            <div class="isLimit">Превышен лимит показов в сутки (<?=$limit?>)</div>
        <?php }else $zoom=1 ?>
        <div id="imageLayout" style="position:absolute;left:0;top:0;width:<?=$width?>px;height:<?=$height?>px">
            <?php /*/?>
            <img src="<?=$imgUrl?>" border="1">
            <?php //*/?>
            <canvas id="canvas" width="<?=$width?>" height="<?=$height?>" style="margin:0;padding:0;"></canvas>
            <?php //*/
            $prevNamber = FALSE;
            foreach( $aLabels AS $_v ){

                $title   = $_v->id;
                $lLeft   = $_v->cLeft*$zoom - $px*$zoom;
                $lTop    = $_v->cTop*$zoom - $py*$zoom;
                $lWidth  = ( $_v->cWidth ) + $px*$zoom*2;
                $lHeight = ( $_v->cHeight ) + $py*$zoom*2;

                $currNumber = $_v->cPoint;
                $number = ( $currNumber==$prevNamber ) ?$currNumber :$currNumber;
                $prevNamber = $currNumber;
                ?>
                <div id="l<?=$number?>" class="l<?=$number?> mapLabel" title="<?=$title?>"
                     style="
                         position:absolute;
                         left:<?=$lLeft?>px;
                         top:<?=$lTop?>px;
                         min-width:<?=$lWidth?>px;
                         min-height:<?=$lHeight?>px;
                         padding:<?=$py?>px <?=$px?>px;
                         "
                     onclick="labelClick(this,false)"
                     ondblclick="labelClick(this,true)"
                    >

                </div>
            <?php } //*/?>
        </div>
        <?php include WWW_ROOT."helpers/zoomer.php"; /// Подключаем функцию панели с зумером?>
    </div>

    <div id="detailsList">
        <table class="simpleTable innerTable">
            <thead>
            <tr>
                <th class="ETKADetailPosition">№</th>
                <th class="ETKADetailNumber">Номер</th>
                <th class="ETKAName">Наименование</th>
                <th class="ETKAOther">Примечание</th>
                <th class="ETKADetailQuantity">Кол-во</th>
                <th class="ETKAInfo">Данные по модели</th>
            </tr>
            </thead>
            <tbody>
            <?php
            //$prevNamber = FALSE;
            $nc = [];
            $countDetails = count($aDetails)-1;
            /*/
            print'<pre>';print_r(array(
                $countDetails
            ));print'</pre>';exit;
            //*/
            for( $i=0; $i<=$countDetails ; ++$i ){ $_v = $aDetails[$i];
                //$i = -1; foreach( $aDetails AS $_v ){ ++$i;

                $currNumber = $_v->btpos;

                /// Number Format
                $_v->teilenummer = preg_replace('/(\s+)/','  ',$_v->teilenummer);
                ///$_v->teilenummer = implode("&ensp;", str_split($_v->teilenummer,3));
                $_v->teilenummer = preg_replace("~((?:.|\n){3})~im","\${1} ",$_v->teilenummer);

                $detailID = ( $prc==1 ) ?$_v->teilenummer :"*******";

                $style = "";
                $other = $_v->tsbem_text;
                if( $_v->ou=="U" ){
                    $style = 'style="background:lightgreen"';
                }
                elseif( $_v->btpos && $_v->tsbem_text && !$_v->ou && !$_v->stuck && !$_v->teilenummer ){
                    $style = 'style="background:lavender"';
                    $gr = $other{0};
                    $sg = substr($other,0,3);
                    $aJump = explode('-',$other);
                    if( count($aJump)==2 ){
                        $p1 = $aJump[0];
                        $p2 = sprintf("%'.03d\n",$aJump[1]);
                        $jump = $p1.$p2;
                        $a1 = "<a href=\"$jumpUrl&group=$gr&subgroup=$sg&graphic=$jump\" target=\"_blank\">";
                        $a2 = "</a>";
                        $other = $a1.$other.$a2;
                    }
                }

                ?>
                <tr id="d<?=$_v->id?>"
                    <?php if( $_v->ou!="U" ){ ?>
                        class="none anime pointer"
                        data-position="<?=str_replace(['(',')'],'',trim($currNumber))?>"
                        onclick = "trClick(this,0)"
                        ondblclick = "trClick(this,1)"
                    <?php }else{?>
                        <?=$style?>
                    <?php }?>
                    >
                    <td class="ETKADetailPosition"><?=$_v->btpos?></td>
                    <td class="ETKADetailNumber detailNumber c2cValue" id="c2cValue_<?=$_v->id?>">
                        <?php if( $detailID ){?>
                            <?=A2D::callBackLink($detailID,A2D::$callback)?>
                            &ensp;<img title="Скопировать" id="c2cBttn_<?=$_v->id?>" src="/media/images/copy_20x20.png">
                        <?php }?>
                    </td>
                    <td class="ETKAName detailName"><?=$_v->tsben_text?></td>
                    <td class="ETKAOther"><?=$other?></td>
                    <td class="ETKADetailQuantity"><?=str_replace(";","</br>",$_v->stuck)?></td>
                    <td class="ETKAInfo"><?=$_v->tsmoa_text?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>

</div>

<script>
    var offline = '<?=A2D::$offline?>';
    jQueryA2D(document).ready(function(){
        labelsTitle();
    });
</script>