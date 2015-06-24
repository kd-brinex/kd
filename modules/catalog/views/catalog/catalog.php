<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tovar\models\ParamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//var_dump($params,$dataProvider);die;
//$this->title = $params['model_name'];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="catalog-model">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= Tabs::widget([
        'items' => [
            ['label' => 'Tool/Engine/Fuel',
                'content' => $this->render('listview',['dataProvider'=>$dataProvider,'group'=>1]),
                'active' => true,
                'options'=>['class'=>'acatalog-tabs'],
            ],
            ['label' => 'Power Train/Chassis',
                'content' => $this->render('listview',['dataProvider'=>$dataProvider,'group'=>2]),
                'active' => false,
                'options'=>['class'=>'acatalog-tabs'],
            ],
            ['label' => 'Body',
                'content' => $this->render('listview',['dataProvider'=>$dataProvider,'group'=>3]),
                'active' => false,
                'options'=>['class'=>'acatalog-tabs'],
            ],
            ['label' => 'Electrical',
                'content' => $this->render('listview',['dataProvider'=>$dataProvider,'group'=>4]),
                'active' => false,
                'options'=>['class'=>'acatalog-tabs'],
            ],
        ]
    ]); ?>


</div>