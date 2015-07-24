<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\autoparts\models\PartOver */

$this->title = 'Create Part Over';
$this->params['breadcrumbs'][] = ['label' => 'Part Overs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="part-over-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,'flag_postav_list'=>$flag_postav_list
    ]) ?>

</div>
