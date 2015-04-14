<?php
//header('Access-Control-Allow-Origin: *');
//
///** Обязательно к применению */
//include "../_lib.php"; /// После подключения доступен класс A2D
//include "api.php";     /// После подключения доступен класс ADCAPI
//
///// Устанавливаем объект $oA2D - объект для работы с АвтоКаталогом
//$oA2D = ADCAPI::instance();
//
///// Сперва проверим на ошибки
//if( ($aErrors=A2D::property($vMapImg,'errors')) ) $oA2D->error($aErrors,404);

/// Получаем переменные из своего окружения
//$sTreeID  = $oA2D->rcv('treeID');
//$sModelID = $oA2D->rcv('modelID');
//$bBrowser = $oA2D->rcv('browser');
//$sJumpPic = $oA2D->rcv('jumpPic');
$sTreeID  = $params['treeID'];
$sModelID = $params['modelID'];
$bBrowser = (isset($params['browser']))?$params['browser']:"";
$sJumpPic = (isset($params['jumppic']))?$params['jumppic']:"";

/// Получаем данные для построения иллюстрации и списка номенклатуры
$vMapImg  = $oAdcpi->getDetails($sModelID,$sTreeID,$sJumpPic);

/// В ответ вернулся объект с такими свойствами:
$sTypeID    = $oA2D->property($vMapImg,'typeID');    /// Идентификатор группы
$sTypeName  = $oA2D->property($vMapImg,'typeName');  /// Имя группы
$sMarkID    = $oA2D->property($vMapImg,'markID');    /// Идентификатор марки
$sMarkName  = $oA2D->property($vMapImg,'markName');  /// Имя марки
$sModelName = $oA2D->property($vMapImg,'modelName'); /// Имя модели
$sTreeName  = $oA2D->property($vMapImg,'treeName');  /// Имя узла (двигатель, рулевое управление, кузов)
$sMapName   = $oA2D->property($vMapImg,'mapName');   /// Имя выбранной детали
$sMapNameTree = ((strlen($sMapName)>43)?substr($sMapName, 0, 40)."...":$sMapName); /// Сокращение имени выбранной модели для последней крошки
$mapImg     = $oA2D->property($vMapImg,'mapImg');    /// Иллюстрация детали с позициями элементов
$aDetails   = $oA2D->property($vMapImg,'details');   /// Номенклатура к иллюстрации

$aNav       = $oA2D->property($vMapImg,'nav');       /// Навигации - предыдущая и следующая деталь
$_prev      = $oA2D->property($aNav,'prev');         /// предыдущая
$_next      = $oA2D->property($aNav,'next');         /// следующая

$bMultiArray = 0; /// Нужно для крошек, чтобы при переходе не получить другой массив. Хотя отсутсвие и означает FALSE/0 - для понимания

$this->params['breadcrumbs'][] = ['label'=>'Каталог','url'=>['/auto']];
$this->params['breadcrumbs'][] = ['label'=>$sTypeName,'url'=>['/auto/marks/'.$sTypeID]];
$this->params['breadcrumbs'][] = ['label'=>$sMarkName,'url'=>['/auto/models/'.$sTypeID.'_'.$sMarkID]];
$this->params['breadcrumbs'][] = ['label'=>$sModelName,'url'=>['/auto/tree/'.$sModelID]];
$this->params['breadcrumbs'][] = ['label'=>$sMapName];

Yii::$app->view->registerCssFile('/assets/auto/css/style.css');
Yii::$app->view->registerCssFile('/assets/auto/css/adc.css');
?>

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
            <a href="?modelID=<?=$sModelID?>&treeID=<?=$_prev->id?>">
                <span class="pointer" title="<?=$_prev->tree_name?>">&larr;</span>
            </a>
        <?php }else{?>
            <span>&larr;</span>
        <?php }?>
        &nbsp;
        <span>Запчасти</span>
        &nbsp;
        <?php if( $_next ){?>
            <a href="?modelID=<?=$sModelID?>&treeID=<?=$_next->id?>">
                <span class="pointer" title="<?=$_next->tree_name?>">&rarr;</span>
            </a>
        <?php }else{?>
            <span>&rarr;</span>
        <?php }?>
    </div><!--/Nav-->
    <div class="clear"></div>

    <!--List-->
    <table border="0" align="center" width="100%" cellpadding="2" cellspacing="1" class="brd">
        <tr bgcolor=LightSteelBlue>
            <td align="center" width="5%"><B>N</B></td>
            <td align="center" width="45%"><B>Наименование</B></td>
            <td align="center" width="30%"><B>Номер</B></td>
            <td align="center" width="20%"></td>
        </tr>
        <?php foreach( $aDetails as $sDetail ){
            ?>
            <tr id="tr<?=$sDetail->detail_id?>" data-position="<?=$sDetail->detail_pos?>">
            <td align="right" id="detailInfo"><?=($sDetail->detail_inc)?$sDetail->detail_inc.'.':''?></td>
            <td>
                <a href="#" onclick="return td(<?=$sDetail->detail_id?>,1,<?=$sDetail->detail_pos?>);" title="more">
                    <?=$sDetail->detail_name?>
                </a>
            </td>
            <td align="center">
                <a href="#" onclick="return td(<?=$sDetail->detail_id?>,1,<?=$sDetail->detail_pos?>);" title="more">
                    <?=$sDetail->detail_num?>
                </a>
            </td>
            <td align="center">
                <a target="_blank" href="/finddetails?article=<?=$sDetail->detail_num?>">Узнать цену</a>
            </td>
        </tr>
        <?php }?>
    </table>

</div>

</div>
