<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tovar\models\ParamSearch */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin([
        'action' => ['indexvin'],
        'method' => 'get',
        'options'=>['name' =>'search-vin',],
    ]); ?>
    <div class="row">
<div class="col-xs-10">
    <?= Html::input('text', 'vin',(isset($params['vin']))?$params['vin']:'',['class'=>'form-control','placeholder'=>'JTJBT20X740046047'] ) ?>
</div>
    <?= Html::submitButton('Поиск по VIN', ['class' => 'col-xs-2 btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

