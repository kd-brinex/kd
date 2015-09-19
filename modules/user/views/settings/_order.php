<?php

use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\Html;

echo GridView::widget([
    'id'=> 'order-grid',
    'dataProvider' => $orders,
//    'filterModel'=>$model,
    'responsive'=>true,
    'hover'=>true,
    'pjax'=>true,

//    'tableOptions' =>['class' => 'table'],
//    'options'=>['class'=>'grid-view'],

    'rowOptions'=>function($model,$key, $index, $grid){
        return ['class'=>$model->order_class];
    },
//    'pjaxSettings'=>[
//        'neverTimeout'=>true,
//    ],
    'columns' => [
        [
            'header'=>'№ заказа',
            'attribute'=>'order.number'],
        [
            'header' => '<span style="color:#43b2ff">Производитель</span> /<br><span style="font-weight: normal">№ детали</span>',
            'format' => 'raw',
            'value' => function($model){
                           return '<strong style="color: #43b2ff">' .$model['manufacture'].'</strong><br> '.$model['product_id'];
                       }
        ],
        [
            'header' => 'Наименование детали',
//            'attribute' => 'part_name',
            'format' => 'raw',
            'value' => function($model){
                       return Html::a($model['part_name'],$model['product_url'],['target'=>'_blank']);
            }
        ],
        [
            'header' => 'Кол-во',
            'attribute' => 'quantity'
        ],
        [
            'header' => 'Цена',
            'attribute' => 'part_price'
        ],
        [
            'header' => 'Сумма',
            'value' => function($model){
                return $model['quantity']*$model['part_price'];
            }
        ],
        [
            'header' => 'Срок доставки',
            'value' => function($model){
                if(isset($model['parentOrder']['orderPays'][0]->date)){
                    $payDate = strtotime($model['parentOrder']['orderPays'][0]->date);
                    $deliveryDate = date('d.m.Y', ($payDate + (3600 * 24 * (!empty($model['delivery_days']) ? $model['delivery_days'] : \app\modules\user\models\Orders::DEFAULT_DELIVERY_DAYS))));
                } else $deliveryDate = '-';

                return $deliveryDate;
            }
        ],
        [
            'header' => 'Комментарий',
            'attribute' => 'description'
        ],
        [
            'header' => 'Статус',
            'attribute' => 'state.status_name',
//            'format' => 'raw',
//            'value' => function($model){
//                $url = '';
//                if(isset($model['product_id']) && $model['product_id'] != '')
//                    $url = ['/tovar/'.$model['product_id']];
//
//                if(isset($model['product_article']) && $model['product_article'] != '')
//                    $url = ['/autocatalog/autocatalog/details', 'article' => $model['product_article']];
//
//                return $model['status'] === $model::ORDER_CANCELED ? '<p>'.$model['state']['status_name'].'</p><a class="btn btn-success" target="_blank" href="'.Url::to($url).'">Перезаказать</a>' : $model['state']['status_name'];
//            }
        ],
    ]
]);
