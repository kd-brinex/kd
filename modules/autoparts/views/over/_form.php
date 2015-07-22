<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\autoparts\models\PartOver */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="part-over-form">

    <br><br><br>
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'code')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'manufacture')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'srokmin')->textInput() ?>

    <?= $form->field($model, 'srokmax')->textInput() ?>

    <?= $form->field($model, 'lotquantity')->textInput() ?>

    <?= $form->field($model, 'skladid')->textInput() ?>

    <?= $form->field($model, 'sklad')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'flagpostav')->dropDownList($flag_postav_list, []) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
