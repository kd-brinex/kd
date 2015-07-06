<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Collapse;
use yii\bootstrap\Modal;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 29.06.15
 * Time: 11:51
 */
$n=0;
$maxn=round(count($dataProvider)/4);
//var_dump($maxn);die;
foreach($dataProvider as $name=>$model)
{
    if ($n==0){echo '<div class="col-xs-12 col-md-3 row">';}

    Modal::begin([
        'header' => '<h2>'.$name.'</h2>',
        'toggleButton' => [
            'tag' => 'button',
//            'class' => 'btn btn-lg btn-block btn-info ',
            'class' => 'col-xs-12 row ',
            'label' => $name,
        ]
    ]);
//        var_dump($model);die;
    echo $this->render('_index_group_submodel',['model'=>$model]);

    Modal::end();
    $n=$n+1;
    if ($n>$maxn){echo '</div>';$n=0;}

}
if ($n>0){echo '</div>';$n=0;} //Закрываем див