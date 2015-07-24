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
    <div class="col-xs-12 col-md-4">
<div class="row form-vin">

    <?= Html::tag('h3','Поиск автомобиля по VIN')?>
    <?= Html::input('text', 'vin',(isset($params['vin']))?$params['vin']:'',['class'=>'form-control','placeholder'=>'Введите VIN. Например:JTJBT20X740046047'] ) ?>
    <?= Html::input('hidden', 'user_id',(isset($params['user_id']))?$params['user_id']:'',[] ) ?>
    <?= Html::submitButton('Искать по VIN', ['class' => 'col-xs-12 btn btn-primary']) ?>
</div>

    </div>
    <?php ActiveForm::end(); ?>

