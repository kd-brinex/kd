<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\autoparts\models\PartProviderUser */

$this->title = 'Новый срок доставки';
$this->params['breadcrumbs'][] = ['label' => 'Сроки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="part-provider-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
