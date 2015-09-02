<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tovar\models\ParamSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'action' => ['details'],
    'method' => 'get',
    'options' => ['name' => 'search-vin',],
]); ?>
<div class="row">
    <div class="col-md-10">
        <?= Html::input('text', 'article', (isset($params['article'])) ? $params['article'] : '', ['class' => 'form-control', 'placeholder' => 'Введите артикул детали по каталогу']) ?>
    </div>
    <div class="col-md-1">
        <?= Html::submitButton('Искать запчасть', ['class' => ' btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

