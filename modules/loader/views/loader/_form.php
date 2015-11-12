<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\loader\models\Loader */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="loader-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'blob_data')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
