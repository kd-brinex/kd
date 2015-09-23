<?php  header('Access-Control-Allow-Origin: *');

/** Обязательно к применению */
include "../_lib.php"; /// После подключения доступен класс A2D
include "api.php";     /// После подключения доступен класс ADC

/// Устанавливаем объект $oA2D - объект для работы с АвтоКаталогом
$oA2D = ADC::instance();

/// Получаем переменные из своего окружения
$sTreeID  = $oA2D->rcv('treeID');
$sModelID = $oA2D->rcv('modelID');
$bBrowser = $oA2D->rcv('browser');
$sJumpPic = $oA2D->rcv('jumpPic');

/// Получаем данные для построения иллюстрации и списка номенклатуры
$vMapImg  = $oA2D->getDetails($sModelID,$sTreeID,$sJumpPic);
/// Сперва проверим на ошибки
if( ($aErrors=A2D::property($vMapImg,'errors')) ) $oA2D->error($aErrors,404);

/// В ответ вернулся объект с такими свойствами:
$sTypeID    = A2D::property($vMapImg,'typeID');    /// Идентификатор группы
$sTypeName  = A2D::property($vMapImg,'typeName');  /// Имя группы
$sMarkID    = A2D::property($vMapImg,'markID');    /// Идентификатор марки
$sMarkName  = A2D::property($vMapImg,'markName');  /// Имя марки
$sModelName = A2D::property($vMapImg,'modelName'); /// Имя модели
$sTreeName  = A2D::property($vMapImg,'treeName');  /// Имя узла (двигатель, рулевое управление, кузов)
$sMapName   = A2D::property($vMapImg,'mapName');   /// Имя выбранной детали
$sMapNameTree = ((strlen($sMapName)>43)?substr($sMapName, 0, 40)."...":$sMapName); /// Сокращение имени выбранной модели для последней крошки
$mapImg     = A2D::property($vMapImg,'mapImg');    /// Иллюстрация детали с позициями элементов
$aDetails   = A2D::property($vMapImg,'details');   /// Номенклатура к иллюстрации

$aNav       = A2D::property($vMapImg,'nav');       /// Навигации - предыдущая и следующая деталь
$_prev      = A2D::property($aNav,'prev');         /// предыдущая
$_next      = A2D::property($aNav,'next');         /// следующая

$bMultiArray = 0; /// Нужно для крошек, чтобы при переходе не получить другой массив. Хотя отсутсвие и означает FALSE/0 - для понимания

/// Включаем интерфейс для ограничения поиска, по умолчанию активен поиск в заданных пределах ниже:
A2D::$searchWhere['tabs']  = ['detail'];                        /// В какой вкладке включить
A2D::$searchWhere['name']  = "model";                           /// Поиск в пределах модели (либо марки)
A2D::$searchWhere['value'] = $sModelID;                         /// Идентификатор модели
A2D::$searchWhere['desc']  = "искать в $sMarkName $sModelName"; /// Наименования чекбокса, второй "искать везде"
A2D::$searchWhere['hide']  = "_displayNone";                    /// Если нужен только ограниченный поиск, то прописываем класс для скрытие чекбоксов
?>

<meta charset="utf-8">
<link href="../media/css/fw.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/style.css" media="all" rel="stylesheet" type="text/css">
<link href="../media/css/adc.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>


<?php /// Описано в примере №5 в adc/README.MD \\\ ?>
<!--<script>
    exLightsTR = function(id){
        console.debug(id);
        return false;
    };
</script>-->

<!--<script>
   function td(id){
        console.debug(id);
        return false;
    }
</script>-->

