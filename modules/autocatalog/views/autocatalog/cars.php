<?php
use kartik\grid\GridView;
use \yii\bootstrap\Tabs;
use yii\widgets\Breadcrumbs;
use kartik\widgets\Alert;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.10.15
 * Time: 11:22
 */
//var_dump($bread);die;
echo  $this->render('_search_vin',['params'=>$params]);
echo (!empty($params['breadcrumbs']))?Breadcrumbs::widget(['links'=>$params['breadcrumbs']]):'';
echo Alert::widget([
    'options' => [
        'class' => 'alert-info'
    ],
    'body' => 'Выберите регион производства и модель.'
]);
foreach($regions->models as $region) {

    $items []=
        ['label' => Yii::t('autocatalog', $region->region),
            'content' =>'<div class="catalog3">'. GridView::widget([
                'dataProvider' => $provider[$region->region],
                'showHeader' => false,
                'layout' => "{items}\n{pager}",
                'bootstrap' => false,
                'columns' => [

                    [
                        'attribute' => 'family',
                        'format' => 'raw',
                        'value' => function ($model, $key, $index, $widget) {
                            return \yii\helpers\Html::a($model['family'], $model['url']);
//                            return \yii\helpers\Html::a($model['family'], \yii\helpers\Url::to($model['region'].'/'.$model['family'] ));
                        },]
                ],

            ]).'</div>',
            'active' =>  ($region->region==$params['region']),
            'options'=>['class'=>'acatalog-tabs'],
        ];}
    ?>

<?=Tabs::widget(['items'=>$items]);?>


<?php
