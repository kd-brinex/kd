<?php
use yii\Helpers\Html;
use yii\Helpers\Url;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 29.06.15
 * Time: 13:10
 */
//var_dump($model);die;

echo '<ul class="table table-striped">';
    foreach($model as $row) {
//        var_dump($row);die;
        $text= $row['catalog_name'];
        echo '<li>'.Html::a($text,Url::to(['model',
                'name'=>$row['model_name'],
                'model_name'=>$row['model_name'],
                'catalog_name'=>$row['catalog_name'],
                'catalog_code'=>$row['catalog_code'],
//                'user_id'=>$row['user_id'],
        ])).'</li>';
    }
echo '</ul>';