<div id="AutoDealer">

    <div id="catalogBreads">
        <?php /// Вместо того чтобы расширать, т.к. это пока единственный случай, мы можем самостоятельно создать крошки как нам нужно \\\ ?>
        <a href="../types.php">Техника</a>
        &nbsp;&raquo;&nbsp;
        <a href="../marks.php?markID=<?=$sMarkID?>&typeID=<?=$sTypeID?>"><?=$sTypeName?></a>
        &nbsp;&raquo;&nbsp;
        <a href="models.php?markID=<?=$sMarkID?>&typeID=<?=$sTypeID?>"><?=$sMarkName?></a>
        &nbsp;&raquo;&nbsp;
        <a href="tree.php?modelID=<?=$sModelID?>&multiArray=<?=$bMultiArray?>"><?=$sModelName?></a>
        &nbsp;&raquo;&nbsp;
        <a href="tree.php?modelID=<?=$sModelID?>&multiArray=<?=$bMultiArray?>#<?=$sTreeID?>"><?=$sTreeName?></a>
        &nbsp;&raquo;&nbsp;
        <span><?=$sMapNameTree?></span>
    </div>
    <?php include WWW_ROOT."helpers/search.php"; /// Подключаем форму поиска?>

    <div id="map">
        <h1 id="pic">Карта размещения деталей &laquo;<?=$sMapName?>&raquo;</h1>
        <div id="iframe">
            <div id="imageFrame"><?=$mapImg?></div>
            <!--Nav-->
            <div id="zoomer">
                <div class="ml20">
                    <INPUT type="checkbox" checked onclick="showlabels(this.checked);" value="1" style="vertical-align:middle;" id="cl1" title="hide-show">
                    <label title="hide-show" for="cl1">метки</label>&nbsp;
                    <B style="vertical-align:middle">Масштаб: </B>
                    <input type="text" readonly style="vertical-align:middle;width:40px;font-size:10pt;height:16px;background: transparent; border: 0px #000000 Solid;" id="map_info" value="100%">
                    <span class="zoomBttn" onclick="izoom(-1);" title="-Zoom-">-</span>&nbsp;
                    <span class="zoomBttn" onclick="izoom(0);" title="=Zoom=">100%</span>&nbsp;
                    <span class="zoomBttn" onclick="izoom(1);" title="+Zoom+">+</span>
                </div>
            </div>
            <!--/Nav-->
        </div>
    </div>

    <div id="nav">
        <?php if( $_prev ){?>
            <a href="map.php?modelID=<?=$sModelID?>&treeID=<?=$_prev->id?>">
                <span class="pointer" title="<?=$_prev->tree_name?>">&larr;</span>
            </a>
        <?php }else{?>
            <span>&larr;</span>
        <?php }?>
        &nbsp;
        <span>Запчасти</span>
        &nbsp;
        <?php if( $_next ){?>
            <a href="map.php?modelID=<?=$sModelID?>&treeID=<?=$_next->id?>">
                <span class="pointer" title="<?=$_next->tree_name?>">&rarr;</span>
            </a>
        <?php }else{?>
            <span>&rarr;</span>
        <?php }?>
    </div><!--/Nav-->
    <div class="clear"></div>

    <!--List-->
    <table id="detailsList" border="0" align="center" width="100%" cellpadding="2" cellspacing="1" class="brd">
        <tr bgcolor=LightSteelBlue>
            <td align="center" width="3%"><B>N</B></td>
            <td align="center" width="3%"></td>
            <td align="center" width="45%"><B>Наименование</B></td>
            <td align="center" width="30%"><B>Номер</B></td>
            <td></td>
        </tr>
        <?php foreach( $aDetails as $sDetail ){?>
            <tr id="tr<?=$sDetail->detail_id?>" data-position="<?=$sDetail->detail_pos?>">
            <td align="right" id="detailInfo"><?=($sDetail->detail_inc)?$sDetail->detail_inc.'.':''?></td>
            <td align="center">
                <a title="more" class="detailInfo" onclick="_f('model=<?=$sModelID?>&tree=<?=$sDetail->detail_id?>');return false;">
                    i
                </a>
            </td>
            <td>
                <a href="#" onclick="return td(<?=$sDetail->detail_id?>,1,<?=$sDetail->detail_pos?>);" title="more">
                    <?=$sDetail->detail_name?>
                </a>
            </td>
            <td>
                <a href="#" onclick="return td(<?=$sDetail->detail_id?>,1,<?=$sDetail->detail_pos?>);" title="more">
                    <?=$sDetail->detail_num?>
                </a>
            </td>
            <td>
                <a target="_blank" href="/autocatalog/autocatalog/details?article=<?=$sDetail->detail_num?>">Узнать цену</a>
            </td>
        </tr>
        <?php }?>
    </table>

</div>




<!--For HTML-->
<!--PopUp-->
<div id="frameOverlay" style="z-index:3;background-color:black;visibility:hidden;position:fixed;width:100%;height:100%;top:0;left:0;opacity:0.8;" class="opacity"></div>
<div id="_shadow" style="z-index:5;background-color:#224466;visibility:hidden;position:fixed;width:470px;height:360px;top:25%;margin-left:6px;margin-top:6px;" class="opacity"></div>
<div id="_iframe" style="z-index:7;visibility:hidden;position:fixed;width:470px;height:360px;top:25%">
<iframe id="infoFrame" style="border:1px SteelBlue Solid;width:470px;height:360px;" frameborder="no" scrolling="no"></iframe>
</div>
<!--JS For PopUp-->
<script>
    <!--
    /// Close Frame
    function fc(){
        if(document.getElementById("_iframe")){
            document.getElementById("_iframe").firstChild.src="about:blank";
            document.getElementById("_iframe").style.visibility="hidden";
        }
        if(document.getElementById("_shadow")) document.getElementById("_shadow").style.visibility="hidden";
        if(document.getElementById("frameOverlay")) document.getElementById("frameOverlay").style.visibility="hidden";
        return true;
    }
    /// Get Data for Frame
    function _f(_m){
        $frameOverlay = document.getElementById("frameOverlay");
        if($frameOverlay){
            $frameOverlay.style.visibility="visible";
        }
        _top  = (window.innerHeight-300)/2 - 100;
        _left = (window.innerWidth-470)/2;
        $backGround = document.getElementById("_shadow");
        if($backGround){
            $backGround.style.visibility="visible";
            $backGround.style.top=_top+7;
            $backGround.style.left=_left+7;
        };
        var $divFrame = document.getElementById("_iframe");
        var $infoFrame = document.getElementById("infoFrame");
        if($divFrame){
            $infoFrame.src="detailInfo.php?"+_m;
            $divFrame.style.visibility="visible";
            $divFrame.style.top=_top;
            $divFrame.style.left=_left;
        }
        return false;
    }
//-->
</script><!--/script-->