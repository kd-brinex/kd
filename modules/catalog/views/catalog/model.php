<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tovar\models\ParamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//var_dump($params,$dataProvider);die;
$this->title = $params['model_name'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-model">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//            'catalog',
//            'f1',
//            'catalog_code',
            [
                'attribute' => 'model_code',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a(Html::encode($data['model_code']), Url::to(['catalog',
                        'catalog_code' => $data['catalog_code'],
                        'catalog' => $data['catalog'],
                        'model_code' => $data['model_code'],
                        'compl_code' => $data['compl_code'],
                        'sysopt' => $data['sysopt'],
                        'vdate' => (isset($data['vdate']))?$data['vdate']:'',
                    ]));
                },],
            [
                'label'=>'Дата производства',
                'value' => function($data) {
                    return substr($data['prod_start'],-2).'/'.substr($data['prod_start'],0,4).' - '.substr($data['prod_end'],-2).'/'.substr($data['prod_end'],0,4);
                }
            ],
            'compl_code',

            ['attribute'=>'engine1',
            'label'=>'Двигатель'],
//            'engine2',
//            'body',
            [
            'attribute'=>'atm_mtm',
            'label'=>'Коробка'],
            'grade',
            'trans',
//            'sysopt'
//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>