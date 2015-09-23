<?php

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс TOY

/// Устанавливаем объект $oTOY - объект для работы с каталогом Toyota
$oTOY = TOY::instance();

/// Получаем данные с перехода по ссылке из toyota/groups.php
$mark    = $oTOY->rcv('mark');
$market  = $oTOY->rcv('market');
$model   = $oTOY->rcv('model');
$compl   = $oTOY->rcv('compl');
$opt     = $oTOY->rcv('opt');
$code    = $oTOY->rcv('code');
$group   = $oTOY->rcv('group');
$graphic = $oTOY->rcv('graphic');

/// Вспомогательные данные
$vin       = $oTOY->rcv('vin');
$vdate     = $oTOY->rcv('vdate');
$siyopt    = $oTOY->rcv('siyopt');
/// При наличие строим дополнительную строку запроса
$getString = ""
    .(( $vin )    ?"&vin=$vin"      :"")
    .(( $vdate )  ?"&vdate=$vdate"  :"")
    .(( $siyopt ) ?"&siyopt=$siyopt" :"")
;
/**
 * Некоторые иллюстрации имеют смежные изображения (вид с другого ракурса или отдельно рассмотренную деталь)
 * Данная переменная позволяет избавится от лишнего звена и сгруппировать такие изображения в закладки
 * http://acat.autodealer.ru/toyota/jp/115120/QNC25-BHSGK/D20G/003/8401/84D163C (пример с нашего сайта)
 * Другой вариант пока не доступен. Если не передать данную переменную потеряем вкладки
*/
$grFalse = TRUE;

/// Получаем необходимые данные для построения страницы. Второй строкой останавливаемся при ошибках с сервера
$TOYIllustration = $oTOY->getToyPic($market,$model,$compl,$opt,$code,$group,$graphic,$vin,$vdate,$siyopt,$grFalse); ///$oTOY->e($TOYIllustration);
if( ($aErrors = A2D::property($TOYIllustration,'errors')) ) $oTOY->error($aErrors,404);

/// С "хлебными крошками" никто не работает можно сразу передать в объект для конструктора
A2D::$aBreads = A2D::property($TOYIllustration,'aBreads',[]); ///$oTOY->e($aBreads);

/// Получаем список номенклатуры из общего объекта, что вернул сервер
$aDetails = A2D::property($TOYIllustration,'details',[]);

/// Получаем ссылки на смежные иллюстрации из общего объекта, что вернул сервер
$aTabs    = A2D::property($TOYIllustration,'tabs',[]);

/// Получаем список смежных по дереву деталей из общего объекта, что вернул сервер
$aSibling = A2D::property($TOYIllustration,'sibling',[]);
$prev = A2D::property($aSibling,'prev'); /// Предидущее изображение
$next = A2D::property($aSibling,'next'); /// Следующее изображение

/// Получаем данные для построение иллюстрации из общего объекта, что вернул сервер:
$imgInfo = A2D::property($TOYIllustration,'imgInfo'); /// Объект:
$iSID    = A2D::property($imgInfo,'iSID');        /// Ключ, нужен для построение картинки
$imgUrl  = A2D::property($imgInfo,'url');         /// Адрес иллюстрации на сервере
$width   = A2D::property($imgInfo,'width');       /// Ширина изображения
$height  = A2D::property($imgInfo,'height');      /// Высота изображения
$attrs   = A2D::property($imgInfo,'attrs');       /// Те же данные одним атрибутом
$percent = A2D::property($imgInfo,'percent')/100; /// Коэффициент в каком соотношение вернулась иллюстрация, нужно для ограничения показов с одного агента на IP
$limit   = A2D::property($imgInfo,'limit');       /// Ваше число ограничений для отображения пользователю, у которого сработало ограничение


/// Корневой элемент для зума
$rootZoom = "imageLayout";

/// действие для следующего/предыдущего изображения
$_ACTION = "/toyota/illustration.php";

/// Базовый набор переменных для всех формируемых ссылок
$_PARAMS = "mark={$mark}&market={$market}&model={$model}&compl={$compl}&opt={$opt}&code={$code}";

/// Для следующего/предидущего изображения
$forSibl = "$_ACTION?$_PARAMS";

/// Иногда попадаются сгруппированные изображения
$forTabs = $forSibl."&group=$group";

/// Для получение доп информации по детали
$forDetailInfo = "/toyota/detailInfo.php?$_PARAMS&graphic=$graphic";

?>

<meta charset="utf-8">
<link href="../media/css/bootstrap.min.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/toyota.css" media="all" rel="stylesheet" type="text/css">



<?php include WWW_ROOT."helpers/illustration.php"; /// Продключаем функции для иллюстрации?>
<div id="detailsMap">

<?php include WWW_ROOT."helpers/breads.php"; /// Подключаем "хлебные крошки"?>

