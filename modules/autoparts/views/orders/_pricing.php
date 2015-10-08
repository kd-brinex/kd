<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 29.09.15
 * @time: 10:25
 */
use \kartik\grid\GridView;
use yii\helpers\Html;
?>

<?=GridView::widget([
    'id' => 'pricing-order-grid',
    'dataProvider' => $model,
    'responsive' => true,
    'hover' => true,
    'showPageSummary' => true,
    'resizableColumns' => false,

    'toolbar' => [
        [
            'options' => ['class' => 'btn-group-sm']
        ],
    ],
    'panel' => [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-tasks"></i> Позиции</h3>',
        'before' => Html::a('<i class="glyphicon glyphicon-triangle-left"></i> К позициям', ['#'], ['class' => 'btn btn-primary', 'onClick' => 'goTo(1); return false']),
        'beforeOptions' => ['class' => 'btn-group-sm'],
        'footer' => false,
    ],
    'columns' => [

        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'expandIcon' => '<i class="glyphicon glyphicon-chevron-down"></i>',
            'collapseIcon' => '<i class="glyphicon glyphicon-chevron-up"></i>',
            'detailAnimationDuration' => 'fast',
            'value' => function () {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model) use ($offersData, $orderedDetails){
                $model['code'] = strpos($model['code'], '|r') ? explode('|',$model['code'])[0] : $model['code'];
                $offersData = !empty($offersData[$model['code']]) ? $offersData[$model['code']] : [];
                return Yii::$app->controller->renderPartial('_pricingCollapse', ['offers' => $offersData, 'orderedDetail' => $orderedDetails[0]]);
            },
            'detailOptions'=>[
                'class'=> 'expanded-row',
            ],
        ],
        [
            'label' => 'Артикул',
            'attribute' => 'code',
            'contentOptions' => function($model){
                return strpos($model['code'], '|r') ? ['style' => 'background-color:#FF5252 !important'] : [];
            },
            'value' => function($model){
                return strpos($model['code'], '|r') ? explode('|',$model['code'])[0] : $model['code'];
            }
        ],
        [
            'label' => 'Производитель',
            'attribute' => 'manufacture',
            'format' => 'raw',
            'contentOptions' => function($model){
                return $model['manufacture'] == '|r' || strpos($model['manufacture'], '|r') ? ['style' => 'background-color:#FFC7C7 !important'] : [];
            },
            'value' => function($model){
                return $model['manufacture'] == '|r' || strpos($model['manufacture'], '|r') ? explode('|',$model['manufacture'])[0] : $model['manufacture'];
            }
        ],
        [
            'label' => 'Название',
            'attribute' => 'name',
            'contentOptions' => function($model){
                return strpos($model['name'], '|r') ? ['style' => 'background-color:#FFC7C7 !important'] : [];
            },
            'value' => function($model){
                return strpos($model['name'], '|r') ? explode('|',$model['name'])[0] : $model['name'];
            },
        ],
        [
            'label' => 'Кол-во',
            'contentOptions' => function($model){
                $color = '';
                if(($string = explode('|',$model['quantity'])) && !empty($string[1])){
                    $color = $string[1] == 'r' ? '#FFC7C7 !important' : ($string[1] == 'g' ? '#CAFFA2 !important' : '');
                }
                return ['style' => 'background-color:'.$color];
            },
            'value' => function($model){
                return (int)$model['quantity'];
            }
        ],
        [
            'label' => 'Кол-во в заказ',
            'attribute' => 'quantity',
            'format' => 'raw',
            'contentOptions' => function(){
                return ['class' => 'quantity'];
            },
            'value' => function($model){
                return Html::input('number', 'quantity', 1, ['class' => 'form-control', 'min' => 1, 'max' => (int)$model['quantity']]);
            }
        ],
        [
            'label' => 'Цена',
            'attribute' => 'price',
            'contentOptions' => function($model){
                $color = '';
                if(($string = explode('|',$model['price'])) && !empty($string[1])){
                    $color = $string[1] == 'r' ? '#FFC7C7 !important' : ($string[1] == 'g' ? '#CAFFA2 !important' : '');
                }
                return ['style' => 'background-color:'.$color];
            },
            'value' => function($model){
                return (int)$model['price'];
            }
        ],
        [
            'label' => 'Срок',
            'attribute' => 'srokmax',
            'contentOptions' => function($model){
                $color = '';
                if(($string = explode('|',$model['srokmax'])) && !empty($string[1])){
                    $color = $string[1] == 'r' ? '#FFC7C7 !important' : ($string[1] == 'g' ? '#CAFFA2 !important' : '');
                }
                return ['style' => 'background-color:'.$color];
            },
            'value' => function($model){
                return (int)$model['srokmax'];
            }
        ],
        [
            'label' => 'Поставщик',
            'attribute' => 'provider',
            'contentOptions' => function($model){
                return strpos($model['pid'], '|r') ? ['style' => 'background-color:#FFC7C7 !important'] : [];
            },
            'value' => function($model){
                return strpos($model['provider'], '|r') ? explode('|',$model['provider'])[0] : $model['provider'];
            },
        ],
        [
            'label' => 'Поставщик',
            'format' => 'raw',
            'value' => function($model){
                $href = '';
                switch((int)$model['pid']){
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
                $model['code'] = strpos($model['code'], '|r') ? explode('|',$model['code'])[0] : $model['code'];
                return Html::a('<span class="glyphicon glyphicon-share-alt"></span>', $href.$model['code'], ['class' => 'btn '.($href == '' ? 'btn-default disabled' : 'btn-warning'), 'title' => 'Перейти на сайт поставщика', 'target' => '_blank']);
            },
            'hAlign' => 'center',
            'vAlign' => 'middle'
        ],
        [
            'class' => '\kartik\grid\ActionColumn',
            'template' => '{in-order}',
            'contentOptions' => ['class' => 'btn-group-sm'],
            'buttons' => [
                'in-order' => function($url, $model) use ($orderedDetails){
                    $order = [
                        'order_id' => $orderedDetails[0]->order_id,
                        'detail_id' => $orderedDetails[0]->id
                    ];
                    return Html::button('<span class="glyphicon glyphicon-plus"></span> В ЗАКАЗ', [
                        'class' => 'btn btn-primary',
                        'onClick' => 'inOrder(this, "'.$url.'", '.\yii\helpers\Json::encode(array_merge($model, $order)).')'
                    ]);
                }
            ]
        ]
    ]
])?>
