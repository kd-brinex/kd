<?php
use yii\helpers\Url;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 10.01.15
 * Time: 15:39
 *//*
echo yii\widgets\DetailView::widget([
    'model'=>$model,
//    'template'=>'<tr><th>{label}</th><td>{value}</td></tr>',
    'template'=>'<div>{value}</div>',
    'class' => 'item item item item',
    'attributes' => [
        [
            'attribute'=>'image',
            'value'=>$model->image,
            'format' => ['image',['width'=>'150','height'=>'150']],
            'class'=>'offer-v1-img',
        ],
        [
            'attribute'=>'name',
            'value'=>url::toRoute(['view','id'=>$model->id],true),
            'format' => ['url'],
        ],
        'name',

        'price'
    ]]);

*/
//var_dump($model);die;?>
<!--<table class="table offer-v3-table">-->
<!--    <tr>-->
<!--        <th>Наименование товара</th>-->
<!--        <th>В магазине</th><th>На складе</th>-->
<!--        <th>Цена</th>-->
<!--        <th>Заказать</th>-->
<!--    </tr>-->
    <td class="offer-v3-name">
        <div class="offer-v3-code">Код: <?=$model->id?></div>
        <h3 style=";margin:3px;margin-top:5px"><a href="<?=url::toRoute(['view','id'=>$model->id],true)?>"><?=$model->name?></a></h3>
        <div class="offer-v3-srok">
            <?= $model->srok ?>
        </div>
    </td>
    <td class="offer-v3-store"><?=$model->count?></td>
    <td class="offer-v3-stock"><?=$model->count?></td>
    <td class="offer-v3-price"><?=$model->asCurrency($model->price)?></td>
    <td class="offer-v3-order">
        <?=$this->render('btn_basket', ['model' => $model,'viewtype'=>3]);?>
    </td>

<!--</table>-->

