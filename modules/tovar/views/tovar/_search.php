<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 19.08.15
 * Time: 16:33
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$form = ActiveForm::begin([
    'action' => ['finddetails'],
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