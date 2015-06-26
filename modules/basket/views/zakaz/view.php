<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\basket\models\Zakaz */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Zakazs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zakaz-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'session_id',
            'user_id',
            'user_name',
            'user_telephon',
            'user_email:email',
            'pay_id',
            'store_id',
            'adr_city',
            'adr_adres',
            'adr_index',
            'zakaz',
            'zakaz_summa',
            'zakaz_date',
        ],
    ]) ?>

</div>
