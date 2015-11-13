<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\loader\models\Loader */

$this->title = 'Update Loader: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Loaders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="loader-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
