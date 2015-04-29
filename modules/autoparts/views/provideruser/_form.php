<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\autoparts\models\PartProviderUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="part-provider-user-form">

    <?php $form = ActiveForm::begin();

    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 200]) ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => 15]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 15]) ?>

    <?= $form->field($model, 'store_id')->textInput() ?>

    <?= $form->field($model, 'provider_id')->dropDownList($model->getProviderDD(),[]) ?>

    <?= $form->field($model, 'marga')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
