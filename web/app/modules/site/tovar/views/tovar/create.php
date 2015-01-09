<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\site\tovar\models\Tovar */

$this->title = 'Create Tovar';
$this->params['breadcrumbs'][] = ['label' => 'Tovars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tovar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
