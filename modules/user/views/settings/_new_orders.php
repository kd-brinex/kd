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
                'label' => 'Выполнен на',
                'format' => 'raw',
                'value' => function($model){
                    $executed = 0;
                    $execution_step = floor(100 / count($model['orders']));
                    foreach($model['orders'] as $value){
                        if($value->status > \app\modules\user\models\Orders::ORDER_EXECUTED)
                            $executed += $execution_step;
                    }
                    $progressBar = '<div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar"aria-valuenow="'.$executed.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$executed.'%">'.$executed.'%</div></div>';

                    return $progressBar;
                }
            ],
            [
                'label' => 'Оплата',
                'value' => function($model) {
                    if (empty($model['orderPays']))
                        return 0;
                    else {
                        $fullSum = 0;
                        foreach ($model['orderPays'] as $key => $orderPay) {
                                $fullSum += $orderPay->sum;
                        }
                        return $fullSum;
                    }
                }
            ]
        ]
    ]);

?>