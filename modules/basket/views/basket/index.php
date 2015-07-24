<?php

use yii\bootstrap\Tabs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\basket\models\Zakaz */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="basketStepsBlock col-xs-12">
    <div id="step1" class="basketSteps" style="display:block"><i style="float: left;" class="icon-white icon-circle-success"></i>Выберите товары для заказа</div>
    <div id="step2" class="basketSteps"><i style="float: left;" class="icon-white icon-circle-success"></i>Заполните форму для связи с Вам</div>
    <div id="step3" class="basketSteps"><i style="float: left;" class="icon-white icon-circle-success"></i>Выберите магазин в Вашем городе</div>
</div>
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
            'active' => true,
            'headerOptions' => [
                'id' => '1-basket-tab'
            ],],

        [
            'label' => 'Клиент',
            'content' => $this->render('profile_tab',$user_data),
            'headerOptions' => [
                'id' => '2-basket-tab'
            ],

        ],
//        [
//            'label' => 'Оплата',
//            'content' => '<h2>Выберите способ оплаты</h2>',
//
//            'headerOptions' => [
//                'id' => 'pay'
//            ],
//
//        ],
        [
            'label' => 'Доставка',
            'content' => $this->render('delivery_tab', $delivery_data),

            'headerOptions' => [
                'id' => '3-basket-tab'
            ],

        ],]]);?>



<?php \yii\widgets\ActiveForm::end(); ?>

