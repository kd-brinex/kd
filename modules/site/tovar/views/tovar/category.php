<?php
use yii\widgets\LinkPager;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 09.01.15
 * Time: 11:25
 */
//\yii\widgets\Pjax::begin();
echo \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
//    'filterModel'  => $searchModel,
]);
//\yii\widgets\Pjax::end();