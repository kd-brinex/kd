<?php
    use \app\modules\user\models\Profile;
    use \yii\helpers\Html;
    use \yii\bootstrap\Modal;
    use \yii\bootstrap\Button;
?>
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
    echo $form->field($profile, 'public_email')->input('email',['placeholder' => 'Ваш e-mail адрес']);
//    echo $form->field($profile, 'location')->textInput(['placeholder' => 'Ваш город', 'value' => $city->name]);
    echo '<div class="form-group field-profile-location required"><label class="col-lg-3 control-label" for="profile-location">Адрес</label><div class="col-lg-9">';
    Modal::begin(['header' => '<h2>' . 'Города' . '</h2>','toggleButton' => ['tag' => 'input', 'type'=>'text', 'readonly'=>'readonly', 'id'=> 'profile-location','class' => 'btn btn-info btn-block', 'name'=>'Profile[location]', 'value' => $city->name ? $city->name : 'Выбрать город', 'id' => 'button_city_list']]);//Html::button('Выбрать город',['class'=>'', 'data-toggle' => 'modal', 'data-target'=>'#w13',   ']);//'<button type="button" id="button_city_list" class="btn btn-block btn-info" data-toggle="modal" data-target="#w11">Выбрать город</button>';

    echo Button::widget([
        'label' => 'Выбрать город',
        'options' => [
            'class' => 'btn-lg btn-default',
            'style' => 'margin:5px',
            'onclick' => 'load_city_list()',
        ],
        'tagName' => 'div'
    ]);

    echo '<div id="city_list"></div>';
    Modal::end();
    echo '</div><div class="col-sm-offset-3 col-lg-9"><div class="help-block"></div></div></div>';
    echo $form->field($profile, 'telephone')->input('phone',['placeholder' => 'Ваш номер телефона']);

    \yii\widgets\ActiveForm::end();

    ?>
    </div>
<div class="col-xs-offset-10 col-xs-12">
    <button type="button" class="btn btn-error"  onclick="toggleTab(1)">Назад</button>
    <button type="button" class="btn btn-success" onclick="toggleTab(3)">Далее</button>
</div>