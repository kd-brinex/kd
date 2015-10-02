<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.10.15
 * Time: 15:58
 */

use kartik\grid\GridView;
use \yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 01.10.15
 * Time: 11:22
 */

//var_dump($provider);die;
?>
<div class="models">
    <?= GridView::widget([
        'dataProvider'=>$provider,
//        'showHeader' => false,
        'layout' =>"{items}\n{pager}",
        'panelTemplate'=>'<div class="panel {type}">{sort}</div>',
//        'bootstrap' =>false,
        'columns' =>[
            ['attribute'=>'name',
                'label' => 'Характеристики',
            ],
            [
                'attribute'=>'value',
                'format'=>'raw',
                'label'=>'Варианты',
                'value'=>function ($model, $key, $index, $widget) {
                    $a_value=explode(';',$model['value']);
                        $html=Html::radioList($model['type_code'],null,$a_value,[]);
                    return $html;
                },],


        ],

    ]);?>
</div>