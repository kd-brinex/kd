<?php
use kartik\grid\GridView;
use yii\i18n\I18N;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.10.15
 * Time: 11:22
 */

//var_dump($models);die;
?>
<div class="models">
    <?= GridView::widget([
        'dataProvider'=>$provider,
        'filterModel'=>$filterModel,
//        'showHeader' => false,
        'layout' =>"{items}\n{pager}",
        'panelTemplate'=>'<div class="panel {type}">{sort}</div>',

//        'bootstrap' =>false,
        'columns' =>[
            [
                'attribute'=>'family',
                'format'=>'raw',
                'label'=>'Название',
                'value'=>function ($model, $key, $index, $widget) {
                    return \yii\helpers\Html::a($model['cat_name'],\yii\helpers\Url::to($model['family'].'/'.$model['cat_code']));
                },],
    [   'attribute'=>'from',
        'label'=>'Начало производства',
        'format'=>'date',
    ], [   'attribute'=>'to',
                'format'=>'date',
        'label'=>'Окончание производства',
    ],
            ['attribute'=>'region',
                'filterType' => GridView::FILTER_SELECT2,
                'filter'=>[''=>'Все','AUS'=>'Австралия','EUR'=>'Европа','CIS'=>'СНГ','GEN'=>'Общие','HAC'=>'Канада','HMA'=>'США','HMI'=>'Индия','MES'=>'Средний Восток'],

                ],
        ],

    ]);?>
</div>