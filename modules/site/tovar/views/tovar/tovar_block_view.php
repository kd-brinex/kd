<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 10.01.15
 * Time: 15:39
 */
echo yii\widgets\DetailView::widget([
    'model'=>$model,
//    'template'=>'<tr><th>{label}</th><td>{value}</td></tr>',
    'template'=>'<div style="text-align:center">{value}</div>',
    'class' => 'table table-striped table-bordered detail-view',
    'attributes' => [
        [
            'attribute'=>'image',
            'value'=>$model->image,
            'format' => ['image',['width'=>'150','height'=>'150']],
        ],
        'name',

        'price'
    ]]);

