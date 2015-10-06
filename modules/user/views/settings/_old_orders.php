<?php
use kartik\grid\GridView;

$model->setModels($orders);
echo GridView::widget([
    'id' => 'OrdersGrid',
    'dataProvider' => $model,
    'hover' => true,
    'pjax' => true,
    'rowOptions' => function($model){
        return ['class' => 'clickableRow gridRowStateBgColor', 'onClick' => 'openOrder(this)', 'data-id' => $model->id];
    },
    'columns' => [
        [
            'label' => 'Номер заказа',
            'attribute' => 'number'
        ],
        [
            'label' => 'Дата заказа',
            'attribute' => 'date'
        ],
        [
            'label' => 'Сумма',
            'value' => function($model){
                $orderSum = 0;
                foreach($model['orders'] as $order){
                    if($order->order_id == $model['id'])
                        $orderSum += ($order->part_price * $order->quantity);
                }
                return $orderSum;
            }
        ],

        [
            'label' => 'Оплата',
            'value' => function($model){
                if(empty($model['orderPays']))
                    return 0;
                else {
                    $fullSum = 0;
                    foreach($model['orderPays'] as $key => $orderPay){
                        if($orderPay->order_id == $model['id'])
                            $fullSum += $orderPay->sum;
                    }
                    return $fullSum;
                }
            }
        ]
    ]
]);

?>