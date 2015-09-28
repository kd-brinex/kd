<?php
use \kartik\grid\GridView;
use yii\helpers\Html;
?>

<div>
    <span style="margin-right:15px"><strong>Номер заказа:</strong> <?=$order->number?></span>
    <span style="margin-right:15px"><strong>Дата:</strong> <?=$order->date?></span>
    <span style="margin-right:15px"><strong>Имя покупателя:</strong> <?=$order->user_name?></span>
    <span style="margin-right:15px"><strong>Телефон:</strong> <?=$order->user_telephone?></span>
</div>


<?=GridView::widget([
    'id' => 'manager-order-grid',
    'dataProvider' => $model,
    'responsive' => true,
    'hover' => true,
    'columns' => [
        [
            'label' => 'Акртикул',
            'attribute' => 'product_id'
        ],
        [
            'label' => 'Производитель',
            'attribute' => 'manufacture'
        ],
        [
            'label' => 'Название',
            'attribute' => 'part_name'
        ],
        [
            'label' => 'Цена',
            'attribute' => 'part_price'
        ],
        [
            'label' => 'Количество',
            'attribute' => 'quantity'
        ],
        [
            'label' => 'Сумма',
            'attribute' => 'cost'
        ],
        [
            'header' => 'Олата',
            'class' => \kartik\grid\CheckboxColumn::className(),
            'vAlign' => GridView::ALIGN_TOP,
            'checkboxOptions' => function($model){
                return ['value' => $model['id'], 'checked' => $model['is_paid'], 'onClick' => 'updatePaidStatus(this)'];
            }
        ],
        [
            'label' => 'Поставщик',
            'attribute' => 'provider',
            'value' => 'provider.name'

        ],
        [
            'label' => 'Срок',
            'attribute' => 'delivery_days'
        ],
        [
            'label' => 'Статус',
            'attribute' => 'state',
            'format' => 'raw',
            'value' => function($model){
                return Html::activeDropDownList($model, 'status', \yii\helpers\ArrayHelper::map(\app\modules\user\models\OrdersState::find()->all(), 'id', 'status_name'), ['class' => 'form-control', 'style'=> 'min-width:125px', 'onChange' => 'updateStatus(this)']);
            },
        ],
        [
            'label' => 'Комментарий',
            'attribute' => 'description'
        ]

    ]
]);
?>