<div id="nav">
    <?php if( $prev ){?>
        <a title="<?=$prev->desc?>" href="<?=$forSibl?>&group=<?=$prev->group?>&graphic=<?=$prev->pic?><?=$getString?>">&#8592;</a>
    <?php }else{?>
        <span title="Это первое изображение" class="noImg">&#8592;</span>
    <?php }?>
    &emsp;<!--|&#8656; &#8592; &#8249; &#215;&#8855;&#8226;&#9675; &#8250; &#8594; &#8658;|-->&emsp;
    <?php if( $next ){?>
        <a title="<?=$next->desc?>" href="<?=$forSibl?>&group=<?=$next->group?>&graphic=<?=$next->pic?><?=$getString?>">&#8594;</a>
    <?php }else{?>
        <span title="Это последнее изображение" class="noImg">&#8594;</span>
    <?php }?>
</div>

<div id="tabs">
    <?php $i=0;
    $count = count((array)$aTabs);
    if( $count<=4 ) $widthTabs = 25;
    else            $widthTabs = 100/$count-1;
    foreach( $aTabs AS $t ){ $class=($graphic==$t->pic_code)?" class='activeTab cBlue'":""; ++$i;?>
        <a style="width:<?=$widthTabs?>%" href="<?=$forTabs?>&graphic=<?=$t->pic_code?><?=$getString?>"<?=$class?>>Вид <?=$i?> </a>
    <?php }?>
</div>

<?php $px = 5; $py = 1; ?>
<div class="defBorder imgArea mb30" id="imageArea">
    <?php if( $percent<1 ){?>
        <div class="isLimit">Превышен лимит показов в сутки (<?=$limit?>)</div>
    <?php }?>
    <div id="imageLayout" style="position:absolute;left:0;top:0;width:<?=$width?>px;height:<?=$height?>px">
        <canvas id="canvas" <?=$attrs?> style="margin:0;padding:0;"></canvas>
        <?php //*/
        $prevNamber = FALSE;
        foreach( $aDetails AS $_v ){

            $title = ( (!($_v->number_type==1 || $_v->number_type==4)) ) ?"$_v->desc_en - $_v->number" :$_v->number;
            $lLeft   = $_v->x1*$percent - $px*$percent;
            $lTop    = $_v->y1*$percent - $py*$percent;
            $lWidth  = ( $_v->x2*$percent - $lLeft ) + $px*$percent*2;
            $lHeight = ( $_v->y2*$percent - $lTop ) + $py*$percent*2;

            $currNumber = $_v->number;
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
                <?///=$_v->number?>
            </div>
        <?php } //*/?>
    </div>
    <?php include WWW_ROOT."helpers/zoomer.php"; /// Подключаем функцию панели с зумером?>
</div>

