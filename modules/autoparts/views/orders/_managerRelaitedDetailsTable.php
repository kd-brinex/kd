<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 23.10.15
 * @time: 14:21
 */
$query = \app\modules\user\models\Orders::find()->where(['related_detail' => $model->id])->all();
    echo \kartik\grid\GridView::widget([
        'id' => 'manager-related-details-grid-'.$model->id,
        'dataProvider' => new \yii\data\ArrayDataProvider([
            'allModels' => $query,
            'sort' => false,
            'key' => 'id'
        ]),
        'layout' => '{items}',
        'headerRowOptions' => [
            'style' => 'display:none'
        ],
        'options' => [
            'style' => 'width:95%;float:right;',
        ],
        'tableOptions' => [
            'style' => 'display:'.(!empty($query) ? 'table' : 'none'),
            'data' => [
                'related-detail' => $model->id
            ],
            'class' => 'related-table'
        ],
        'rowOptions' => [
            'class' => \kartik\grid\GridView::TYPE_WARNING
        ],
        'export' => false,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout'=>true,
            'options' => [
                'id' => 'manager-related-details-grid-'.$model->id.'-pjax',
            ]
        ],
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
                'vAlign' => \kartik\grid\GridView::ALIGN_TOP,
                'rowSelectedClass' => \kartik\grid\GridView::TYPE_SUCCESS,
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
//                'attribute' => 'order_provider_id',
                'format' => 'raw',

                'value' => function($model){
                    if(!($model->provider->name != 'Kd' && $model->provider->name != 'Over' &&
                        $model->provider->name != 'Iksora' && $model->provider->name != 'Moskvorechie'))
                                return false;

                    return \kartik\editable\Editable::widget([
                        'id' => 'provider-order-id-'.$model->id,
                        'contentOptions' => [
                            'class' => 'editable-inline-in-table'
                        ],
                        'name'=>'ID заказа у поставщика',
                        'asPopover' => false,
                        'size'=>'md',
                        'ajaxSettings' => [
                            'url' => '/autoparts/orders/order-provider-status'
                        ],
                        'pluginEvents' => [
                            'editableSuccess' => 'function(event, val, form, data){
                                var status_td = $(event.target).parents("tr").find("td.provider_status_text");
                                if(data.status !== undefined){
                                    status_td.text(data.status);
                                }
                            }'
                        ],
                        'options' => ['class'=>'form-control', 'placeholder'=>'Введите ID заказа у поставщика...'],
                        'formOptions' => [
                            'id' => 'related-detail-'.$model->id.'-form'
                        ],
                        'submitButton' => [
                            'icon' => '<i class="glyphicon glyphicon-ok"></i>',
                        ]
                    ]);
                }
            ],
            [
                'label' => 'Статус поставщика',
                'attribute' => 'providerOrderStatusName.status_name',
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
                    return !isset($model['related_detail']) ? $model['state']['status_name'] : \yii\bootstrap\Html::activeDropDownList($model, 'status', \yii\helpers\ArrayHelper::map(\app\modules\user\models\OrdersState::find()->all(), 'id', 'status_name'), ['class' => 'form-control', 'style'=> 'min-width:125px', 'onChange' => 'updateStatus(this)']);
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
                    'delete' => function($url){
                        return \yii\bootstrap\Html::button('<span class="glyphicon glyphicon-remove"></span>', [
                            'class' => 'btn btn-danger',
                            'onClick' => 'deleteDetail("'.$url.'");'
                        ]);
                    }
                ]
            ]
        ]
    ]);


