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

    <?= GridView::widget([
        'dataProvider' => $podbor,
        'columns'=>[

            [
                'label'=>'Найденные автокаталоги',
                'format'=>'raw',
                'value'=> function ($model, $key, $index, $widget)use($params) {
                    return Html::a(Html::button('Перейти к подбору автозапчастей - '.strtoupper($params['marka']).' '.$params['family'].'. ' .$model['cat_folder']. ' ('.$params['option'].')',['class'=>"btn btn-success",'id'=>'catalog_button']),\yii\helpers\Url::to(base64_encode($params['option']).'/'.$model['cat_folder']));

                },
            ]
        ]

    ]);?>


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