<div id="detailsList">
    <table class="simpleTable">
        <thead>
        <tr>
            <th class="ToyBttnInfo"></th>
            <th class="ToyDetailNumber">Номер детали</th>
            <th class="ToyDetailQuantity">Кол-во</th>
            <th>Наименование</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $nc = [];
        for( $i=0; $i < count($aDetails); ++$i ){ $_v = $aDetails[$i];

            $currNumber = ( $percent==1 ) ?$_v->number :"*******";
            $nextNumber = @$aDetails[($i+1)]->number;

            $c = A2D::get($nc,$currNumber);
            if( !$c ) $nc[$currNumber] = 1;
            else $nc[$currNumber] = $c+1;

            if( $currNumber==$nextNumber ) continue;

            $detailName = $_v->desc_en;
            if( $_v->number_type==1 ){ /// Связанная иллюстрация.
                $detailName = "<a href='$forSibl&group={$_v->jGroup}&graphic={$_v->jGraphic}'>*** REFER &#171;$detailName&#187;</a>";
            }

            ?>
            <tr>
                <td colspan="4">
                    <table class="innerTable">
                        <tr id="d<?=$currNumber?>" data-position="<?=$currNumber?>" class="none anime pointer"
                            ondblclick = "trClick(this,1)"
                            onclick = "
                                trClick(this,0);
                            <?php if( !($_v->number_type==1 || $_v->number_type==4) ){?>
                                clickOnTR('<?=$forDetailInfo?>&detail=<?=$_v->pnc?><?=$getString?>',this,'<?=$_v->number_type?>','<?=urlencode($_v->desc_en)?>');
                            <?php }?>
                                "
                            >
                            <td class="ToyBttnInfo">
                                <?php if( !($_v->number_type==1 || $_v->number_type==4) ){?>
                                    <span class="information anime" onclick="bttnClick('<?=$forDetailInfo?>&detail=<?=$_v->pnc?><?=$getString?>',this,'<?=$_v->desc_en?>')">i</span>
                                <?php }?>
                            </td>
                            <td class="ToyDetailNumber<?php if( $_v->number_type==4 ){?> c2cValue<?php }?>" id="c2cValue_<?=$currNumber?>">

                                <?php if( $_v->number_type==4 ){?>
                                    <?=A2D::callBackLink($currNumber,A2D::$callback)?>
                                    &ensp;<img title="Скопировать" id="c2cBttn_<?=$currNumber?>" src="/media/images/copy_20x20.png" />
                                <?php }else{?>
                                    <span><?=$currNumber?></span>
                                <?php }?>

                            </td>
                            <td class="ToyDetailQuantity"><?=$nc[$currNumber]?></td>
                            <td class="tl"><?=$detailName?></td>
                        </tr>
                        <tr id="response" style="display:none"><td colspan="4"><table class="w100p"></table></td></tr>
                    </table>
                </td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <script>
        var offline = '<?=A2D::$offline?>',
            isLimit = '<?=($percent<1)?TRUE:FALSE;?>',
            callback = '<?=A2D::$callback?>'
            ;

        function clickOnTR(url,tr,type,detail){
            if( !(type==4||type==1) ){
                if( !jQueryA2D(tr).next('.response').hasClass('response') ){
                    var bttn = jQueryA2D(tr).find('span.information');
                    bttnClick(url,bttn,detail);
                }
            }
        }

        function bttnClick(url,bttn,detail){
            jQueryA2D.ajax({
                type: "GET",
                url: url,
                dataType: "json"
            }).done(function( r ){
                ///console.log('Response:'); console.log(r);
                jQueryA2D(bttn).removeAttr('onclick');
                var innerTable = jQueryA2D(bttn).parents('.innerTable'),
                    responseTR = innerTable.find('#response'),
                    response   = responseTR.find('td table'),
                    msg
                ;
                if( !r ){
                    bttn.remove();
                    msg = ""+
                    "<div class='noResponse'>"+
                    "   <span class='red'>Не применяется</span>"+
                    "</div>"+
                    "";
                    response.html( msg );
                    responseTR.addClass('response').show(500);
                }
                else{

                    msg = ""+
                    "<tr>"+
                    "   <th>Номер детали</th>"+
                    "   <th>Кол-во</th>"+
                    "   <th>Производство</th>"+
                    "   <th>Аналог</th>"+
                    "</tr>"+
                    "";

                    jQueryA2D.each( r, function( k, v ){

                        var analog  = v.analog;
                        var aAnalog = analog.split(',');
                        var dAnalog = "";
                        ///console.log(aAnalog.length); console.log(aAnalog);
                        for( var i=0; i<aAnalog.length; i++ ){
                            var num = ( !isLimit ) ?aAnalog[i] :"*******"; //console.log(num);
                            if( num ){
                                if( !offline ){
                                    var callBackLink = createCallBackLink(num,callback);
                                    dAnalog += ""+
                                    "<div id=\"c2cValue_"+ num +"\" class=\"c2cValuePNC\">"+
                                    "   "+ callBackLink +
                                    "   &ensp;<img title='Скопировать' id=\"c2cBttn_"+ num +"\" src=\"/media/images/copy_20x20.png\">"+
                                    "</div>"+
                                    "";
                                }
                                else{
                                    var url = offline+"&number="+num+"&detail="+detail;
                                    dAnalog += "<div id=\""+ num +"\">"+ Offline.Instance().getLink( url, num ) +"</div>";
                                }

                            }
                        }

                        var serial = ( !isLimit ) ?v.serialNumber :"*******";
                        if( !offline ){
                            var callBackLink = createCallBackLink(serial,callback);
                            msg += ""+
                            "<tr>"+
                            "   <td id=\"c2cValue_"+ serial +"\" class=\"c2cValuePNC\">"+
                            "       "+ callBackLink +
                            "       &ensp;<img title='Скопировать' id=\"c2cBttn_"+ serial +"\" src=\"/media/images/copy_20x20.png\">"+
                            "   </td>"+
                            "   <td>"+ v.quantity +"</td>"+
                            "   <td>"+ v.prodaction +"</td>"+
                            "   <td>"+ dAnalog +"</td>"+
                            "</tr>"+
                            "";
                        }
                        else{
                            var url = offline+"&number="+serial+"&detail="+detail;
                            msg += ""+
                            "<tr>" +
                            "   <td id='"+ serial +"'>"+ Offline.Instance().getLink( url, serial ) +"</td>"+
                            "   <td>"+ v.quantity +"</td>"+
                            "   <td>"+ v.prodaction +"</td>"+
                            "   <td>"+ dAnalog +"</td>"+
                            "</tr>"+
                            "";
                        }

                    });

                    response.html( msg );
                    if( !offline ) responseTR.addClass('response').show(500).setC2C('class','c2cValuePNC');
                    else           responseTR.addClass('response').show(500);
                }
            } ).error(function(e){
                ///console.log('Errors:'); console.log(e);
            } ).fail(function(e){
                ///console.log('Fail:'); console.log(e);
            });

        }

        function createCallBackLink(name,callback){
            var r;
            if( callback ){
                var _callBack = callback.replace(/{{DetailNumber}}/g,name);
                r = "<a target='_blank' href='"+_callBack+"'><span class='c2c'>"+name+"</span></a>";
            }
            else{
                r = "<span class='c2c'>"+name+"</span>";
            }
            return r;
        }
    </script>
</div>

</div>