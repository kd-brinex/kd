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

<?=ListView::widget([
    'summary'=>'Количество позиций: {count}',
    'dataProvider' =>$model,
//    'showHeader' => false,
    'options'=>['tag'=>'table','class'=>'offer-v3-table'],
//    'itemOptions' => ['tag'=>'tr'],

    'itemView' => function ($model){return $this->render('tovars_block_view', ['model' => $model]);},
]);
echo '<div class="basket-itogo">Сумма к оплате: '.$itogo['tovar_summa'].'</div>';
?>
