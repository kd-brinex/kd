<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 24.06.15
 * Time: 16:47
 */

$model = $dataProvider->models;
foreach ($model as $m) {
    if ($m[$group['key']] == $group['value']) {
        echo '<div class="col-xs-2 row">';
        echo $this->render($view, ['model' => $m,'toyota'=>$toyota]);
        echo '</div>';
    }
}