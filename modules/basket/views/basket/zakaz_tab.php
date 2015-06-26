<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 26.02.15
 * Time: 10:20
 */
use yii\widgets\ListView;
Yii::$app->view->registerCssFile('/css/style-offer.css');
?>
<div class="col-xs-12 col-lg-9 col-md-12 col-sm-12">
<?=ListView::widget([
    'summary'=>'Количество позиций: {count}',
    'dataProvider' =>$model,
//    'showHeader' => false,
    'options'=>['tag'=>'table','class'=>'offer-v3-table'],
//    'itemOptions' => ['tag'=>'tr'],

    'itemView' => function ($model){return $this->render('tovars_block_view', ['model' => $model]);},
]);?>
</div>
<div class="visible-lg col-lg-3 bg-info">Рекламный блок</div>
<?= '<div class="col-xs-12 basket-itogo">Сумма к оплате: '.Yii::$app->formatter->asCurrency($itogo['tovar_summa']).'</div>';?>

