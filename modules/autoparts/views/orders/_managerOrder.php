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
    'pjax'=>true,

    'export' => false,
    'resizableColumns' => false,
    'rowOptions' => function($model){
        return ['class' => empty($model->related_detail) ? '' : GridView::TYPE_WARNING];
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
            'class' => '\kartik\grid\CheckboxColumn',
            'vAlign' => GridView::ALIGN_TOP,
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'checkboxOptions' => function($model){
                return [
                    'value' => $model['id'],
                    'checked' => $model['is_paid'],
                    'onClick' => 'updatePaidStatus(this)'
                ];
            }
        ],
        [
            'label' => 'Поставщик',
            'value' => 'provider.name'

        ],
        [
            'label' => 'ID поставщика',
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'order_provider_id',
            'format' => 'raw',
            'editableOptions' => function($model) {
                $format = ($model['provider']['name'] != 'Kd' && $model['provider']['name'] != 'Over' &&
                           $model['provider']['name'] != 'Iksora' && $model['provider']['name'] != 'Moskvorechie')
                           ? 'link' : 'button';
                return [
                            'header' => 'ID поставщика',
                            'type' => \kartik\popover\PopoverX::TYPE_SUCCESS,
                            'format' => $format,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'size' => 'md',
                            'ajaxSettings' => [
                                'url' => '/autoparts/orders/order-provider-status'
                            ],
                            'editableButtonOptions' => [
                                'style' => 'display:none',
                            ],
                            'pluginEvents' => [
                                'editableSuccess' => 'function(event, val, form, data){
                                    var status_td = $(event.target).parents("tr").find("td.provider_status_text");
                                    if(data.status !== undefined){
                                        status_td.text(data.status);
                                    }
                                }'
                            ]
                        ];
            }
        ],
        [
            'label' => 'Статус поставщика',
            'attribute' => 'providerOrderStatusName.status_name',
//            'value' => function($model){
//                if(isset($model['providerOrderStatusName'][0]))
//                var_dump($model['providerOrderStatusName'][0]->status_name);
//            },
            'contentOptions' => [
                'class' => 'provider_status_text'
            ]
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

