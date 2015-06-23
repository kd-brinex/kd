<?php

use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel app\modules\tovar\models\ParamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = $params['catalog'];
$this->params['breadcrumbs'][] = 'Toyota';
$this->params['breadcrumbs'][] = $this->title;
//var_dump($dataProvider->models);die;
?>
<div class="catalog-models">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php //var_dump($searchModel);die;?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'catalog',
//            'f1',
//            [
//                'attribute'=>'catalog_code',
//
//            ],
            [
                'attribute'=>'model_code',
                'format'=>'raw',
                'value'=>function ($data) {
                    return Html::a(Html::encode($data->model_code),$data->urlModel);
                },
            ],

            'prod_start',
            'prod_end',
//            'vdate',
//            'opt'
//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>