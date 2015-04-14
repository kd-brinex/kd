<?php
use yii\helpers\Url;
use yii\helpers\Html;

//use yii\helpers\Html5;

?>
<div id="<?= $model->tovar_id ?>_offer">
    <div class="col-xs-12 col-lg-6 col-md-5 col-sm-5">
        <div class="offer-v3-code"><?= $model->tovar_id ?></div>

            <a href="<?= url::toRoute(['/tovar/tovar/view', 'id' => $model->tovar_id], true) ?>"><?= $model->tovarname ?></a>

    </div>
    <div class="col-xs-5 col-lg-1 col-md-2 col-sm-2" ><?= HTML::input('number', 'tovar_count', $model->tovar_count, ['size' => '5', 'min' => 1, 'max' => 10, 'id' => $model->tovar_id, 'onchange' => 'count(this)']) ?></div>
    <div class="hidden-xs col-lg-2 col-md-2 col-sm-2" id="<?= $model->tovar_id ?>_price" ?><?= Yii::$app->formatter->asCurrency($model->tovar_price) ?></div>
    <div class="col-xs-5 col-lg-2 col-md-2 col-sm-2" id="<?= $model->tovar_id ?>_summa" ?><?= Yii::$app->formatter->asCurrency($model->tovar_summa) ?></div>

    <div class="col-xs-2 col-lg-1 col-md-1 col-sm-1" onclick="del(this)">
        <div class="btn btn-warning" tovar_id="<?= $model->tovar_id ?>"><i class="icon-delete"></i></div>

    </div>

</div>
<hr align="center" width="70%" color="#337ab7" >