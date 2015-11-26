<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\seotools\models\InfotextSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="infotext-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'meta_id') ?>

    <?= $form->field($model, 'city_id') ?>

    <?= $form->field($model, 'infotext_before') ?>

    <?= $form->field($model, 'infotext_after') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('seotools', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('seotools', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
