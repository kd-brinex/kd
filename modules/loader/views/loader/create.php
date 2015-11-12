<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\loader\models\Loader */

$this->title = 'Create Loader';
$this->params['breadcrumbs'][] = ['label' => 'Loaders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loader-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
