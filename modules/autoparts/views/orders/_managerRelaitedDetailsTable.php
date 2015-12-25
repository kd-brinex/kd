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
        'resizableColumns' => false,
        'layout' => '{items}',
//        'headerRowOptions' => [
//            'style' => 'display:none'
//        ],

        'tableOptions' => [
            'style' => 'display:'.(!empty($query) ? 'table' : 'none').'; margin-top:5px; width:99%;float:right',
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
                'header' => 'Оплата',
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
                'label' => 'ID заказа поставщика',
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'order_provider_id',
                'format' => 'raw',
//                'readonly' => function($model){
//                    return !($model['provider']['name'] != 'Kd' && $model['provider']['name'] != 'Over' &&
//                        $model['provider']['name'] != 'Iksora' && $model['provider']['name'] != 'Moskvorechie');
//                },
                'editableOptions' => function($model) {
                    return [
                        'header' => 'ID поставщика',
                        'contentOptions' => [
                            'class' => 'editable-inline-in-table'
                        ],
                        'type' => \kartik\popover\PopoverX::TYPE_SUCCESS,
                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                        'size' => 'md',
                        'options' => ['class'=>'form-control', 'placeholder'=>'Введите ID заказа у поставщика...'],
                        'asPopover' => false,
                        'ajaxSettings' => [
                            'url' => '/autoparts/orders/order-provider-status'
                        ],
                        'submitButton' => [
                            'icon' => '<i class="glyphicon glyphicon-ok"></i>',
                        ],
                        'pluginEvents' => [
                            'editableSuccess' => 'function(event, val, form, data){
                                    var status_td = $(event.target).parents("tr").find("td.provider_status_text > select");
                                    if(data.status !== undefined){
                                        if(!status_td.find("option[value="+data.status+"]").length)
                                            status_td.append("<option value=\""+data.status+"\">"+data.status_text+"</option>");

                                        status_td.val(data.status).change();
                                    }
                                }'
                        ],
                    ];
                }
            ],
            [
                'label' => 'Статус поставщика',
                'attribute' => 'providerOrderStatusName.status_name',
                'contentOptions' => [
                    'class' => 'provider_status_text'
                ],
                'format' => 'raw',
                'value' => function($model, $key){
                    $states = \yii\helpers\ArrayHelper::map($model['allProviderOrderStatusName'], 'status_code', 'status_name');
                    $params = [
                        'class' => 'form-control',
                        'onChange' => 'setOrderProviderState('.$key.', this)',
                        'style' => 'font-size:12px',
                        'prompt' => 'Выбрать статус'
                    ];

                    return \yii\helpers\Html::dropDownList('provider_status', $model['order_provider_status'], $states, $params);
                },
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
                'value' => function($model, $key){
                    return !isset($model['related_detail']) ? $model['state']['status_name'] : \yii\bootstrap\Html::activeDropDownList($model, 'status', $model['stateAll'], ['class' => 'form-control', 'style'=> 'min-width:125px; font-size:12px', 'onChange' => 'updateStatus(this)', 'id' => 'order-status-'.$key.'-field']);
                },
                'vAlign' => 'middle'
            ],
//            [
//                'label' => 'Комментарий',
//                'value' => 'description'
//            ],
            [
                'label' => 'Поставщик',
                'format' => 'raw',
                'value' => function($model){
                    $href = '';
                    switch((int)$model['provider_id']){
                        case 1:
                            $href = 'http://ixora-auto.ru/Shop/Search.html?DetailNumber=';
                            break;
                        case 2:
                            $href = 'http://www.part-kom.ru/new/#/search/0/0/0/';
                            break;
                        case 4:
                            $href = 'https://www.emex.ru/f?detailNum=';
                            break;
                        case 8:
                            $href = 'http://moskvorechie.ru/search.php?artikul=';
                            break;
                    }
                    return \yii\helpers\Html::a('<span class="glyphicon glyphicon-share-alt"></span>', $href.$model['product_article'], ['class' => 'btn '.($href == '' ? 'btn-default disabled' : 'btn-warning'), 'title' => 'Перейти на сайт поставщика', 'target' => '_blank']);
                },
                'hAlign' => 'center',
                'vAlign' => 'middle'
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


