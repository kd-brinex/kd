<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\growl\Growl;

$asset = app\modules\autoparts\autopartsAsset::register($this);
$asset = app\modules\user\userAsset::register($this);
$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1>Заказы</h1>

<div class="col-xs-12">
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
        'rowOptions'=>function($model){
            return ['class' => $model->order_class];
        },
        'columns' => [
            [
                'label' => 'Дата',
                'attribute' => 'order',
                'value' => 'order.date'
            ],
            [
                'label' => 'Магазин',
                'attribute' => 'store',
                'value' => 'store.name'
            ],
            [
                'label' => 'Покупатель',
                'attribute' => 'order_name',
                'value' => 'order.user_name'
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Отправить заказ', ['create'], ['class' => 'btn btn-success', 'onClick' => 'sendAllToProvider(this); return false']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Сбросить', ['index'], ['class' => 'btn btn-info']),
        ],

    ]);


?>
</div>

