<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 26.02.15
 * Time: 10:20
 */
use yii\widgets\ListView;

//var_dump($model);die;
?>

<?=\yii\widgets\DetailView::widget([
//    'summary'=>'Количество позиций: {count}',
    'model' =>$model,
    'attributes' => [
        'username',
        'email',
        'registration_ip',
        'created_at'
    ]
//    'showHeader' => false,
//    'options'=>['tag'=>'table','class'=>'offer-v3-table'],
//    'itemOptions' => ['tag'=>'tr'],

//    'itemView' => function ($model){return $this->render('klient_block_view', ['model' => $model]);},
]);
//echo '<div class="basket-itogo">Сумма к оплате: '.$itogo['tovar_summa'].'</div>';
?>
