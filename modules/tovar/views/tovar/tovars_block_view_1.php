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

<div class="offer-v1-item borders-lite">
<div class="offer-v1-code">Код: <?=$model->category_id?>/<?=$model->id?></div>
<div class="offer-v1-img">
<a href="<?=url::toRoute(['view','id'=>$model->id],true)?>">
<div class="offer-v1-img-bg" style="background-image: url(<?=$model->image?>);"></div>
</a>
</div>
<h3><a href="<?=url::toRoute(['view','id'=>$model->id],true)?>"><?= $model->name?></a></h3>

<div class="offer-v1-deliv">
<?=$model->srok?>
</div>
<div class="offer-v1-bonus">
    <a href="/samara/buyer/program-ball/" target="_blank" title="Количество начисляемых баллов. Баллы начисляются при покупке товара через сайт! Начисленные баллы становятся активными по истечении 14 дней с момента покупки.">
        <img src="http://kolesa-darom.ru/img2/goods-bonuspoint.png"> +<?=$model->ball?></a></div>
<div class="offer-v1-price">
<div class="offer-v1-price-new"><?=$model->price?></div>
</div>
<div class="clr"></div>
<div class="offer-v1-order">
<a class="btn btn-warning" href="javascript:basketControlActivate1(48437,1);">
    <i class="icon-shopping-cart icon-white"></i> Заказать</a>
<!--    <input type="hidden" id="48437" class="basket-cnt" size="4" value="0" onchange="basketInputControlActivate('48437',105)">-->
<!--    <span class="basket-price" id="48437Controls"></span>-->
<!--    <div class="basket-price" id="48437Price">659</div>-->
<!--    <div class="basket-price" id="48437Sel">23</div>-->
</div>
</div>
