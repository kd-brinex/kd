<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tovar\models\ParamSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => ['name' => 'search-vin',],
]); ?>
<div class="row">
    <div class="col-md-10">
        <?= Html::input('text', 'vin', (isset($params['vin'])) ? $params['vin'] : '', ['class' => 'form-control', 'placeholder' => 'Введите VIN. Например:JTJBT20X740046047']) ?>
    </div>
    <?= Html::input('hidden', 'user_id', (isset($params['user_id'])) ? $params['user_id'] : '', []) ?>
    <div class="col-md-1">
        <?= Html::submitButton('Искать по VIN', ['class' => ' btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

