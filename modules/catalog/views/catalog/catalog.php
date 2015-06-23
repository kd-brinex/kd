<?php

use yii\helpers\Html;
use yii\grid\GridView;
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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
//
        ],
    ]); ?>

</div>