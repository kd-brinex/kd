<?php

use yii\helpers\Html;

use yii\bootstrap\Tabs;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\tovar\models\ParamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = $params['title'];
$this->params['breadcrumbs']= $params['breadcrumbs'];
//var_dump($dataProviderEU->query->getUrlParams('index'));die;
?>
<div class="catalog-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="row">


<div class="col-xs-12 col-md-4">
    <div class="row form-vin">
    <?= Html::tag('h3','Поиск по названию модели')?>
    <?= html::input('text','find_model',null,['placeholder'=>'Введите название. Например:Corolla','class'=>'form-control','id'=>'find_model'])?>

        </div>
</div>
        <?= $this->render('_search_vin', ['params'=>$params]) ?>
        <?= $this->render('_search_frame', ['params'=>$params]) ?>
        </div>
<div class="container">
    <?= Tabs::widget([

        'items' => [
            ['label' => 'Европа',
                'content' => $this->render('_index_group_model',['dataProvider'=>$dataProviderEU,'params'=>$params]),
                'active' => true,
                'options'=>['class'=>'acatalog-tabs'],
            ],
            ['label' => 'Ближний восток',
                'content' => $this->render('_index_group_model',['dataProvider'=>$dataProviderGR,'params'=>$params]),
                'active' => false,
                'options'=>['class'=>'acatalog-tabs'],
            ],
            ['label' => 'Япония',
                'content' => $this->render('_index_group_model',['dataProvider'=>$dataProviderJP,'params'=>$params]),
                'active' => false,
                'options'=>['class'=>'acatalog-tabs'],
            ],
            ['label' => 'США',
                'content' => $this->render('_index_group_model',['dataProvider'=>$dataProviderUS,'params'=>$params]),
                'active' => false,
                'options'=>['class'=>'acatalog-tabs'],
            ],
        ]
    ]); ?>
</div>
    <?php
//    GridView::widget([
//        'dataProvider' => $dataProvider,
//
//        'columns' => [
////            ['class' => 'yii\grid\SerialColumn'],
//            'catalog',
////            'f1',
////            [
////                'attribute'=>'catalog_code',
////
////            ],
//            [
//                'attribute'=>'model_name',
//                'format'=>'raw',
//                'value'=>function ($data) {
//                    return Html::a(Html::encode($data['model_name']),Url::to(['model',
//                        'catalog_code' => $data['catalog_code'],
//                        'catalog' => $data['catalog'],
//                        'model_name' => $data['model_name'],
//                    ]));
//                },
//            ],
//
//            'prod_start',
//            'prod_end',
////            'vdate',
////            'opt'
////            ['class' => 'yii\grid\ActionColumn'],
//        ],
//    ]);
    ?>

</div>