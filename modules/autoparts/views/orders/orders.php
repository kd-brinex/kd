<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\growl\Growl;
$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1>Заказы</h1>

<div class="col-xs-12">
    <?php
   ?>
    <?=GridView::widget([
        'id'=> 'orders-manage-grid',
        'dataProvider' => $orders,
        'responsive'=>true,
        'hover'=>true,
        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true,
        ],
        'columns' => [
            [
                'label' => 'ID',
                'attribute' => 'id'
            ],
            [
                'class' => 'kartik\grid\ExpandRowColumn',
                'expandAllTitle' => 'Покупатель',
                'expandIcon' => '<span class="123 glyphicon glyphicon-user"></span>',
                'collapseIcon' => '<span class="1123 glyphicon glyphicon-minus"></span>',
                'expandOneOnly' => true,
                'detail' => function($model){
                    return '<div class="orders-user-block col-sm-10 col-sm-offset-1">
                                <div class="col-sm-3"><span class="label">Имя</span>'.($model['name'] != '' ? $model['name'] : ($model['userData']['name'] != '' ? $model['userData']['name'] : 'Не задано' )).'</div>
                                <div class="col-sm-3"><span class="label">E-mail</span>'.($model['email'] != '' ? $model['email'] : ($model['userData']['public_email'] != '' ? $model['userData']['public_email'] : 'Не задано' )).'</div>
                                <div class="col-sm-2"><span class="label">Телефон</span>'.($model['telephone'] != '' ? $model['telephone'] : ($model['userData']['telephone'] != '' ? $model['userData']['telephone'] : 'Не задано' )).'</div>
                                <div class="col-sm-2"><span class="label">Город</span>'.($model['location'] != '' ? $model['location'] : ($model['userData']['location'] != '' ? $model['userData']['location'] : 'Не задано' )).'</div>
                            </div>';
                },
                'detailAnimationDuration' => 'fast',

                'value' => function() {
                    return GridView::ROW_COLLAPSED;
                },
                'contentOptions' => [
                    'class' => ' user',

                ]
            ],
            [
                'label' => 'ID/Артикул',
                'format' => 'raw',
                'value' => function($model){
                    $string = '<strong style="color:#9D9D9D">'.($model['product_id']?$model['product_id']:'Нет').'</strong> / <strong style="color:#9D9D9D">'.($model['product_article']?$model['product_article']:'Нет').'</strong>';
                    return $string;
                }
            ],
            [
                'label' => 'Произ-ль',
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
                'label' => 'Reference',
                'value' => 'reference'
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'label' => 'Статус',
                'attribute' => 'status',
                'editableOptions'=> function ($model, $key, $index) {
                    return [
                        'header'=>'статус',
                        'size'=>'md',
                        'inputType' => 'dropDownList',
                        'data' => [
                            '1' => 'Отказано',
                            '2' => 'В работе'
                        ],
                        'submitOnEnter' => true
                    ];
                }

            ],
            [
                'label' => 'Дата',
                'value' => 'datetime'
            ],
//            [
//                'class' => 'kartik\grid\EditableColumn',
//                'label' => 'Платеж',
//                'attribute' => 'pay_datetime',
//                'editableOptions'=> function ($model, $key, $index) {
//                    return [
//                        'header'=>'дату платежа',
//                        'size'=>'md',
//                        'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
//                        'widgetClass' => '\kartik\widgets\DateTimePicker',
//                        'options' => [
//                           'value' => /*$model['pay_datetime']*/'',
//                           'pluginOptions' => [
//                               'autoclose'=>true,
//                               'format' => 'dd.mm.yyyy hh:mm'
//                           ]
//                        ],
//                        'formOptions' => [
//                            'action' => 'update'
//                        ],
//
//
//                     ];
//                },
//                'value' => 'normalizeDate'
//
//
//            ],
            [
                'class' => 'kartik\grid\ExpandRowColumn',
                'expandAllTitle' => 'Место доставки',
                'expandIcon' => '<span class="glyphicon glyphicon-flag"></span>',
                'collapseIcon' => '<span class="glyphicon glyphicon-minus"></span>',
                'expandOneOnly' => true,
                'detail' => function($model){
                    return '<div class="orders-user-block col-sm-10 col-sm-offset-1">
                                <div class="col-sm-3"><span class="label">Название</span>'.($model['store']['name'] != '' ? $model['store']['name'] : 'Не задано').'</div>
                                <div class="col-sm-3"><span class="label">Адрес</span>'.($model['store']['addr'] != '' ? $model['store']['addr'] : 'Не задано').'</div>
                                <div class="col-sm-2"><span class="label">Телефон</span>'.($model['store']['tel'] != '' ? $model['store']['tel'] : 'Не задано').'</div>
                                <div class="col-sm-2"><span class="label">Город</span>'.($model['location'] != '' ? $model['location'] : ($model['userData']['location'] != '' ? $model['userData']['location'] : 'Не задано' )).'</div>
                            </div>';
                },
                'detailAnimationDuration' => 'fast',
                'value' => function() {
                    return GridView::ROW_COLLAPSED;
                }
            ],
            [
                'label' => 'Описание',
                'value' => 'description'
            ],
            [
                'class' => '\kartik\grid\CheckboxColumn',
                'hiddenFromExport' => true,
                'checkboxOptions' => function($model){
                    return ['disabled' => !(boolean)$model['provider_id']];
                }
            ]

        ],
//        'toolbar' => [
//            [
//                'content'=>
//                    Html::button('<i class="glyphicon glyphicon-plus"></i>', [
//                        'type' =>'button',
//                        'title'=> 'Add Book',
//                        'class'=>'btn btn-success'
//                    ]) . ' '.
//                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['grid-demo'], [
//                        'class' => 'btn btn-default',
//                        'title' => 'Reset Grid'
//                    ]),
//            ],
//            '{export}',
//            '{toggleData}'
//        ],
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-shopping-cart"></i> Заказы</h3>',
            'type'=>'success',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Отправить заказ', ['create'], ['class' => 'btn btn-success', 'onClick' => 'sendAllToProvider(this); return false']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Сбросить', ['index'], ['class' => 'btn btn-info']),
        ],

    ]);


?>
</div>

