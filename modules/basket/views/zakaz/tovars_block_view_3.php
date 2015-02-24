<?php
use yii\helpers\Url;
use yii\helpers\Html;
//use yii\helpers\Html5;

Yii::$app->view->registerCssFile('/css/style-offer.css');
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

    <td class="offer-v3-name">
        <div class="offer-v3-code"><?=$model->tovar_id?></div>
        <h3><a href="<?=url::toRoute(['view','id'=>$model->tovar_id],true)?>"><?=$model->tovarname?></a></h3>
    </td>
    <td class="offer-v3-store"><?=HTML::input('text','tovar_count',$model->tovar_count,['size'=>'3'])?></td>

<!--    <td class="offer-v3-stock">8</td>-->
    <td class="offer-v3-price"><?=$model->tovar_price?></td>
    <td class="offer-v3-order">
        <a class="btn btn-warning" href="javascript:put('');"><i class="icon-shopping-cart icon-white"></i></a>
<!--        <input type="hidden" id="47483" class="basket-cnt" size="4" value="0" onchange="basketInputControlActivate('47483',6444)">-->
<!--        <span class="basket-price" id="47483Controls"></span>-->
<!--        <div class="basket-price" id="47483Price">130</div>-->
<!--        <div class="basket-price" id="47483Sel">23</div>-->
    </td>


