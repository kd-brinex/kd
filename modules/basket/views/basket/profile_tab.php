<?php
    use \app\modules\user\models\Profile;
    use \yii\helpers\Html;
    use \yii\bootstrap\Modal;
    use \yii\bootstrap\Button;
?>
<div class="basketStepsBlock col-xs-12">
    <div id="step2" class="basketSteps"><i style="float: left;" class="icon-white icon-circle-success"></i>Заполните форму для связи с Вам</div>
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
    echo '<div class="form-group field-profile-location required"><label class="col-lg-3 control-label" for="profile-location">Адрес</label><div class="col-lg-9">';
    Modal::begin([
        'header' => '<img src="/img/kolesa-darom_logo.png"/>',
        'toggleButton' => ['tag' => 'input',
            'type'=>'text',
            'readonly'=>'readonly',
            'id'=> 'profile-location',
            'class' => 'btn btn-info btn-block',
            'name'=>'Profile[location]',
            'value' => $city->name ? $city->name : 'Выбрать город',

            'onclick'=>'load_city_list()',
        ]]);




    echo '<div id="city_list1"></div>';
    Modal::end();
    echo '</div><div class="col-sm-offset-3 col-lg-9"><div class="help-block"></div></div></div>';
    echo $form->field($user, 'telephone')->input('phone',['placeholder' => 'Ваш номер телефона']);

    \yii\widgets\ActiveForm::end();

    ?>
    </div>
<div class="col-xs-offset-10 col-xs-12">
    <button type="button" class="btn btn-error"  onclick="toggleTab(1)">Назад</button>
    <button type="button" class="btn btn-success" onclick="checkTab()">Далее</button>
</div>