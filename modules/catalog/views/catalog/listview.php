<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 24.06.15
 * Time: 16:47
 */
use yii\widgets\ListView;

//var_dump($group);die;
$model = $dataProvider->models;
foreach ($model as $m) {
    if ($m['main_group'] == $group) {
        echo '<div class="col-xs-2 row">';
        echo $this->render('block', ['model' => $m, 'group' => $group]);
        echo '</div>';
    }
}