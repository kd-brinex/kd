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
            'label' => 'Статус',
            'value' => function($model){
                $states = [];
                foreach($model['orders'] as $value){
                    if($value->order_id == $model['id'])
                        $states[] = $value->status;
                }
                return \app\modules\user\models\OrdersState::find()
                    ->select('status_name')
                    ->where('id = :id', [':id' => min($states)])
                    ->one()
                    ->status_name;
            }
        ],
    ]
]);

?>