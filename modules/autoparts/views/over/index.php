<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\autoparts\models\PartOverSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Part Overs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="part-over-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать запись', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Загрузить CSV файл', ['upload'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'name',
            'manufacture',
            'price',
            'quantity',
            'date_update',
            'flagpostav',
            // 'srokmin',
            // 'srokmax',
            // 'lotquantity',
            // 'pricedate',
            // 'skladid',
            // 'sklad',
            // 'flagpostav',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
