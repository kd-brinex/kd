<?php
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 10.01.15
 * Time: 15:39
 */
echo DetailView::widget([
    'model' => $model,
//    'template'=>'<tr><th>{label}</th><td>{value}</td></tr>',
//    'template'=>'<div>{value}</div>',
//    'class' => 'div tovar-image',
    'template' => function ($attribute, $index, $this) {
        return $this->render('detail_view_template', ['attribute' => $attribute, 'index' => $index, 'widget' => $this]);
    },
    'attributes' => [
        ['attribute' => 'name',
            'template' => '<div><h1>{value}</h1></div>',
            'value' =>  $model->name ,
            'format' => ['raw'],],

        [
            'attribute' => 'image',
            'template' => '<div {class}>{value}</div>',
            'value' => $model->bigimage,
            'format' => ['image', ['width' => '300', 'height' => '300']],
            'class' => 'tovar-image',
        ],
    ['attribute'=>'price',
        'template' => '<div {class}>{label}:{value}</div>',
        'value' =>$model->price,
        'class' => 'tovar-price',
        'format' => ['currency',
            'thousandSeparator' => ' ',
            'decimalSeparator' => '.',]
        ],
    ]]);


