<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 10.08.15
 * Time: 16:41
 */
use yii\helpers\Html;
var_dump($model);
?>
<div class="col-xs-12 col-md-3">
    <?= Html::img($model['image_path'].'/'.$model['image'].'.png',['width'=>'99%'])?>
    <?=Html::tag('div',$model['catalog_code'])?>
    <?=Html::tag('div',$model['catalog_name'])?>
    <?=Html::tag('div','Модельный ряд: <b>'.$model['model_name'].'</b>')?>
    <?=Html::tag('div','Регион: <b>'.$model['region'].'</b>')?>
    <?=Html::tag('div','Тип автомобиля: <b>'.$model['vehicle_type'].'</b>')?>
    <?=Html::tag('div','Дата производства: <b>'.$model['date_start'].'-'.$model['date_end'].'</b>')?>
</div>
