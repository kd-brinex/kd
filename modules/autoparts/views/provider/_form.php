<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\autoparts\models\PartProvider */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="part-provider-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'enable')->checkbox() ?>

    <?= $form->field($model, 'cross')->checkbox() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'weight')->textInput([]) ?>

    <?= $form->field($model, 'flagpostav')->textInput([]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
