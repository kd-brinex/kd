<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 24.06.15
 * Time: 16:47
 */
//var_dump($dataProvider->models);die;
$model = $dataProvider->models;
foreach ($model as $m) {

        echo '<div>';
        echo '<div class="col-xs-6 col-lg-2 col-md-3 catalog-block">';
        echo $this->render($view, ['model' => $m]);
        echo '</div></div>';

}