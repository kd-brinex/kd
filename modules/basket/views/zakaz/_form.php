<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $model app\modules\basket\models\Zakaz */
/* @var $form yii\widgets\ActiveForm */
//var_dump($model->basket);
?>

<div class="zakaz-form">
    <?php
//    var_dump($model->basket->getModels());die;

    \yii\widgets\Pjax::begin();
    $form = ActiveForm::begin(); ?>
    <?= Tabs::widget ( [
    'items' => [
    [
    'label' => 'Заказ',
    'content' => '<h1>Ваш заказ.</h1>'
    .$this->render('zakaz_tab', ['model' => $model])
//.ListView::widget([
//    'summary'=>'',
//    'dataProvider' =>$model->basket,
////    'showHeader' => false,
//    'options'=>['tag'=>'table','class'=>'offer-v3-table'],
////    'itemOptions' => ['tag'=>'tr'],
//
//    'itemView' => function ($model){return $this->render('tovars_block_view_3', ['model' => $model]);},
//])
//. GridView::widget([
//        'dataProvider' => $model->basket,
////            'showHeader'=>false,
////        'filterModel' => $searchModel,
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//            'id',
//            'tovar_id',
//            'tovar_count',
//            'tovar_price',
//
////            ['class' => 'yii\grid\ActionColumn'],
//        ],
//    ])

    .$form->field($model, 'zakaz')->textInput()

    .$form->field($model, 'zakaz_summa')->textInput()

    .$form->field($model, 'zakaz_date')->textInput(),

    'active' => true,
    'headerOptions' => [
    'id' => 'zakaz'
    ],

    ],
    [
    'label' => 'Клиент',
    'content' => '<h2>Укажите контактные данные.</h2>'

    .$form->field($model, 'user_id')->textInput(['maxlength' => 45])

    .$form->field($model, 'user_name')->textInput(['maxlength' => 45])

    .$form->field($model, 'user_telephon')->textInput(['maxlength' => 45])

    .$form->field($model, 'user_email')->textInput(['maxlength' => 45]),

    'headerOptions' => [
    'id' => 'user'
    ],

    ],
    [
    'label' => 'Оплата',
    'content' => '<h2>Выберите способ оплаты</h2>'
        . $form->field($model, 'pay_id')->textInput(),
    'headerOptions' => [
    'id' => 'pay'
    ],

    ],
    [
    'label' => 'Доставка',
    'content' => '<h2>Выберите способ получения товара.</h2>'
    .$form->field($model, 'store_id')->textInput()

    .$form->field($model, 'adr_city')->textInput(['maxlength' => 45])

    .$form->field($model, 'adr_adres')->textInput(['maxlength' => 150])

    .$form->field($model, 'adr_index')->textInput(['maxlength' => 6]),

    'headerOptions' => [
    'id' => 'delivery'
    ],

    ],

    ]
    ] )?>



    <?= $form->field($model, 'id')->hiddenInput() ?>

    <?= $form->field($model, 'session_id')->hiddenInput(['maxlength' => 45]) ?>









    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сделать заказ' : 'Изменить заказ', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php \yii\widgets\Pjax::end();?>
</div>
