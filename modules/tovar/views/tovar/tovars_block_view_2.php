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
//var_dump($model->inbasket);die;?>

<!--    <div class="col-xs-12">-->
        <div class="col-xs-6">
            <a href="<?=url::toRoute(['view','id'=>$model->id],true)?>">
                 <img src="<?=$model->Image?>">
            </a>
        </div>
        <div class="col-xs-6">
            <div class="col-xs-6 offer-v2-code">Код: <?= $model->id ?></div>
            <div class="col-xs-6 offer-v2-bonus"><a href="/samara/buyer/program-ball/" target="_blank" title="Количество начисляемых баллов. Баллы начисляются при покупке товара через сайт! Начисленные баллы становятся активными по истечении 14 дней с момента покупки.">
                    <img src="http://kolesa-darom.ru/img2/goods-bonuspoint.png"> +10</a></div>
<!--            <div class="offer-v2-price-name">Цена</div>-->
            <div class="offer-v2-price-new"><?=$model->asCurrency($model->price)?></div>

            <?=$this->render('btn_basket', ['model' => $model,'viewtype'=>2]);?>


        </div>
        <div class="col-xs-12">
            <h3><a href="<?=url::toRoute(['view','id'=>$model->id],true)?>"><?=$model->name?></a></h3>

            <div><?=$model->description?></div>

<!--            <div class="col-xs-12 ">-->
                <div>

                        <div class="col-xs-4"><?=$model->srok?></div>

                        <div class="col-xs-4">В магазине<br><span><?=$model->count?></span></div>
                        <div class="col-xs-4">На складе<br><span><?=$model->count?></span></div>
                </div>
<!--            </div>-->

        </div>



<!--    </div>-->
<hr align="center" width="100%" color="#337ab7" >


