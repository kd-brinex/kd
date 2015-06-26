<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\autoparts\models\PartProvider */

$this->title = 'Create Part Provider';
$this->params['breadcrumbs'][] = ['label' => 'Part Providers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="part-provider-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
