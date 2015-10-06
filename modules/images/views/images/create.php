<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\images\models\ImgImage */

$this->title = 'Добавить картинку';
$this->params['breadcrumbs'][] = ['label' => 'Картинки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="img-image-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
