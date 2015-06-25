<?php
use yii\helpers\Url;
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 10.01.15
 * Time: 15:39
 **/
//var_dump($toyota);die;
?>

    <div class="acatalog-block">
    <div>
        <?=  Html::img(\app\modules\catalog\models\ToyotaQuery::getImageUrl().$model['pic_code'].'.png',['height'=>'100px']);?>
    </div>
    <div>
        <?= Html::a(Html::encode($model['desc_en']),
        Url::to(['album',
        'catalog_code' => $model['catalog_code'],
        'catalog' => $model['catalog'],
        'vdate' => (isset($model['vdate']))?$model['vdate']:'',
        'part_group' => $model['part_group'],
        'model_code' => $toyota->model_code,
        ]));?>
    </div>
    </div>





