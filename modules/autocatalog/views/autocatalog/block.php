<?php
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 10.01.15
 * Time: 15:39
 **/
//var_dump($model);die;
?>

<div class="acatalog-block">
        <?= Html::img( $model['img'] ); ?>
        <?= Html::a(Html::encode(Yii::t('autocatalog',$model['name'])),Url::to($model['url']));?>
</div>





