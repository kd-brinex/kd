<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use yii\grid\GridView;
use yii\widgets\ListView;
use app\modules\autoparts\AutopartsAsset;
AutopartsAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\autoparts\models\PartOver */
/* @var $form yii\widgets\ActiveForm */
?>

<noindex>
<div id="parent_popup">
    <div id="popup">
        <p>Пожалуйста, подождите...<img src="<?=Yii::$app->request->baseUrl."/assets/images/712.gif"?>" width="20"/></p>
    </div>
</div>
</noindex>


<div class="part-over-form">
    <?php
    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <?= $form->field($model, 'file')->fileInput() ?>
    <?= $form->field($model, 'flagpostav')->dropDownList($flag_postav_list, []) ?>


    <button>Отправить</button>

    <?php ActiveForm::end() ?>
    <?php






    ?>
</div>

