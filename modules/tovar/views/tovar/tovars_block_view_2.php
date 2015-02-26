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
//var_dump($model);?>
<li>
    <div class="offer-v2-item borders-lite">
        <div class="offer-v2-img">
            <a href="<?=url::toRoute(['view','id'=>$model->id],true)?>">
                <div class="offer-v2-img-bg" style="background-image: url(<?=$model->Image?>);"></div>
            </a>
        </div>

        <div class="offer-v2-info">
            <h3><a href="<?=url::toRoute(['view','id'=>$model->id],true)?>"><?=$model->name?></a></h3>
            <div class="offer-v2-code">Код: <?=$model->id?></div>
            <div class="offer-v2-spec">&nbsp;&nbsp; Стеклоомывающая жидкость, которая эффективно очищает боковое&nbsp;,лобовое, задние стекла, а также фары автомобиля. ...</div>

            <div class="offer-v2-deliv">
                <table class="table page-table-deliv">
                    <tbody><tr>
                        <td class="page-table-deliv-1"><?=$model->srok?></td>

                        <td class="page-table-deliv-2">В магазине<br><span>8</span></td>
                        <td class="page-table-deliv-2">На складе<br><span>8</span></td>
                    </tr>
                    </tbody></table>
            </div>

        </div>

        <div class="offer-v2-order">
            <div class="offer-v2-price-name">Цена</div>
            <div class="offer-v2-price-new"><?=$model->price?> р.</div>
            <div class="offer-v2-bonus"><a href="/samara/buyer/program-ball/" target="_blank" title="Количество начисляемых баллов. Баллы начисляются при покупке товара через сайт! Начисленные баллы становятся активными по истечении 14 дней с момента покупки.">
                    <img src="http://kolesa-darom.ru/img2/goods-bonuspoint.png"> +10</a></div>

            <a class="btn btn-warning" href="javascript:put('<?=$model->id?>');">
                <i class="icon-shopping-cart icon-white"></i> Заказать</a>
<!--            <input type="hidden" id="47483" class="basket-cnt" size="4" value="0" onchange="basketInputControlActivate('47483',6594)">-->
<!--            <span class="basket-price" id="47483Controls"></span>-->
<!--            <div class="basket-price" id="47483Price">130</div>-->
<!--            <div class="basket-price" id="47483Sel">23</div>-->

        </div>
        <div class="clr"></div>
    </div>
</li>


