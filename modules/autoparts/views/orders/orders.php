<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use \yii\bootstrap\Modal;

$asset = app\modules\autoparts\AutopartsAsset::register($this);
$asset = app\modules\user\userAsset::register($this);
$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'options' => [
        'id' => 'order-modal'
    ],
    'size' => 'modal-lg',

    'header' => '<h3>Детализация заказа</h3>'
]);
Modal::end();

?>

<h1>Заказы</h1>

<div class="col-xs-12" style="padding: 0px !important;">
    <?=GridView::widget([
        'id'=> 'orders-manage-grid',
        'dataProvider' => $orders,
        'filterModel' => $model,
        'responsive'=>true,
        'hover'=>true,
        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true,
        ],

        'columns' => [
            [
                'label' => 'Дата',
                'attribute' => 'date',
                'filter' => Html::activeInput('date', $model, 'date', ['class' => 'form-control']),
                'value' => 'date'
            ],
            [
                'label' => 'Магазин',
                'attribute' => 'store_name',
                'value' => 'store.name'
            ],
            [
                'label' => 'Покупатель',
                'attribute' => 'user_name',
                'value' => 'user_name'
            ],
            [
                'label' => 'Сумма заказа',
                'attribute' => 'orderSumma',
                'value' => 'orderSumma',
            ],
            [
                'label' => 'Оплачено',
                'attribute' => 'orderPaysSum',
                'value' => 'orderPaysSum'
            ],
            [
                'label' => '№ заказа в 1C',
                'attribute' => '1c_order_id',
                'value' => '1c_order_id'
            ],
            [
                'label' => 'Комментарий',
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'comment',
                'editableOptions' => [
                    'header' => 'комментарий',
                    'inputType' => \kartik\editable\Editable::INPUT_TEXTAREA,
                    'size' => 'md',
                    'ajaxSettings' => [
                        'url' => '/autoparts/orders/update'
                    ]
                ]
            ],
            [
                'label' => 'Статус',
                'format' => 'raw',
                'attribute' => 'managerOrderStatus',
                'value' => function($model){
                    return '<div class="progress" style="min-width: 150px"><div class="progress-bar progress-bar-striped active" role="progressbar"aria-valuenow="'.$model['managerOrderStatus'].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$model['managerOrderStatus'].'%">'.$model['managerOrderStatus'].'%</div></div>';
                },
            ],
            [
                'format' => 'raw',
                'value' => function($model){
                    return '<button class="btn btn-primary" onClick="loadOrderData(this)" data-toggle="modal" data-target="#order-modal">Просмотр</button>';
                }
            ]

        ],
//        'toolbar' => [
//            [
//                'content'=>
//                    Html::button('<i class="glyphicon glyphicon-plus"></i>', [
//                        'type' =>'button',
//                        'title'=> 'Add Book',
//                        'class'=>'btn btn-success'
//                    ]) . ' '.
//                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['grid-demo'], [
//                        'class' => 'btn btn-default',
//                        'title' => 'Reset Grid'
//                    ]),
//            ],
//            '{export}',
//            '{toggleData}'
//        ],
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-shopping-cart"></i> Заказы</h3>',
            'type'=>'success',
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Сбросить', ['.'], ['class' => 'btn btn-info']),
        ],

    ]);


?>
</div>

