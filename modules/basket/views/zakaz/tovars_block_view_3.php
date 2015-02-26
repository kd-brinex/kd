<?php
use yii\helpers\Url;
use yii\helpers\Html;

//use yii\helpers\Html5;

Yii::$app->view->registerCssFile('/css/style-offer.css');?>
<tr id="<?=$model->tovar_id?>_offer">
    <td class="offer-v3-name">
        <div class="offer-v3-code"><?=$model->tovar_id?></div>
        <h3><a href="<?=url::toRoute(['view','id'=>$model->tovar_id],true)?>"><?=$model->tovarname?></a></h3>
    </td>
    <td class="offer-v3-store"><?=HTML::input('number','tovar_count',$model->tovar_count,['size'=>'3','min'=>1,'max'=>10,'id'=>$model->tovar_id,'onchange'=>'count(this)'])?></td>

<!--    <td class="offer-v3-stock">8</td>-->
    <td class="offer-v3-price" id="<?=$model->tovar_id?>_price"?><?=$model->tovar_price?></td>
<td class="offer-v3-price" id="<?=$model->tovar_id?>_summa"?><?=$model->tovar_price*$model->tovar_count?></td>
    <td class="offer-v3-order"  onclick="del(this)">
        <div class="btn btn-warning" tovar_id="<?=$model->tovar_id?>"><i class="icon-delete"></i></div>

    </td>

</tr>
