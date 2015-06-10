<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\autoparts\models\PartProviderSrok */
/* @var $form ActiveForm */
?>
<div class="modules-autoparts-views-providersrok-_form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'provider_id') ?>
        <?= $form->field($model, 'city_id') ?>
        <?= $form->field($model, 'days') ?>
        <?= $form->field($model, 'id') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- modules-autoparts-views-providersrok-_form -->
