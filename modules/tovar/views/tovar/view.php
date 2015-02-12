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
//var_dump($tovarProvider->models[0]['name']);die;
$this->title=$tovarProvider->models[0]['name'];
$category=$tovarProvider->models[0]['tip_id'];
$this->params['breadcrumbs'][] = ['label'=>$category,'url'=>['/tovar/tovar/category','tip_id'=>$category]];
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->view->registerCssFile('/css/style-uni.css');
echo ListView::widget([

    'dataProvider' => $tovarProvider,
    'layout' => "{items}",
    'options' => ['tag'=>'div','class'=>'offer-page'],

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
                        'title',
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
