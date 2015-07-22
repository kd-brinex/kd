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

echo '<ul class="table table-striped row">';
    foreach($model as $row) {
//        var_dump($row);die;
        $text= substr($row['prod_start'],-2).'/'.substr($row['prod_start'],0,4).' - '.substr($row['prod_end'],-2).'/'.substr($row['prod_end'],0,4) .'_'.$row['models_codes'] ;
        echo '<li>'.Html::a($text,Url::to(['model',
                'name'=>$row['model_name'],
                'model_name'=>$row['model_name'].'-'.$row['models_codes'],
                'catalog'=>$row['catalog'],
                'catalog_code'=>$row['catalog_code'],
                'user_id'=>$row['user_id'],
        ])).'</li>';
    }
echo '</ul>';

