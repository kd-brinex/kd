<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\images\models\ImgImage */

$this->title = 'Редактировать картинку' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Картинки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="img-image-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('updateForm', [
        'model' => $model,
    ]) ?>

</div>
