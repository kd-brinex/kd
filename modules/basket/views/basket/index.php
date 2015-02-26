<?php

use yii\bootstrap\Tabs;


/* @var $this yii\web\View */
/* @var $model app\modules\basket\models\Zakaz */
/* @var $form yii\widgets\ActiveForm */

echo Tabs::widget([
    'items' => [
        [
            'label' => 'Заказ',
            'content' => '<h1>Ваш заказ.</h1>'
                .'<div id="basket">'
                . $this->render('zakaz_tab', ['model' => $model,'itogo'=>$itogo])
                .'</div>',
            'active' => true,
            'headerOptions' => [
                'id' => 'zakaz'
            ],],
        [
            'label' => 'Клиент',
            'content' => '<h2>Укажите контактные данные.</h2>',


            'headerOptions' => [
                'id' => 'user'
            ],

        ],
        [
            'label' => 'Оплата',
            'content' => '<h2>Выберите способ оплаты</h2>',

            'headerOptions' => [
                'id' => 'pay'
            ],

        ],
        [
            'label' => 'Доставка',
            'content' => '<h2>Выберите способ получения товара.</h2>',

            'headerOptions' => [
                'id' => 'delivery'
            ],

        ],]]);



