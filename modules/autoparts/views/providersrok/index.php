<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\autoparts\models\PartProviderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Срок доставки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="part-provider-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Новый срок доставки', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'providerName',
//            'city_id',
            'cityName',
            'days',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>