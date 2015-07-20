<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 15.07.15
 * Time: 10:41
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
var_dump($vin_info[0]);
$this->params['breadcrumbs']= $params['breadcrumbs'];
$attributes=[
    [
        'label' => 'Производитель',
        'value' => 'TOYOTA'
    ],
    [
        'label' => 'Регион',
        'attribute' => 'catalog'
    ],
    [
        'label' => 'Модель',
        'attribute' => 'model_name'
    ],
    [
        'label' => 'Модификация',
        'attribute' => 'model_code'
    ],
    [
        'label' => 'Комплектация',
        'attribute' => 'compl_code'
    ],
    [
        'label' => 'Производство',
        'attribute' => 'prod'
    ],
    [
        'label' => 'Тип передачи',
        'attribute' => 'tm_en'
    ],
    [
        'label' => 'Коробка передач',
        'attribute' => 'trans_en'
    ],
    [
        'label' => 'Кузов',
        'attribute' => 'body_en'
    ],
    [
        'label' => 'Двигатель',
        'attribute' => 'engine_en'
    ]];
if (!empty($dataModel[0]['f1_name'])){
    $attributes[]=[
        'label' => $dataModel[0]['f1_name'],
        'attribute' => 'f1_en'
    ];}
if (!empty($dataModel[0]['f2_name'])){
    $attributes[]=[
        'label' => $dataModel[0]['f2_name'],
        'attribute' => 'f2_en'
    ];}
if (!empty($dataModel[0]['f3_name'])){
    $attributes[]=[
        'label' => $dataModel[0]['f3_name'],
        'attribute' => 'f3_en'
    ];}
if (!empty($dataModel[0]['f4_name'])){
    $attributes[]=[
        'label' => $dataModel[0]['f4_name'],
        'attribute' => 'f4_en'
    ];}


echo DetailView::widget([
    'model' => $dataModel[0],
//    'template' => "<tr><td>{value}</td></tr>",
    'attributes'=>$attributes,
//        [
//            'label' => '',
//            'attribute' => 'sysopt'
//        ],

//    'catalog',
//        'catalog_code',
//        'compl_code',
//        [
//            'label'=>'desc_en',
//            'format'=>'raw',
//            'value'=>Html::a($model['desc_en'], \yii\helpers\Url::to(array_merge(['page', $model])))
//
//        ],
//        'desc_en',
//        'end_date',
//        'ftype',
//        'illust_no',
//        'ipic_code',
//        'op1',
//        'op2',
//        'op3',
//        'part_groupe',
//        'pic_code',
//        'pic_desc_code',
//        'start_date',
//        [   'label'=>'pic_code',
//            'format'=>'raw',
//            'value'=> Html::a(Html::img(\app\modules\catalog\models\ToyotaQuery::getImageUrl()."/Img/".$model['catalog']."/".$model['rec_num']."/".$model['pic_code'].'.png',['height'=>'300px']), \yii\helpers\Url::to(array_merge(['page', $model])))
//        ]

]);?>