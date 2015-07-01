<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Collapse;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 29.06.15
 * Time: 11:51
 */
foreach($dataProvider as $name=>$model)
{


        echo Collapse::widget([
            'items' => [
                [
                    'label' => $name,
                   'content'=>$this->render('_index_group_submodel',['model'=>$model]),
                    // Открыто по-умолчанию
                    'options'=>['class'=>"col-xs-12 col-md-6 row"],
//                    'contentOptions' => [  ]
                ],
            ]
        ]);
}
//foreach($dataProvider->models as $m){
//
//
//    echo '<div class="col-lg-3">'.Html::a($m['model_name'],Url::to(['sub'])).'</div>';
//}