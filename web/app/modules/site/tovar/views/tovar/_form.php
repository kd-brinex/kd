<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\site\tovar\models\Tovar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tovar-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => 9]) ?>

    <?= $form->field($model, 'tip_id')->textInput(['maxlength' => 25]) ?>

    <?= $form->field($model, 'category_id')->textInput(['maxlength' => 9]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 200]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
