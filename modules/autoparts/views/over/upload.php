<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use yii\grid\GridView;
use yii\widgets\ListView;
use app\modules\autoparts\autopartsAsset;
autopartsAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\autoparts\models\PartOver */
/* @var $form yii\widgets\ActiveForm */
?>

<noindex>
<div id="parent_popup">
    <div id="popup">
        <p>Пожалуйста, подождите...<img src="http://cs7052.vk.me/c610730/u101531767/docs/5ffda83aef35/712.gif" width="20"/></p>
    </div>
</div>
</noindex>


<div class="part-over-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <?= $form->field($model, 'file')->fileInput() ?>
    <?= $form->field($model, 'flagpostav')->dropDownList($flag_postav_list, []) ?>


    <button>Отправить</button>

    <?php ActiveForm::end() ?>
    <?php






    ?>
</div>

