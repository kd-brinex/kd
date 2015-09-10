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
$cat='';
$to='';
foreach($catalog as $key=>$marka)
{
    $cat .= Html::tag('div',Html::a($marka->prop['marka'],Url::toRoute($key)),['class'=>'autocatalog_marka']);
    $to .= Html::tag('div',Html::a($marka->prop['marka'],Url::toRoute('to/'.$key)),['class'=>'autocatalog_marka']);
}
?>
<div>
            <?=Tabs::widget([
    'items' => [
        [
            'label' => 'Номер детали',
            'content' => $this->render('_search_details',['params'=>$params]),
            'active' => true,
            'options'=>['class'=>'acatalog-tabs'],
        ],
        [
            'label' => 'VIN',
            'content' => $this->render('_search_vin',['params'=>$params]),
            'options'=>['class'=>'acatalog-tabs'],
        ],
        [
            'label' => 'Автокаталог',
            'content' => $cat,
            'options'=>['class'=>'acatalog-tabs'],

        ],
        [
            'label' => 'Каталог ТО',
            'content' => $to,
            'options'=>['class'=>'acatalog-tabs'],

        ],
    ]
]);?>
</div>