<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 22.06.15
 * Time: 15:04
 */
?>

<?php $form = ActiveForm::begin([
    'action' => ['indexframe'],
    'method' => 'get',
    'options' => ['name' => 'search-frame'],
]); ?>

    <div class="col-xs-12 col-md-4">
        <div class="row form-vin">
            <?= Html::tag('h3','Поиск автомобиля по frame')?>
            <div class="row">
            <div class="col-xs-6">
                <?= Html::input('text', 'frame', (isset($params['frame'])) ? $params['frame'] : '', ['class' => 'form-control', 'placeholder' => 'AE110']) ?>
            </div>
            <div class="col-xs-6">
                <?= Html::input('text', 'number', (isset($params['number'])) ? $params['number'] : '', ['class' => 'form-control', 'placeholder' => '5014465']) ?>
            </div>
                </div>

            <?= Html::input('hidden', 'user_id', (isset($params['user_id'])) ? $params['user_id'] : '', []) ?>
            <div class="row">
                <div class="col-xs-12">
                <?= Html::submitButton('Искать по Frame', ['class' => 'col-xs-12 btn btn-primary']) ?>
            </div>
                </div>

        </div>

    </div>

<?php ActiveForm::end(); ?>