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
        echo '<li>'.Html::a($text,Url::to(array_merge(['model'],$row))).
        '</li>';
    }
echo '</ul>';

