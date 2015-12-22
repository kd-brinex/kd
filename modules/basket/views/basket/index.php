<?php

use yii\bootstrap\Tabs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\basket\models\Zakaz */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = \yii\widgets\ActiveForm::begin([
    'id' => 'profile-form',
    'options' => ['class' => 'form-horizontal', 'onsubmit' => 'return false;'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
        'labelOptions' => ['class' => 'col-lg-3 control-label'],
    ],
    'enableAjaxValidation'   => false,
    'enableClientValidation' => true,
    'validateOnBlur'         => true,
]);
?>
<?php
    echo Tabs::widget([
        'items' => [
            [
                'label' => 'Корзина',
                'content' => $basketContent,
                'active' => $tab == 0,
                'headerOptions' => [
                    'id' => '1-basket-tab'
                ],
            ],
            [
                'label' => 'Клиент',
                'content' => $this->render('profile_tab',$user_data),
                'active' => $tab == 1,
                'headerOptions' => [
                    'id' => '2-basket-tab'
                ],
            ],
            [
                'label' => 'Доставка',
                'content' => $this->render('delivery_tab', $delivery_data),
                'active' => $tab == 2,
                'headerOptions' => [
                    'id' => '3-basket-tab'
                ],
            ],
            [
                'label' => 'Оплата',
                'content' => $this->render('pay_tab'),
                'active' => $tab == 3,
                'headerOptions' => [
                    'id' => '4-basket-tab'
                ],
            ],
        ]
]);?>

<?php \yii\widgets\ActiveForm::end(); ?>

