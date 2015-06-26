
<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
//var_dump($dataProvider->models);die;
$data=$dataProvider->models[0];
$dataProvider->query->setData($data);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//            'catalog',
//            'f1',
//            [
//                'attribute'=>'catalog_code',
//
//            ],
            [
                'attribute'=>'model_name',
                'label' => 'Модель',
                'format'=>'raw',
                'value'=>function ($data) {
                    return Html::a(Html::encode($data['model_name']),Url::to(['model',
                        'catalog_code' => $data['catalog_code'],
                        'catalog' => $data['catalog'],
                        'model_name' => $data['model_name'],
                    ]));
                },
            ],
            [
                'label'=>'Дата производства',
                'value' => function($data) {
                    return substr($data['prod_start'],-2).'/'.substr($data['prod_start'],0,4).' - '.substr($data['prod_end'],-2).'/'.substr($data['prod_end'],0,4);
                }
            ],
            [
                'label' => 'Модификации',
                'attribute' => 'models_codes',
            ]

//[
//            'attribute'=>'prod_end',
//            'value' => function($data) {
//                return substr($data['prod_end'],0,4).'-'.substr($data['prod_end'],-2);
//            }
//        ],
//            'vdate',
//            'opt'
//            ['class' => 'yii\grid\ActionColumn'],
        ],

    ]);
?>