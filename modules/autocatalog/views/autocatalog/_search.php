<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 19.08.15
 * Time: 15:12
 */
use yii\jui\Tabs;
use yii\helpers\Url;
use yii\helpers\Html;
$cat = '';
$to = '';
foreach($catalog as $key => $marka){
    $cat .= Html::tag('div', Html::a($marka['prop']['marka'], Url::toRoute('/autocatalogs/'.$key.'/'.$marka['prop']['region'])), ['class'=>'autocatalog_marka']);
    $to .= Html::tag('div', Html::a($marka['prop']['marka'], Url::toRoute('to/'.$key)), ['class'=>'autocatalog_marka']);
}
$items = [
    [
        'label' => 'VIN',
        'content' => $this->render('_search_vin',['params' => $params]),
        'options'=>['class'=>'acatalog-tabs', 'tag' => 'div'],
    ],
    [
        'label' => 'Автокаталог',
        'content' => $cat,
        'options'=>['class'=>'acatalog-tabs','tag' => 'div'],
    ],

];?>
<div>
    <?=Tabs::widget([
        'items' => $items,
    ]);?>
</div>