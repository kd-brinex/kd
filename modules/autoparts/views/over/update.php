<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\autoparts\models\PartOver */

$this->title = 'Update Part Over: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Part Overs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="part-over-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
