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
            'label' => 'Артикул',
            'value' => function($model){
                return !empty($model['product_id']) ? $model['product_id'] : $model['product_article'];
            },
            'contentOptions' => ['class' => 'part_article']
        ],
        [
            'label' => 'Производитель',
            'value' => 'manufacture'
        ],
        [
            'label' => 'Название',
            'value' => 'part_name'
        ],
        [
            'label' => 'Цена',
            'value' => 'part_price'
        ],
        [
            'label' => 'Количество',
            'value' => 'quantity'
        ],
        [
            'label' => 'Сумма',
            'value' => 'cost'
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
            'value' => 'provider.name'

        ],
        [
            'label' => 'Срок',
            'value' => 'delivery_days'
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
            'value' => 'description'
        ],
    ],
    'toolbar' => [
        [
            'content' =>  Html::a('<i class="glyphicon glyphicon-rub"></i> Проценка', ['#'], [
                    'title'=>'Проценка',
                    'class'=>'btn btn-success',
                    'onClick' => 'pricing('.$order->id.'); return false'
            ]),
            'options' => ['class' => 'btn-group-sm']
        ],
        'export' => [

        ],
    ],
    'panel' => [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-tasks"></i> Позиции</h3>',
        'before'=> Html::a('<i class="glyphicon glyphicon-triangle-right"></i> К проценке', ['#'], ['id' => 'tableToggler', 'class' => 'btn btn-primary', 'onClick' => 'goTo(2); return false;', 'style' => 'display:none']),
        'beforeOptions' => ['class' => 'btn-group-sm'],
        'footer' => false
    ],

]);
?>