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

echo (!empty($params['breadcrumbs']))?Breadcrumbs::widget(['links'=>$params['breadcrumbs']]):'';
?>

<div class="auto-info">
    <?= GridView::widget([
        'dataProvider' => $podbor,
        'columns'=>[

//    'cat_code',
//    'cat_folder',
//    'option',
            [
                'label'=>'Найденные автокаталоги',
                'format'=>'raw',
                'value'=> function ($model, $key, $index, $widget)use($params) {
                    return Html::a(Html::button('Перейти к подбору автозапчастей - '.strtoupper($params['marka']).' '.$params['family'].'. ' .$model['cat_folder']. ' ('.$params['option'].')',['class'=>"btn btn-success",'id'=>'catalog_button']),\yii\helpers\Url::to(base64_encode($params['option']).'/'.$model['cat_folder']));
//            return Html::a('Каталог',\yii\helpers\Url::to($model['cat_code'].'/'.$model['cat_folder'].'/'.$params['option']));
                },
            ]
        ]

    ]);?>
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
                    $key[0]='';
                    $values[0]='Unknown';
                    $keys=array_merge($key,explode(';', $model['key']));
                    $values=array_merge($values,explode(';', $model['value']));

//                    $select=(!empty($params['option']))?explode('|',$params['option'])[$index]:$key[0];
//                    var_dump($keys);die;
                $select=$keys[0];
                        if (!empty($params['option'])){
                            $option=str_replace('  ','|',$params['option']);
                            while (strpos($option,'||')>0){$option=str_replace('||','|',$option);}
                            $options=explode('|',$option);
                            $select=(!empty($options[$index]))?$options[$index]:$select;

}

                    $val=array_combine($keys,$values);
                    $html = Html::radioList($model['type_code'], $select, $val, []);
                    return $html;
                },],


        ],

    ]); ?>

<?= Html::submitButton('Изменить конфигурацию',['id'=>'submit']);?>
<?= Html::endForm();?>
</div>

<?php
Yii::$app->view->registerJs(
'
      $("#submit").click(function(){
      $(".btn-success").attr("disabled","disabled");
      $(".btn-success").animate({
        opacity: 0
      }, 1500)});
'
);


