<?php
/**
 * @author: Eugene
 * @date: 21.12.15
 * @time: 9:56
 */
use \yii\helpers\Html;
use yii\helpers\Url;


$cat = '';
$to = '';

foreach($catalog as $key => $marka){
    $cat .= Html::tag('div', Html::a($marka['prop']['marka'], Url::toRoute('/autocatalogs/'.$key.'/'.$marka['prop']['region'])), ['class'=>'autocatalog_marka']);
    $to .= Html::tag('div', Html::a($marka['prop']['marka'], Url::toRoute('to/'.$key)), ['class'=>'autocatalog_marka']);
}

echo \yii\bootstrap\Tabs::widget([
    'items' => [
        [
            'label' => 'Поиск запчастей по артикулу',
            'content' => $this->render('finddetails', ['provider' => $provider, 'columns' => $columns])
        ],
        [
            'label' => 'Поиск запчастей по VIN',
            'content' => $this->render('@app/modules/autocatalog/views/autocatalog/_search_vin',['params' => $params]),
            'options'=>['class'=>'acatalog-tabs', 'tag' => 'div'],
        ],
        [
            'label' => 'Поиск запчастей по каталогу',
            'content' => $cat,
            'options'=>['class'=>'acatalog-tabs','tag' => 'div'],
        ],
    ],
    'options' => [
        'style' => 'margin-bottom:20px'
    ]
]);