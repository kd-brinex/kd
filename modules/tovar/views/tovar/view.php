<?php
use yii\bootstrap\Collapse;
use \yii\grid\GridView;
use yii\widgets\ListView;

\app\modules\tovar\tovarAsset::register($this);
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 08.01.15
 * Time: 17:28
 */

$this->title = $tovarProvider->models[0]['name'];
$category = $tovarProvider->models[0]['tip_id'];
$this->params['breadcrumbs'][] = ['label'=>$category,'url'=>['/tovar/tovar/category','tip_id'=>$category]];
$this->params['breadcrumbs'][] = $this->title;

echo ListView::widget([
    'dataProvider' => $tovarProvider,
    'layout' => "{items}",
    'options' => ['tag'=>'div','class'=>'offer-page'],
    'itemView' => function ($model) {
        return $this->render('tovar_block_view', ['model' => $model]);
    },
]);

echo Collapse::widget([
    'items' => [
        [
            'label' => 'Спецификация.',
            'content' =>
                GridView::widget([
                    'layout' => "{items}",
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                          'class' => 'yii\grid\SerialColumn',
                          'header' => '№'
                        ],
                        [
                          'attribute' => 'title',
                          'label' => 'Характеристика'
                        ],
                        'value_char',
                    ]
                ]),
            // Открыто по-умолчанию
            'contentOptions' => [ 'class' => 'in'],
        ],
    ]
]);
