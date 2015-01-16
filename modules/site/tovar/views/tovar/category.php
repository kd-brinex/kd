<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 09.01.15
 * Time: 11:25
 */

echo yii\widgets\ListView::widget([

    'dataProvider' => $dataProvider,

    'itemOptions' => ['class' => 'tovars_block'],

    'itemView' => function ($model, $key, $index, $widget) {
        return $this->render('tovars_block_view', ['model' => $model]);

    },

]);
