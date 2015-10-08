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
    'resizableColumns' => false,
    'rowOptions' => function($model){
        return ['class' => empty($model->related_detail) ? GridView::TYPE_SUCCESS : GridView::TYPE_WARNING];
    },
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
            'label' => 'Кол-во',
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
            'contentOptions' => ['class' => 'detailStatus'],
            'format' => 'raw',
            'value' => function($model){
                return !isset($model['related_detail']) ? $model['state']['status_name'] : Html::activeDropDownList($model, 'status', \yii\helpers\ArrayHelper::map(\app\modules\user\models\OrdersState::find()->all(), 'id', 'status_name'), ['class' => 'form-control', 'style'=> 'min-width:125px', 'onChange' => 'updateStatus(this)']);
            },
            'vAlign' => 'middle'
        ],
        [
            'label' => 'Комментарий',
            'value' => 'description'
        ],
        [
            'class' => '\kartik\grid\ActionColumn',
            'header' => '',
            'template' => '{delete}',
            'contentOptions' => ['class' => 'btn-group-sm'],
            'buttons' => [
                'delete' => function($url, $model){
                    return isset($model['related_detail']) ? Html::button('<span class="glyphicon glyphicon-remove"></span>', [
                        'class' => 'btn btn-danger',
                        'onClick' => 'deleteDetail("'.$url.'")'
                    ]) : '';
                }
            ]
        ]
    ],
    'toolbar' => [
        [
            'content' =>  Html::a('<i class="glyphicon glyphicon-share-alt"></i> Отправить в <strong>1С</strong>', ['#'], [
                                  'title'=>'Проценка',
                                  'class'=>'btn '.($order->isIn1C === null ? '' : 'btn-default disabled'),
                                  'onClick' => 'sendTo1C('.$order->id.', this); return false',
                                  'style' => $order->isIn1C === null ? 'background-color:#FFDC0E; color:#EC1421' : ''
                          ]).
                          Html::a('<i class="glyphicon glyphicon-rub"></i> Проценка', ['#'], [
                                  'title'=>'Проценка',
                                  'class'=>'btn btn-success',
                                  'onClick' => 'pricing('.$order->id.', this); return false'
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