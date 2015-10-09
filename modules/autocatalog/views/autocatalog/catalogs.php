<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.10.15
 * Time: 15:58
 */

use kartik\grid\GridView;
use yii\widgets\DetailView;
use \yii\helpers\Html;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.10.15
 * Time: 11:22
 */

//var_dump($info->models);die;
?>
<div class="auto-info">
    <?= DetailView::widget([
            'model' => $info->models[0],
            'template' => '<tr><th>{label}</th><td class="upper">{value}</td></tr>',
            'attributes' => [
                'cat_code',
                'marka',
                'family',
                'cat_name',

                [
                    'attribute' => 'vehicle_type',
                    'format'=>'raw',
                    'value' => Html::tag('span',Yii::t('autocatalog', $info->models[0]->vehicle_type),['class'=>'upper']),

                ],

            ],
        ]
    ); ?>
</div>
<div class="models">
    <?= Html::beginForm($info->models[0]->cat_code.'/'.$info->models[0]->cat_folder,'post',['name'=>'catalog']);?>
    <?= GridView::widget([
        'dataProvider' => $provider,
//        'showHeader' => false,
        'layout' => "{items}\n{pager}",
        'panelTemplate' => '<div class="panel {type}">{sort}</div>',
//        'bootstrap' =>false,
        'columns' => [
            ['attribute' => 'name',
                'label' => 'Характеристики',
                'value' => function ($model, $key, $index, $widget) {
                    return Yii::t('autocatalog', $model['name']);

                }
            ],
            [
                'attribute' => 'value',
                'format' => 'raw',
                'label' => 'Варианты',
                'value' => function ($model, $key, $index, $widget) {
//                    var_dump($model);die;

                    $key=explode(';', $model['key']);
                    $value=explode(';', $model['value']);
//                    var_dump(array_combine($key,$value));die;
                    $val=array_combine($key,$value);

//                    foreach($a_value as $v){$val[$v]=$v;}
//                    \yii\helpers\ArrayHelper::index($a_value,);

                    $html = Html::radioList($model['type_code'], $key[0], $val, []);
                    return $html;
                },],


        ],

    ]); ?>
</div>
<?= Html::submitButton('Загрузить');?>
<?= Html::endForm();?>