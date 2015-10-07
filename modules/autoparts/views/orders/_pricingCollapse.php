<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 02.10.15
 * @time: 14:31
 */

use \kartik\grid\GridView;
use yii\helpers\Html;
?>

<?=GridView::widget([
    'id' => 'collapse-order-detail-grid-'.rand(5,10),
    'dataProvider' => $offers,
    'responsive' => true,
    'hover' => true,
    'striped' => true,
    'resizableColumns' => false,
    'toolbar' => [
        [
            'options' => ['class' => 'btn-group-sm']
        ],
    ],
    'panel' => [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-tasks"></i> Другие предложения</h3>',
        'beforeOptions' => ['class' => 'btn-group-sm'],
        'footer' => false,
    ],
    'columns' => [
        [
            'label' => 'Артикул',
            'attribute' => 'code',
            'value' => function($model){
                return strpos($model['code'], '|r') ? explode('|', $model['code'])[0] : $model['code'];
            },
            'group' => true,
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
            },
            'group' => true,
            'subGroupOf' => 0
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
            'group' => true,
            'subGroupOf' => 0
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
            },
            'group' => true,
            'subGroupOf' => 0,
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
            },
            'group' => true,
            'subGroupOf' => 0
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
            },
            'group' => true,
            'subGroupOf' => 0
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
            'group' => true,
            'subGroupOf' => 0
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
                'in-order' => function($url, $model) use ($orderedDetail){
                    $order = [
                        'order_id' => $orderedDetail->order_id,
                        'detail_id' => $orderedDetail->id
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
