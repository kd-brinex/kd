<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\seotools\models\base\Infotext */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="infotext-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'meta_id')->textInput() ?>

    <?php echo $form->field($model, 'city_id')
        ->label(Yii::t('seotools', 'City'))
        ->widget(Select2::classname(), [
            'data' => ArrayHelper::map($city_list, 'id', 'name'),
            'value' => $model->city_id,
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => ['placeholder' => 'Select a city ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>

    <?= $form->field($model, 'infotext_before')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'infotext_after')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('seotools', 'Create') : Yii::t('seotools', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ,'data-pjax'=>1]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
