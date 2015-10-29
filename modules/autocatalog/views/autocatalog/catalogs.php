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
use yii\widgets\Breadcrumbs;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.10.15
 * Time: 11:22
 */
//echo $params['option'];
//var_dump($params);
echo (!empty($params['breadcrumbs']))?Breadcrumbs::widget(['links'=>$params['breadcrumbs']]):'';
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
    <?= Html::beginForm('','post',['name'=>'catalog']);?>
    <?= GridView::widget([
        'dataProvider' => $provider,
//        'showHeader' => false,
        'layout' => "{items}\n{pager}",
        'panelTemplate' => '<div class="panel {type}">{sort}</div>',
//        'bootstrap' =>false,
        'columns' => [
            ['attribute' => 'name',
                'label' => 'Характеристики',
                'value' => function ($model, $key, $index, $widget)  {
                    return Yii::t('autocatalog', $model['name']);

                }
            ],
            [
                'attribute' => 'value',
                'format' => 'raw',
                'label' => 'Варианты',
                'value' => function ($model, $key, $index, $widget) use ($params) {
                    $keys=explode(';', $model['key']);
                    $values=explode(';', $model['value']);

//                    $select=(!empty($params['option']))?explode('|',$params['option'])[$index]:$key[0];

                        if (!empty($params['option'])){
                            $options=explode('|',$params['option']);
                            $select=(isset($options[$index]))?$options[$index]:$keys[0];

}
                    else{$select=$keys[0];}
                    $val=array_combine($keys,$values);
                    $html = Html::radioList($model['type_code'], $select, $val, []);
                    return $html;
                },],


        ],

    ]); ?>

<?= Html::submitButton('Найти каталог');?>
<?= Html::endForm();?>
</div>
<?= GridView::widget([
    'dataProvider' => $podbor,
    'columns'=>[

//    'cat_code',
//    'cat_folder',
//    'option',
    [
        'label'=>'Автокаталог',
        'format'=>'raw',
        'value'=> function ($model, $key, $index, $widget)use($params) {
            return Html::a('Автокаталог - ' .$model['cat_folder'].'. '.$params['marka'].' '.$params['family']. ' ('.$params['option'].')',\yii\helpers\Url::to(base64_encode($params['option']).'/'.$model['cat_folder']));
//            return Html::a('Каталог',\yii\helpers\Url::to($model['cat_code'].'/'.$model['cat_folder'].'/'.$params['option']));
},
    ]
    ]

    ]);?>
