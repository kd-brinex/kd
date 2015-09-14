<?php
use \kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\images\models\ImgImage */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="img-image-form">


    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?=$form->field($model, 'image')->widget(FileInput::classname(), [
         'options'=>[
            'multiple'=>true
        ],
        'pluginOptions' => [
            'uploadUrl' => Url::to(['/images/images/upload']),
            'uploadExtraData' => [
                'album_id' => 20,
                'cat_id' => 'Nature'
            ],
            'maxFileCount' => 10
        ]


    ]);?>



    <?php ActiveForm::end(); ?>

</div>
