<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;


/* @var $this yii\web\View */
/* @var $searchModel app\modules\tovar\models\ParamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//var_dump($params,$dataProvider);die;
//$this->title = $params['title'];
//$this->params['breadcrumbs']= $params['breadcrumbs'];

$this->params['breadcrumbs']= $params['breadcrumbs'];
?>
<div class="catalog-model">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= Tabs::widget([
        'items' => [
            ['label' => 'Tool/Engine/Fuel',
                'content' => $this->render('listview',['toyota'=>$searchModel,'dataProvider'=>$dataProvider,'group'=>['key'=>'main_group','value'=>1],'view'=>'block']),
                'active' => true,
                'options'=>['class'=>'acatalog-tabs'],
            ],
            ['label' => 'Power Train/Chassis',
                'content' => $this->render('listview',['toyota'=>$searchModel,'dataProvider'=>$dataProvider,'group'=>['key'=>'main_group','value'=>2],'view'=>'block']),
                'active' => false,
                'options'=>['class'=>'acatalog-tabs'],
            ],
            ['label' => 'Body',
                'content' => $this->render('listview',['toyota'=>$searchModel,'dataProvider'=>$dataProvider,'group'=>['key'=>'main_group','value'=>3],'view'=>'block']),
                'active' => false,
                'options'=>['class'=>'acatalog-tabs'],
            ],
            ['label' => 'Electrical',
                'content' => $this->render('listview',['toyota'=>$searchModel,'dataProvider'=>$dataProvider,'group'=>['key'=>'main_group','value'=>4],'view'=>'block']),
                'active' => false,
                'options'=>['class'=>'acatalog-tabs'],
            ],
        ]
    ]); ?>



</div>