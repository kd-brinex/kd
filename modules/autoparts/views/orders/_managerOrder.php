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
    'pjax' => true,
    'pjaxSettings' => [
        'neverTimeout' => true,
        'options' => [
            'id' => 'manager-order-grid-pjax-container'
        ]
    ],
    'export' => false,
    'resizableColumns' => false,
    'rowOptions' => function($model){
        return ['class' => (empty($model->related_detail) ? '' : GridView::TYPE_WARNING).' ui-droppable ui-draggable-handle'];
    },
    'afterRow' => function($model, $key, $index) {
        return Html::tag('tr',
                    Html::tag('td',
                        $this->render('_managerRelaitedDetailsTable', ['model' => $model])
                    , ['colspan' => 14, 'style' => 'background-color: #d9edf7;padding:0px']
                    )
               );
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
            'label' => 'ID заказа поставщика',
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'order_provider_id',
            'format' => 'raw',
            'readonly' => function($model){
                return !($model['provider']['name'] != 'Kd' && $model['provider']['name'] != 'Over' &&
                $model['provider']['name'] != 'Iksora' && $model['provider']['name'] != 'Moskvorechie');
            },
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

                                        status_td.val(data.status);
                                    }
                                }'
                            ]
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
            'value' => function($model, $key, $index){
                $states = \yii\helpers\ArrayHelper::map($model['allProviderOrderStatusName'], 'status_code', 'status_name');
                $params = [
                    'class' => 'form-control',
                    'onChange' => 'setOrderProviderState('.$key.', this)',
                    'style' => 'font-size:12px',
                    'prompt' => 'Выбрать статус'
                ];

                return Html::dropDownList('provider_status', $model['order_provider_status'], $states, $params);
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
            'template' => '{delete-main}',
            'contentOptions' => ['class' => 'btn-group-sm'],
            'buttons' => [
                'delete-main' => function($url, $model){
                    return \yii\bootstrap\Html::button('<span class="glyphicon glyphicon-remove"></span>', [
                        'class' => 'btn btn-danger',
                        'onClick' => 'deleteMainDetail("'.$url.'");'
                    ]);
                }
            ]
        ]
    ],
    'toolbar' => [
        [
            'content' =>  '<div style="margin:0px 2px;display: inline-block">'.Html::a('<i class="glyphicon glyphicon-refresh"></i> Синхронизировать с <strong>1С</strong>', ['#'], [
                                  'title'=>'Отправить в 1C',
                                  'class'=>'btn '.($order->isIn1C === null ? '' : 'btn-default disabled'),
                                  'onClick' => 'sendTo1C('.$order->id.', this); return false',
                                  'style' => $order->isIn1C === null ? 'background-color:#FFDC0E; color:#EC1421' : ''
                          ]).'</div>'.
                          '<div style="margin:0px 2px;display: inline-block">'.Html::a('<i class="glyphicon glyphicon-rub"></i> Проценка', ['#'], [
                                  'title'=>'Проценка',
                                  'class'=>'btn btn-success',
                                  'onClick' => 'pricing('.$order->id.', this); return false'
                          ]).'</div>',

            'options' => ['class' => 'btn-group-sm']
        ],

    ],
    'panel' => [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-tasks"></i> Позиции</h3>',
        'before'=> '<div style="margin:0px 2px;display: inline-block">'.Html::a('<i class="glyphicon glyphicon-triangle-right"></i> К выборке', ['#'], [
                        'id' => 'tableToggler',
                        'class' => 'btn btn-primary',
                        'onClick' => 'goTo(2); return false;',
                        'style' => 'display:none'
                    ]).'</div>'.
                    '<div style="margin:0px 2px;display: inline-block">'.\kartik\editable\Editable::widget([
                        'id' => 'add-position-button',
                        'name'=>'code',
                        'asPopover' => false,
                        'format' => 'button',
                        'inlineSettings' => [
                            'options' => [
                                'class' => 'add-position-button'
                            ]
                        ],
                        'editableButtonOptions' => [
                            'label' => '<i class="glyphicon glyphicon-plus"></i> Добавить позицию',
                            'class' => 'btn btn-danger'
                        ],
                        'value' => false,
                        'size'=>'md',
                        'showButtons' => false,
                        'ajaxSettings' => [
                            'url' => '/autoparts/orders/pricing',
                        ],
                        'submitButton' => [
                            'icon' => '<i class="glyphicon glyphicon-search"></i>',
                        ],
                        'formOptions' => [
                            'id' => 'add-position-to-order-form',
                        ],
                        'afterInput' => function() use ($order){
                            echo Html::hiddenInput('city_id', $order->store->city_id);
                            echo Html::hiddenInput('order_id', $order->id);
                            echo '<div class="form-group" style="float:left">'.Html::a('Из каталога', '/finddetails#w2-tab2',[
                                'target' => '_blank',
                                'class' => 'btn btn-primary',
                                'data-pjax' => '0'
                            ]).'</div>';
                        },
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'Введите артикул...',
                            'required' => true
                        ],
                        'editableValueOptions' => [
                            'style' => 'width: 0px;opacity: 0;'
                        ],
                        'pluginEvents' => [
                            'editableSubmit' => 'function() {
                                    if($("#add-position-button").val() == "") return false;
                                    var content = $(".modal-body"),
                                        header = $(".modal-header");

                                        if($("#modal-body-2").length > 0) {
                                            $("#modal-body-2").remove();
                                        }

                                        if($("#modal-body-1").length == 0){
                                            content.attr("id","modal-body-1").hide();
                                        } else {
                                            $("#modal-body-1").hide();
                                        }

                                        header.after("<div class=\'loader\'></div>");

                            }',
                            'editableSuccess' => 'function(event, val, form, data) {
                                        $("#tableToggler").show();
                                        $(".loader").remove();
                                        $(".modal-header").after("<div class=\'modal-body\' id=\'modal-body-2\'></div>");
                                        $("#modal-body-2").html(data.table);
                            }'
                        ]
            ]).'</div>',
        'beforeOptions' => ['class' => 'btn-group-sm'],
        'footer' => false
    ],

]);

$this->registerJs('
    $(document).on("pjax:end", function(){
        var editable_819dc9c5 = {"valueIfNull":"\u003Cem\u003E(не задано)\u003C\/em\u003E","asPopover":false,"placement":"right","target":".kv-editable-button","displayValueConfig":[],"showAjaxErrors":true,"ajaxSettings":{"url":"\/autoparts\/orders\/pricing"},"submitOnEnter":true};
        jQuery("#add-position-button-cont").editable(editable_819dc9c5);


        if($("#modal-body-2").length > 0){
            $("#tableToggler").show();
        }
        jQuery("#add-position-button-cont").on(\'editableSubmit\', function() {
                                    var content = $(".modal-body"),
                                        header = $(".modal-header");

                                        if($("#modal-body-2").length > 0) {
                                            $("#modal-body-2").remove();
                                        }

                                        if($("#modal-body-1").length == 0){
                                            content.attr("id","modal-body-1").hide();
                                        } else {
                                            $("#modal-body-1").hide();
                                        }

                                        header.after("<div class=\'loader\'></div>");

                            });
        jQuery("#add-position-button-cont").on(\'editableSuccess\', function(event, val, form, data) {
                                        $("#tableToggler").show();
                                        $(".loader").remove();
                                        $(".modal-header").after("<div class=\'modal-body\' id=\'modal-body-2\'></div>");
                                        $("#modal-body-2").html(data.table);
                            });
    });
', $this::POS_READY);


