<?php
use kartik\grid\GridView;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.10.15
 * Time: 11:22
 */

//var_dump($provider);die;
?>
<div class="catalog">
<?= GridView::widget([
    'dataProvider'=>$provider,
    'showHeader' => false,
    'layout' =>"{items}\n{pager}",
    'bootstrap' =>false,
    'columns' =>[
      [
          'attribute'=>'family',
          'format'=>'raw',
          'value'=>function ($model, $key, $index, $widget) {
    return \yii\helpers\Html::a($model['family'],\yii\helpers\Url::to($model['marka'].'/'.$model['family']));
},]
    ],

]);?>
    </div>