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
        <?= Html::img( $model['img'] ); ?>
        <?= Html::a(Html::encode(Yii::t('autocatalog',$model['name']))
//            .(!empty($model->compatibility)?$model->compatibility:'')
            ,Url::to($model['url'].'/'.$params['option']));?>

</div>





