<?php
use yii\helpers\Url;
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 10.01.15
 * Time: 15:39
 **/

?>

    <div class="acatalog-block">
    <div>
        <?=  Html::img(\app\modules\catalog\models\ToyotaQuery::getImageUrl().$model['pic_code'].'.png',['height'=>'100px']);?>
    </div>
    <div>
        <?= Html::a($model['desc_en'])?>
    </div>
    </div>





