<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tovar\models\ParamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Toyota';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $this->render('_search_vin', ['params'=>$params]) ?>
    </p>
    <p>
        <?= $this->render('_search_frame', ['params'=>$params]) ?>
    </p>

<?php //var_dump($searchModel);die;?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'catalog',
//            'f1',
//            [
//                'attribute'=>'catalog_code',
//
//            ],
            [
                'attribute'=>'model_name',
                'format'=>'raw',
                'value'=>function ($data) {
                    return Html::a(Html::encode($data['model_name']),Url::to(['model',
                        'catalog_code' => $data['catalog_code'],
                        'catalog' => $data['catalog'],
                        'model_name' => $data['model_name'],
                    ]));
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