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
    'options'=>['name' =>'search-frame'],
]); ?>

    <div class="row">
        <div class="col-xs-5">
            <?= Html::input('text', 'frame',(isset($params['frame']))?$params['frame']:'',['class'=>'form-control','placeholder'=>'AE110'] ) ?>
        </div>
        <div class="col-xs-5">
            <?= Html::input('text', 'number',(isset($params['number']))?$params['number']:'',['class'=>'form-control','placeholder'=>'5014465'] ) ?>
        </div>
        <?= Html::input('hidden', 'user_id',(isset($params['user_id']))?$params['user_id']:'',[] ) ?>
        <?= Html::submitButton('Поиск по Frame', ['class' => 'col-xs-2 btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>