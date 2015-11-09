<?php
use yii\widgets\ActiveForm;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 03.11.15
 * Time: 17:28
 */
echo \yii\grid\GridView::widget(['dataProvider'=>$provider]);


$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);?>
<?= $form->field($model, 'textFile')->fileInput();?>
<button>Load</button>
<?= \yii\bootstrap\Html::tag('div',$text)?>

