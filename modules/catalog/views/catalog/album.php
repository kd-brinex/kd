<?php

use yii\helpers\Html;
use yii\widgets\ListView;


/* @var $this yii\web\View */
/* @var $searchModel app\modules\tovar\models\ParamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//$this->title = $params['catalog'];
//$this->params['breadcrumbs'][] = 'Toyota';
//$this->params['breadcrumbs'][] = $this->title;
//var_dump($dataProvider);die;
?>
<div class="catalog-models">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php //var_dump($searchModel);die;?>
    <?php
//    echo $this->render('listblank',['toyota'=>$searchModel,'dataProvider'=>$dataProvider,'group'=>['key'=>'pic_code','value'=>4],'view'=>'album_image']);
   echo ListView::widget([
        'dataProvider' => $dataProvider,
       'itemView' => '_album',
//        'filterModel' => $searchModel,
//        'attributes' => [
//            'desc_en',
//
//            [   'attribute' => 'pic_code',
//                'format'=>'raw',
//                'value'=>function($model){return Html::img(\app\modules\catalog\models\ToyotaQuery::getImageUrl().$model['pic_code'].'.png',['height'=>'300px']);}
//            ]
//]
    ]);
    ?>

</div>