<?php

use yii\bootstrap\Tabs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\basket\models\Zakaz */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = \yii\widgets\ActiveForm::begin([
    'id' => 'profile-form',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
        'labelOptions' => ['class' => 'col-lg-3 control-label'],
    ],
    'enableAjaxValidation'   => true,
    'enableClientValidation' => false,
    'validateOnBlur'         => false,
]);
$profile=$user->profile;
?>


<?php
echo Tabs::widget([
    'items' => [
        [
            'label' => 'Заказ',
            'content' => '<h1>Ваш заказ.</h1>'
                . '<div id="basket">'
                . $this->render('zakaz_tab', ['model' => $bmodel, 'itogo' => $itogo])
                . '</div>',
            'active' => true,
            'headerOptions' => [
                'id' => 'zakaz'
            ],],
        [
            'label' => 'Клиент',
            'content' => '<h2>Укажите контактные данные.</h2>'
                . '<div id="user">'

            .$form->field($profile, 'name')
            .$form->field($profile, 'public_email')
            .$form->field($profile, 'location')
                . '</div>'
            ,

            'headerOptions' => [
                'id' => 'user'
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
            'content' => '<h2>Выберите способ получения товара.</h2>',

            'headerOptions' => [
                'id' => 'delivery'
            ],

        ],]]);?>

<div class="form-group">
    <div class="col-lg-offset-9 col-lg-3">
        <?= \yii\helpers\Html::submitButton(Yii::t('user', 'Оформить заказ'), ['class' => 'btn btn-block btn-success']) ?><br>
</div>
</div>

<?php \yii\widgets\ActiveForm::end(); ?>

