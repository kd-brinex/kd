<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\file_upload\ImgImageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="img-image-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'table') ?>

    <?= $form->field($model, 'table_id') ?>

    <?= $form->field($model, 'src') ?>

    <?= $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'alt') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
