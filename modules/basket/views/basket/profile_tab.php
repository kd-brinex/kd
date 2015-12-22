<?php
    use \app\modules\user\models\Profile;
    use \yii\helpers\Html;
    use \yii\bootstrap\Modal;
    use \yii\bootstrap\Button;
?>
<div class="basketStepsBlock col-xs-12">
    <div id="step2" class="basketSteps"><i style="float: left;" class="icon-white icon-circle-success"></i>Заполните форму для связи с Вами</div>
</div>
<h2>Укажите контактные данные.</h2>
<div id="user">

    <?php
    $form = \yii\widgets\ActiveForm::begin([
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

    echo $form->field($profile, 'name')->textInput(['placeholder' => 'Ваше имя']);

    echo $form->field($user, 'email')->input('email',['placeholder' => 'Ваш e-mail адрес','value' => $user->email]);

    echo $form->field($user, 'telephone')->input('phone',['placeholder' => 'Ваш номер телефона']);

    \yii\widgets\ActiveForm::end();

    ?>
    </div>
<div class="col-xs-offset-10 col-xs-12">
    <button type="button" class="btn btn-error"  onclick="toggleTab(1)">Назад</button>
    <button type="button" class="btn btn-success" onclick="checkTab()">Далее</button>
</div>