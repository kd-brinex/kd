<?php
use kartik\grid\GridView;
use \yii\bootstrap\Tabs;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.10.15
 * Time: 11:22
 */
foreach($regions->models as $model) {
    $items []=
        ['label' => Yii::t('autocatalog', $model->region),
            'content' =>'<div class="catalog">'. GridView::widget([
                'dataProvider' => $provider[$model->region],
                'showHeader' => false,
                'layout' => "{items}\n{pager}",
                'bootstrap' => false,
                'columns' => [

                    [
                        'attribute' => 'family',
                        'format' => 'raw',
                        'value' => function ($model, $key, $index, $widget) {
                            return \yii\helpers\Html::a($model['family'], \yii\helpers\Url::to($model['marka'] . '/' . $model['family']));
                        },]
                ],

            ]).'</div>',
//            'active' => true,
            'options'=>['class'=>'acatalog-tabs'],
        ];}
    ?>

<?=Tabs::widget(['items'=>$items]);?>


<?php
