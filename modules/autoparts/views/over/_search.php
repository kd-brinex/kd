<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\autoparts\models\PartOverSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="part-over-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'manufacture') ?>

    <?= $form->field($model, 'price') ?>

    <?= $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'srokmin') ?>

    <?php // echo $form->field($model, 'srokmax') ?>

    <?php // echo $form->field($model, 'lotquantity') ?>

    <?php // echo $form->field($model, 'pricedate') ?>

    <?php // echo $form->field($model, 'skladid') ?>

    <?php // echo $form->field($model, 'sklad') ?>

    <?php // echo $form->field($model, 'flagpostav') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
