<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 08.01.15
 * Time: 17:28
 */
\yii\widgets\Pjax::begin();
echo \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
]);
\yii\widgets\Pjax::end();