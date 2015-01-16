<?php
use yii\bootstrap\Collapse;
use \yii\grid\GridView;
use yii\widgets\ListView;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 08.01.15
 * Time: 17:28
 */

//echo GridView::widget([
//    'dataProvider' => $dataProvider,
//    'columns' => [
//        ['class' => 'yii\grid\SerialColumn'],
//        'param_id',
//        'value_char',
////        ['class' => 'yii\grid\ActionColumn'],
//    ]

//]);
echo ListView::widget([

    'dataProvider' => $tovarProvider,
    'layout' => "{items}",
    'itemOptions' => ['class' => 'tovar_block'],

    'itemView' => function ($model, $key, $index, $widget) {
        return $this->render('tovar_block_view', ['model' => $model]);

    },

]);

echo Collapse::widget([
    'items' => [
        [
            'label' => 'Дополнительные характеристики.',
            'content' =>
                GridView::widget([

                    'layout' => "{items}",
//                    'showPageSummary' => false,
//                    'showFooter' => false,
//                    'pagination' => false,
                    'dataProvider' => $dataProvider,
//              'filterModel'=>$searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'param_id',
                        'value_char',

//        ['class' => 'yii\grid\ActionColumn'],
                    ]

                ])
            ,
            // Открыто по-умолчанию
            'contentOptions' => [ 'class' => 'in'],

        ],

    ]
]);
